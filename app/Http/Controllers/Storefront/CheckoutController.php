<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\OrderMailService;
use App\Services\PromotionService;
use App\Services\WhatsAppNotificationService;
use App\Actions\ProcessCheckoutAction;
use App\Exceptions\CheckoutException;
use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected ProcessCheckoutAction $processCheckoutAction;
    protected WhatsAppNotificationService $whatsappService;
    protected PromotionService $promotionService;
    protected OrderMailService $orderMailService;

    public function __construct(
        CartService $cartService,
        ProcessCheckoutAction $processCheckoutAction,
        WhatsAppNotificationService $whatsappService,
        PromotionService $promotionService,
        OrderMailService $orderMailService
    ) {
        $this->cartService = $cartService;
        $this->processCheckoutAction = $processCheckoutAction;
        $this->whatsappService = $whatsappService;
        $this->promotionService = $promotionService;
        $this->orderMailService = $orderMailService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $cart->load(['items.product.primaryImage', 'items.variant']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cart->subtotal;

        // Re-validate any applied promo against the current subtotal so the displayed
        // discount matches what ProcessCheckoutAction will actually charge.
        $discount = 0;
        $applied = session('applied_promo');
        if ($applied && !empty($applied['code'])) {
            $promotion = $this->promotionService->findByCode($applied['code']);
            if ($promotion && $promotion->isValid($subtotal)) {
                $discount = $this->promotionService->calculateDiscount($promotion, $subtotal);
                session(['applied_promo' => [
                    'code' => $promotion->code,
                    'name' => $promotion->name,
                    'discount' => $discount,
                ]]);
            } else {
                session()->forget('applied_promo');
            }
        }

        // Delivery fee — same calculator ProcessCheckoutAction charges with, so the
        // quote always equals the charged total.
        $deliveryFee = \App\Support\DeliveryFee::for((float) $subtotal);

        $total = ($subtotal - $discount) + $deliveryFee;

        return view('storefront.checkout', compact('cart', 'subtotal', 'discount', 'deliveryFee', 'total'));
    }

    public function process(CheckoutRequest $request)
    {
        $cart = $this->cartService->getCart();
        $cart->load(['items.product', 'items.variant']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            $promoCode = session('applied_promo')['code'] ?? null;

            $order = $this->processCheckoutAction->execute(
                $cart,
                $request->validated(),
                $promoCode
            );
        } catch (CheckoutException $e) {
            // Customer-safe domain failure (out of stock, invalid variant, …).
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            // Unexpected (DB deadlock, outage): log details, show a generic message.
            Log::error('Checkout failed: ' . $e->getMessage(), [
                'cart_id' => $cart->id,
                'user_id' => auth()->id(),
            ]);
            return back()->withInput()->with('error', 'We could not place your order right now. Please try again in a moment.');
        }

        session()->forget('applied_promo');
        // Track every order placed in this session (array), so placing a second order
        // never revokes access to the first order's confirmation/handoff.
        session()->push('placed_order_ids', $order->id);

        // Fire the order-placed emails (customer confirmation + vendor alert). Runs
        // after the order is committed and is fully non-fatal — a mail issue must never
        // block a placed order.
        $this->orderMailService->sendPlacedNotifications($order);

        // Payment-gateway-style handoff: always land on the on-site confirmation anchor
        // first (the order record). When WhatsApp is configured it opens the chat.
        return redirect()->route('checkout.confirmation', $order);
    }

    /**
     * Order-placed confirmation page / receipt. Auto-opens the WhatsApp handoff on
     * first load when configured. Access: this session placed it, OR the signed-in
     * customer owns it (so logged-in users keep a permanent recovery path).
     */
    public function confirmation(Order $order)
    {
        if (! $this->canAccessOrder($order)) {
            abort(403);
        }

        $order->load('items');

        $whatsappReady = $this->whatsappService->isConfigured();

        // Pre-compute the wa.me deep link server-side so the page can render it as a
        // plain anchor (navigation via href is not blocked by CSP form-action). If URL
        // generation fails, degrade gracefully to the "our team will contact you" path.
        $whatsappUrl = null;
        if ($whatsappReady) {
            try {
                $whatsappUrl = $this->whatsappService->generateUrl($order);
            } catch (\Throwable $e) {
                Log::error('WhatsApp URL generation failed on confirmation: ' . $e->getMessage(), ['order_id' => $order->id]);
                $whatsappReady = false;
            }
        }

        // Auto-open only until the handoff has been opened once, and never for a
        // cancelled/refunded order.
        $autoOpenWhatsapp = $whatsappReady
            && $order->whatsapp_sent_at === null
            && ! in_array($order->status, [Order::STATUS_CANCELLED, Order::STATUS_REFUNDED], true);

        // Record the handoff server-side the moment we first serve an auto-open render.
        // This guarantees the "opened once" invariant even if the client's best-effort
        // keepalive mark POST is dropped (some mobile webviews don't honour keepalive) —
        // otherwise a dropped POST would re-auto-open WhatsApp on every Back/reload. The
        // in-memory $order is left untouched, so THIS render still auto-opens exactly once;
        // the next reload sees whatsapp_sent_at set and drops the auto-open. Access here is
        // already gated by canAccessOrder(), so a crawler/prefetch can't trigger it.
        if ($autoOpenWhatsapp) {
            $this->markWhatsAppOpened($order);
        }

        return view('storefront.checkout-confirmation', compact('order', 'whatsappReady', 'whatsappUrl', 'autoOpenWhatsapp'));
    }

    /**
     * WhatsApp handoff (POST — state-changing, CSRF-protected, not prefetchable).
     * Records that the chat was opened and returns the prefilled wa.me business-chat
     * URL as JSON. The browser navigates to wa.me via the page's anchor/href — NOT a
     * server redirect through a form — because CSP `form-action 'self'` blocks a form
     * that navigates off-site. Atomic + idempotent: never downgrades a status the
     * vendor already advanced.
     */
    public function whatsapp(Order $order)
    {
        if (! $this->canAccessOrder($order)) {
            abort(403);
        }

        if (! $this->whatsappService->isConfigured()) {
            return response()->json([
                'error' => 'WhatsApp checkout is currently unavailable. Our team will contact you about this order.',
            ], 409);
        }

        try {
            $whatsappUrl = $this->whatsappService->generateUrl($order);
        } catch (\Throwable $e) {
            Log::error('WhatsApp handoff failed: ' . $e->getMessage(), ['order_id' => $order->id]);

            return response()->json([
                'error' => 'We could not open WhatsApp. Our team will contact you about this order.',
            ], 422);
        }

        $this->markWhatsAppOpened($order);

        return response()->json(['url' => $whatsappUrl]);
    }

    /**
     * Atomically record that the WhatsApp handoff was opened for this order. Re-reads
     * the row under a pessimistic lock so a concurrent admin status change (e.g. cancel)
     * is never clobbered; idempotent and one-directional (only pending -> whatsapp_sent,
     * and only sets the timestamp once). Does not mutate the passed-in $order instance.
     */
    private function markWhatsAppOpened(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $fresh = Order::whereKey($order->getKey())->lockForUpdate()->first();
            if ($fresh && $fresh->whatsapp_sent_at === null) {
                $fresh->whatsapp_sent_at = now();
                if ($fresh->status === Order::STATUS_PENDING) {
                    $fresh->status = Order::STATUS_WHATSAPP_SENT;
                }
                $fresh->save();
            }
        });
    }

    /**
     * A confirmation/handoff is accessible if this session placed the order, or the
     * authenticated customer owns it (permanent recovery path for logged-in users).
     */
    private function canAccessOrder(Order $order): bool
    {
        if (in_array($order->id, (array) session('placed_order_ids', []), true)) {
            return true;
        }

        return auth()->check() && (int) $order->user_id === (int) auth()->id();
    }

    public function applyPromo(Request $request)
    {
        $request->validate(['promo_code' => 'required|string|max:50']);

        $promotion = $this->promotionService->findByCode($request->promo_code);

        if (!$promotion) {
            return back()->with('error', 'Invalid promo code.');
        }

        $cart = $this->cartService->getCart();
        $cart->load('items.product');
        $subtotal = $cart->subtotal;

        if (!$promotion->isValid($subtotal)) {
            return back()->with('error', 'This promo code is expired or does not meet the minimum order requirement.');
        }

        $discount = $this->promotionService->calculateDiscount($promotion, $subtotal);

        session(['applied_promo' => [
            'code' => $promotion->code,
            'name' => $promotion->name,
            'discount' => $discount,
        ]]);

        return back()->with('success', "Promo code '{$promotion->code}' applied! You save " . money($discount));
    }
}

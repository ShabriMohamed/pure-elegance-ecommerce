<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\PromotionService;
use App\Services\WhatsAppNotificationService;
use App\Actions\ProcessCheckoutAction;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected ProcessCheckoutAction $processCheckoutAction;
    protected WhatsAppNotificationService $whatsappService;
    protected PromotionService $promotionService;

    public function __construct(
        CartService $cartService,
        ProcessCheckoutAction $processCheckoutAction,
        WhatsAppNotificationService $whatsappService,
        PromotionService $promotionService
    ) {
        $this->cartService = $cartService;
        $this->processCheckoutAction = $processCheckoutAction;
        $this->whatsappService = $whatsappService;
        $this->promotionService = $promotionService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $cart->load(['items.product.primaryImage', 'items.variant']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('storefront.checkout', compact('cart'));
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

            // Clear session promo
            session()->forget('applied_promo');

            // Generate WhatsApp URL and mark as sent
            $whatsappUrl = $this->whatsappService->generateUrl($order);
            $order->update([
                'whatsapp_sent_at' => now(),
                'status' => Order::STATUS_WHATSAPP_SENT,
            ]);

            return redirect()->away($whatsappUrl);

        } catch (\Exception $e) {
            Log::error('Checkout failed: ' . $e->getMessage(), [
                'cart_id' => $cart->id,
                'user_id' => auth()->id(),
            ]);
            return back()->withInput()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
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

        return back()->with('success', "Promo code '{$promotion->code}' applied! You save LKR " . number_format($discount, 2));
    }
}

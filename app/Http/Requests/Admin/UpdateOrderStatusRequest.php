<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Order;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(array_keys(Order::statuses())),
            ],
            'payment_status' => [
                'nullable',
                Rule::in(['unpaid', 'paid', 'refunded']),
            ],
            'tracking_number' => ['nullable', 'string', 'max:100'],
        ];
    }
}

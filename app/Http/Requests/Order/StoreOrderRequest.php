<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_number' => ['nullable', 'string', 'max:100', 'unique:orders,order_number'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'cart_id' => ['nullable', 'integer', 'exists:carts,id'],
            'products' => ['nullable'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'status' => ['nullable', 'string', 'max:50'],
            'razorpay_order_id' => ['nullable', 'string', 'max:100'],
        ];
    }
}

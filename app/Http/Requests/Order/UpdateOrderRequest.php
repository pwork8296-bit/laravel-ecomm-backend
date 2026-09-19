<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('order') ?? $this->route('id');

        return [
            'order_number' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('orders', 'order_number')->ignore($id),
            ],
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

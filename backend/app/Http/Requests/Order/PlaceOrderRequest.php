<?php

declare(strict_types=1);

namespace App\Http\Requests\Order;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
            'payment_method' => ['required', 'string', Rule::in(PaymentMethod::values())],
            'coupon_code' => ['nullable', 'string', 'exists:coupons,code'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

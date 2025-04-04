<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|numeric|exists:users,id',
            'address_id' => 'required|numeric|exists:addresses,id',
            'coupon_id' => 'sometimes|numeric',
            'status' => 'sometimes|in:pending,processing,shipped,completed,canceled',
        ];
    }
}

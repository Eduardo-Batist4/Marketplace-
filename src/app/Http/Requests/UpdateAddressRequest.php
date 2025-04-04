<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'street' => 'sometimes|string|min:5|max:200',
            'number' => 'sometimes|numeric',
            'zip' => 'sometimes|string|min:7',
            'city' => 'sometimes|string|min:4',
            'state' => 'sometimes|string|min:4',
            'country' => 'sometimes|string|min:4'
        ];
    }
}

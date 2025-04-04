<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
            'street' => 'required|string|min:5|max:200',
            'number' => 'required|numeric',
            'zip' => 'required|string|min:7',
            'city' => 'required|string|min:4',
            'state' => 'required|string|min:4',
            'country' => 'required|string|min:4'
        ];
    }
}

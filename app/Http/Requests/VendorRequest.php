<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
        if ($this->routeIs('vendor.login')) {
            return [
                'email'    => 'required|email|string|max:255',
                'password' => 'required|string|min:8',
            ];
        } elseif ($this->routeIs('vendor.store')) {
            return [
    'name'       => 'required|string|max:255',
    'address'    => 'required|string|max:255',
    'email'      => 'required|email|string|max:255|unique:users,email',
    'password'   => 'required|string|min:8|confirmed',
    'contact_no' => 'required|string',
];

        } elseif ($this->routeIs('user.password')) {
            return [
                'password' => 'required|string|min:8|confirmed',
            ];
        }

        // Default case if no route matches
        return [];
    }
}

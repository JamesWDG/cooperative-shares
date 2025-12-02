<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileFormRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'address' => 'required',
            'description' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please Enter Your First Name!',
            'last_name.required' => 'Please Enter Your Last Name!',
            'email.required' => 'Please Enter Your Email!',
            'email.email' => 'Please Enter A Valid Email!',
            'phone_number.required' => 'Please Enter Your Phone Number!',
            'phone_number.numeric' => 'Your Phone Number Should Be Numerical',
            'address' => 'Please Enter Your Address!',
            'description' => 'Please Enter Your Description!',
        ];
    }
}

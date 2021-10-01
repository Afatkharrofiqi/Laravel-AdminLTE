<?php

namespace App\Http\Requests\Administrator\ManagementUser;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name wajib diisi!',
            'email.required' => 'Email wajib diisi!',
            'email.email'    => 'Email harus berupa email!'
        ];
    }
}

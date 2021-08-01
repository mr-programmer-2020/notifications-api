<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name'     => 'required|string|min:2|max:20',
            'email'    => 'required|string|unique:users,email',
            'password' => 'required|string|min:6|max:50'
        ];
    }

    public function message()
    {
        return [
            'name.required'     => 'name is required',
            'email.required'    =>'email is required',
            'password.required' =>'password is required',

            'name.max'     => 'name must not be more the 20 letters',
            'name.min'     => 'name must not be less the 2 letters',
            'password.max' => 'password must not be more the 50 letters',
            'password.min' => 'password must not be less then 6 letters',

        ];
    }

}

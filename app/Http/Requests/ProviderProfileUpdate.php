<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProviderProfileUpdate extends FormRequest
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
            'email'                 => Rule::unique('users')->ignore(Auth::user()->id, 'id'),
            'password_confirmation' => 'required_with:password|same:password',
            'street'                => 'string',
            'city'                  => 'string',
            'state'                 => 'string',
            'phone'                 => 'string',
            'website'               => 'string',
            'zip'                   => 'numeric'
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Facades\AuthAdmin;
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
        return AuthAdmin::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($record = null)
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email' . ($record ? (',' . $record->id) : ''),
            'password' => ($record ? 'nullable' : 'required') . '|min:6|confirmed',
        ];
    }
}

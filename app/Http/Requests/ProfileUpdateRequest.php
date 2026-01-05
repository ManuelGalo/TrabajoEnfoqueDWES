<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
   public function rules(): array
{
    return [
        'nombre' => ['required', 'string', 'max:255'],
        'apellidos' => ['required', 'string', 'max:255'],
        'dni' => [
            'required', 
            'string', 
            'size:9', 
            'regex:/^[0-9]{8}[A-Z]$/',
            Rule::unique(User::class)->ignore($this->user()->id)
        ],
        'email' => [
            'required', 
            'string', 
            'lowercase', 
            'email', 
            'max:255', 
            Rule::unique(User::class)->ignore($this->user()->id)
        ],
    ];
}
}

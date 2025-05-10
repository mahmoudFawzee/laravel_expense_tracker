<?php

namespace App\Http\Requests;
use Illuminate\Support\Str;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
protected function prepareForValidation()
{
    $converted = collect($this->all())->mapWithKeys(function ($value, $key) {
        return [Str::snake($key) => $value];
    });

    $this->replace($converted->toArray());
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'=>'sometimes|required|string|min:3',
            'last_name'=>['sometimes','required','min:5'],
            'email'=>['sometimes','required','email',],
            'phone_number'=>['sometimes','required','min:11'],
            'password' => 'sometimes|required|string|nullable',
        ];
    }
}

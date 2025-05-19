<?php

namespace App\Http\Requests;
use Illuminate\Support\Str;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
    
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->getMessages();
    
        // Convert keys from snake_case to camelCase
        $camelCaseErrors = [];
        foreach ($errors as $key => $messages) {
            $camelCaseErrors[Str::camel($key)] = $messages;
        }
    
        throw new HttpResponseException(response()->json([
            'errors' => $camelCaseErrors,
        ], 422));
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
            'email'=>['sometimes','required','email','unique:Users,email'],
            'phone_number'=>['sometimes','required','min:11','unique:Users,phone_number'],
            'password' => 'string|nullable|required_unless:email,null',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepairOrderRequest extends FormRequest
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
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac,flac,wma,m4b,m4p,m4r|max:10240',
            'phone1' => 'required|string',
            'phone2' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0|max:999999.99',
            'final_cost' => 'nullable|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}

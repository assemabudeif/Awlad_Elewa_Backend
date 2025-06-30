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
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac,flac,wma,m4b,m4p,m4r,m4v,m4b,m4p,m4r,m4v',
            'phone1' => 'required|string',
            'phone2' => 'nullable|string',
        ];
    }
}

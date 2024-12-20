<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class StoreReadingRequest extends FormRequest
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
            'reading' => 'required|numeric',
            'type' => ['required', Rule::in(['fbs', 'rbs'])],
            'read_at' => 'required|date',
        ];
    }

    protected function prepareForValidation(){
        $this->merge(['read_at'=>Carbon::parse(str_replace("/","-",$this->read_at))]);
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Change this to true for authorization
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Set default value for source_type to 'existing' if it's not provided
        $this->merge([
            'source_type' => $this->input('source_type', 'existing'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'source_type' => 'required|string|in:existing,new',
            'medium' => 'required|string',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
            'new_service_name' => 'required_if:source_type,new|string', // Required if source_type is 'new'
        ];
    }
}

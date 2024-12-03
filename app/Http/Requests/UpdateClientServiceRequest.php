<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientServiceRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Add authorization logic if needed
    }

    public function rules()
    {

        return [
            'client_id' => 'required|integer',
            'service_id' => 'required|integer',
            'duration' => 'nullable|integer',
            'duration_type' => 'nullable|string',
            'hosting_service' => 'nullable|string|max:255',
            'email_service' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
            'billing_start_date' => 'required|date|',
            'billing_frequency' => 'nullable|in:one-time annually,semi-annually,quarterly,monthly', // Use `in` for validation
            'advance_paid' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',  // Allow decimal values with up to 2 decimal places
        ];
    }

    public function messages()
    {
        return [
            'client_id.required' => 'select client ',
            'billing_start_date.required' => 'The billing start date is required.',
            'duration.required' => 'Please provide a valid duration.',
            'duration_type.required' => 'The duration type must be days, weeks, months, or years.',
            // Add custom messages for other fields as needed
        ];
    }
}

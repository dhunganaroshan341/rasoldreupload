<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOutStandingInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // You can add authorization logic if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_service_id' => 'required|exists:client_services,id',
            'total_amount' => 'required|numeric',
            'prev_remaining_amount' => 'nullable|numeric',
            'all_total' => 'nullable|numeric',
            'paid_amount' => 'nullable|numeric',
            'remaining_amount' => 'nullable|numeric',
            'due_date' => 'required|date',
            'remarks' => 'nullable|string',
            // 'bill_number' => 'nullable|string|unique:outstanding_invoices',
            'bill_number' => 'nullable|string',
            'status' => 'nullable|in:pending,paid,overdue',
        ];
    }
}

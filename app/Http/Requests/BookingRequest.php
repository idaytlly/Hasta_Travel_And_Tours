<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_id' => 'required|exists:cars,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'nullable|date_format:H:i',
            'return_date' => 'required|date|after:pickup_date',
            'return_time' => 'nullable|date_format:H:i',
            'voucher' => [
                'nullable',
                'string',
                Rule::exists('vouchers', 'code')->where('is_active', true),
            ],
            'remarks' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'car_id.required' => 'Please select a car',
            'car_id.exists' => 'Selected car does not exist',
            'pickup_location.required' => 'Pickup location is required',
            'pickup_date.required' => 'Pickup date is required',
            'pickup_date.after_or_equal' => 'Pickup date must be today or later',
            'return_date.required' => 'Return date is required',
            'return_date.after' => 'Return date must be after pickup date',
            'voucher.exists' => 'Invalid voucher code',
        ];
    }
}
<?php

namespace App\Http\Requests\Tattooer;

use Illuminate\Foundation\Http\FormRequest;

class AcceptBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $artisan = auth()->user()->artisan();
        $booking = $this->route('bookingRequest');

        return $artisan && $booking
            && $booking->bookable_id === $artisan->id
            && $booking->bookable_type === get_class($artisan);
    }

    public function rules(): array
    {
        return [
            'price_estimate_min'          => 'required|numeric|min:0',
            'price_estimate_max'          => 'required|numeric|min:0|gte:price_estimate_min',
            'proposed_dates'              => 'nullable|array|max:3',
            'proposed_dates.*.date'       => 'required|date|after:today',
            'proposed_dates.*.period'     => 'nullable|in:morning,afternoon,evening',
            'included_design_versions'    => 'required|integer|min:1',
            'modifications_per_design'    => 'required|integer|min:0',
            'total_deposit_amount'        => 'required|numeric|min:0',
            'client_payment_deadline_days'=> 'required|integer|min:1',
        ];
    }
}

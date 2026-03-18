<?php

namespace App\Http\Requests\Tattooer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->artisan() !== null;
    }

    public function rules(): array
    {
        $artisan = auth()->user()->artisan();
        $userId  = $artisan?->user_id;

        return [
            'avatar'               => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'banner'               => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'first_name'           => 'required|string|max:255',
            'last_name'            => 'required|string|max:255',
            'pseudo'               => 'nullable|string|max:255|unique:users,pseudo,' . $userId,
            'email'                => 'required|email|unique:users,email,' . $userId,
            'phone'                => 'nullable|string|max:20',
            'studio_name'          => 'nullable|string|max:255',
            'address'              => 'nullable|string|max:500',
            'city'                 => 'nullable|string|max:255',
            'postal_code'          => 'nullable|string|max:10',
            'country'              => 'nullable|string|max:255',
            'bio'                  => 'nullable|string|max:2000',
            'styles'               => 'nullable|array',
            'styles.*'             => 'string|max:100',
            'custom_styles'        => 'nullable|array',
            'custom_style_names'   => 'nullable|array',
            'custom_style_names.*' => 'nullable|string|max:100',
            'piercing_types'       => 'nullable|array',
            'piercing_types.*'     => 'string|max:100',
            'years_of_experience'  => 'nullable|integer|min:0|max:50',
            'minimum_price'        => 'nullable|numeric|min:0|max:10000',
            'wait_time_weeks_min'  => 'nullable|integer|min:0|max:52',
            'wait_time_weeks_max'  => 'nullable|integer|min:0|max:52',
            'instagram'            => 'nullable|string|max:255',
            'facebook'             => 'nullable|string|max:255',
            'website'              => 'nullable|url|max:255',
            'is_available'         => 'boolean',
            'accepts_new_clients'  => 'boolean',
            'email_notifications'  => 'boolean',
            'sms_notifications'    => 'boolean',
        ];
    }
}

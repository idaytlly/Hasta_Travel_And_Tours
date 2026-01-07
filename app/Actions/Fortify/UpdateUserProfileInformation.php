<?php

namespace App\Actions\Fortify;

use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the customer's profile information.
     */
    public function update(Customer $customer, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($customer->id),
            ],
            'phone' => ['required', 'string', 'max:15'],
            'ic' => ['required', 'digits:12', Rule::unique('customers')->ignore($customer->id)],
            'street' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postcode' => ['nullable', 'string', 'max:10'],
            'license_no' => ['nullable', 'string', 'max:50'],
        ])->validateWithBag('updateProfileInformation');

        $customer->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'ic' => $input['ic'],
            'street' => $input['street'] ?? null,
            'city' => $input['city'] ?? null,
            'state' => $input['state'] ?? null,
            'postcode' => $input['postcode'] ?? null,
            'license_no' => $input['license_no'] ?? null,
        ])->save();
    }
}

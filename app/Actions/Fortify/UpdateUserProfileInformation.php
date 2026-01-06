<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        // Base validation
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:15'],
        ];

        // Only for customers
        if ($user->usertype === 'customer' || $user->usertype === 'user') {
            $rules = array_merge($rules, [
                'ic' => ['required','digits:12', Rule::unique('users')->ignore($user->id)],
                'street' => ['nullable','string','max:255'],
                'city' => ['nullable','string','max:100'],
                'state' => ['nullable','string','max:100'],
                'postcode' => ['nullable','string','max:10'],
                'license_no' => ['nullable','string','max:50'],
            ]);
        }

        Validator::make($input, $rules)->validateWithBag('updateProfileInformation');

        // Update verified email if changed
        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            // Fill common fields
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
            ]);

            // Fill customer-only fields
            if ($user->usertype === 'customer' || $user->usertype === 'user') {
                $user->forceFill([
                    'ic' => $input['ic'],
                    'street' => $input['street'] ?? null,
                    'city' => $input['city'] ?? null,
                    'state' => $input['state'] ?? null,
                    'postcode' => $input['postcode'] ?? null,
                    'license_no' => $input['license_no'] ?? null,
                ]);
            }

            $user->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}

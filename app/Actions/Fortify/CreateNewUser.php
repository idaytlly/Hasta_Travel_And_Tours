<?php

namespace App\Actions\Fortify;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): Customer
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'matricNum' => ['required', 'string', 'max:255', 'unique:customers'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return Customer::create([
            'name' => $input['name'],
            'matricNum' => $input['matricNum'], 
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'customer', // DEFAULT
        ]);
    }
}

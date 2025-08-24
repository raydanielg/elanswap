<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PhoneAuthProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return User::where('id', $identifier)->first();
    }

    public function retrieveByToken($identifier, $token)
    {
        $user = User::where('id', $identifier)->first();
        return $user && $user->getRememberToken() === $token ? $user : null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        $user->save();
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (!isset($credentials['phone'])) {
            return null;
        }

        // Check if the email is actually a phone number in the format used for password reset
        $phone = str_replace('@elanswap.reset', '', $credentials['email'] ?? '');
        
        // If it's not in the reset format, use the phone field directly
        if ($phone === ($credentials['email'] ?? '')) {
            $phone = $credentials['phone'];
        }

        return User::where('phone', $phone)->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (!isset($credentials['password'])) {
            return false;
        }
        
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }

    /**
     * Update the user's hashed password if required.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $password
     * @param  bool  $force
     * @return void
     */
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // If the password needs to be rehashed, we'll do it here
        if ($force || $this->needsRehash($user->getAuthPassword())) {
            $user->password = Hash::make($credentials['password']);
            $user->save();
        }
    }

    /**
     * Determine if the given password needs to be rehashed.
     *
     * @param  string  $hashedPassword
     * @return bool
     */
    protected function needsRehash($hashedPassword)
    {
        return Hash::needsRehash($hashedPassword);
    }
}

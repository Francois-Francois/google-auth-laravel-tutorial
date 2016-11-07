<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Validation\Validator;

/**
 * Class CustomValidator
 * @package App\Http\Requests
 */
class CustomValidator extends Validator
{
    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateTokenIsOk($attribute, $value, $parameters)
    {
        try {
            $user = User::findOrFail(session('2fa:user:id'));

        } catch (\Exception $exc) {
            return false;
        }

        $secret = \Crypt::decrypt($user->google2fa_secret);

        return \Google2FA::verifyKey($secret, $value);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return mixed
     */
    public function validateTokenIsValid($attribute, $value, $parameters)
    {
        $secret = \Crypt::decrypt(session('google2fa_secret'));

        return \Google2FA::verifyKey($secret, $value);
    }
}
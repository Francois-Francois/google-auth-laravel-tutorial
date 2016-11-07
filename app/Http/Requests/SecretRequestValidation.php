<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SecretRequestValidation
 * @package App\Http\Requests
 */
class SecretRequestValidation extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        try {
            User::findOrFail(
                session('2fa:user:id')
            );
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'totp' => 'bail|required|digits:6|token_is_ok',
        ];
    }
}

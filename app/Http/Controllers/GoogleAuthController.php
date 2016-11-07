<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivateSecretValidation;
use Illuminate\Http\Request;
use Base32\Base32;

/**
 * Class GoogleAuthController
 * @package App\Http\Controllers
 */
class GoogleAuthController extends Controller
{

    protected $redirectTo = '/';


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function enableTwoFactor(Request $request)
    {
        //Generate a secret key in Base32 format
        $secret = strtoupper(Base32::encode(random_bytes(10)));
        $user = auth()->user();

        session(['google2fa_secret' => \Crypt::encrypt($secret), '2fa:user:id' => auth()->user()->id]);

        //generate image for QR barcode
        $QRCode = \Google2FA::getQRCodeGoogleUrl(
            config('app.name'),
            $user->email,
            $secret,
            200
        );

        return view('2fa/enable', ['image' => $QRCode,
            'secret' => $secret]);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function disableTwoFactor()
    {
        $user = auth()->user();

        $user->google2fa_secret = null;
        $user->save();

        return view('2fa/disable');
    }

    /**
     * @param ActivateSecretValidation $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activateTwoFactor(ActivateSecretValidation $request)
    {

        auth()->user()->google2fa_secret = session('google2fa_secret');
        auth()->user()->save();
        session()->forget(['google2fa_secret', '2fa:user:id']);

        return redirect()->intended($this->redirectTo)->with(['success' => 'Your account is secured !']);
    }

}

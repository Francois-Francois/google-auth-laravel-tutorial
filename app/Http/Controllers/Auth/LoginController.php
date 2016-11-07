<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SecretRequestValidation;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    /**
     * @param Request $request
     * @param Authenticatable $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function authenticated(Request $request, Authenticatable $user)
    {
        if (!is_null($user->google2fa_secret)) {
            \Auth::logout();

            $request->session()->put('2fa:user:id', $user->id);

            return redirect('2fa/validate');
        }

        return redirect()->intended($this->redirectTo);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function validate2fa()
    {
        if (session('2fa:user:id')) {
            return view('2fa/validate');
        }

        return redirect('login');
    }

    /**
     * @param SecretRequestValidation $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postValidate2fa(SecretRequestValidation $request)
    {
        $userId = $request->session()->pull('2fa:user:id');

        //login and redirect user
        \Auth::loginUsingId($userId);

        return redirect()->intended($this->redirectTo);
    }
}

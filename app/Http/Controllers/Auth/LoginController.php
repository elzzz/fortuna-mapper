<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Support\Facades\Log;
use Socialite;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFBProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleFBProviderCallback()
    {
        $facebookUser = Socialite::driver('facebook')->user();
        $findUser = User::where('email', $facebookUser->email)->first();

        if ($findUser) {
            Auth::login($findUser);
            return redirect('/')->with('success', 'Logged in successfully [FB]');
        } else {
            $user = new User;
            $user->name = $facebookUser->name;
            $user->email = $facebookUser->email;
            $user->password = bcrypt(123456);
            $user->save();
            Auth::login($user);
            return redirect('/')->with('success', 'User was created successfully [FB]');
        }
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToVKProvider()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleVKProviderCallback()
    {
        $vkUser = Socialite::driver('vkontakte')->user();
        $findUser = User::where('email', $vkUser->accessTokenResponseBody['email'])->first();

        if ($findUser) {
            Auth::login($findUser);
            return redirect('/')->with('success', 'Logged in successfully [VK]');
        } else {
            $user = new User;
            $user->name = $vkUser->name;
            $user->email = $vkUser->accessTokenResponseBody['email'];
            $user->password = bcrypt(123456);
            $user->save();
            Auth::login($user);
            return redirect('/')->with('success', 'User was created successfully [VK]');
        }
    }
}

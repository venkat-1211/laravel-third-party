<?php

namespace App\Http\Controllers\AuthenticationUserManagement;

use Illuminate\Http\Request;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SocialLoginController extends Controller
{

    public function index()
    {
        return view('Authentication-User-Management.login');
    }

    public function home()
    {
        return view('Authentication-User-Management.home');
    }
    
    // Redirect to provider
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Callback from provider
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();   // stateless()  only use local. don't use in protection

            // Check if user exists
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                // Create user if doesn't exist
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    'password' => bcrypt(uniqid()), // Random password
                ]);
            }

            // Login user
            Auth::login($user, true);

            return redirect('/home'); // after login
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Login failed!');
        }
    }
}

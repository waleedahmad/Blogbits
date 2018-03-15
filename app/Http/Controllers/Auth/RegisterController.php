<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Laravel\Socialite\Facades\Socialite;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    // Social Authentication Methods

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToFacebookProvider()
    {
        return Socialite::driver('facebook')->scopes(["publish_actions, manage_pages", "publish_pages"])->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleFacebookProviderCallback()
    {
        try {
            $user = Socialite::driver('facebook')->stateless()->user();

        } catch (Exception $e) {
            return redirect('auth/facebook');
        }

        $authUser = $this->findOrCreateUser($user, 'facebook');

        Auth::login($authUser, true);

        return redirect('/');
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return Response
     */
    public function redirectToGoogleProvider()
    {
        return Socialite::driver('google')->scopes(['profile','email'])->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return Response
     */
    public function handleGoogleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

        } catch (Exception $e) {
            return redirect('auth/google');
        }

        $authUser = $this->findOrCreateUser($user, 'google');

        Auth::login($authUser, true);

        return redirect('/');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $user
     * @param $provider
     * @return User
     */
    private function findOrCreateUser($user, $provider)
    {
        if ($authUser = User::where('email', $user->email)->first()) {
            if($provider === 'google'){
                if(!$authUser->google_id){
                    $authUser->google_id = $user->id;
                    $authUser->save();
                }
            }

            if($provider === 'facebook'){
                if(!$authUser->facebook_id){
                    $authUser->facebook_id = $user->id;
                }
                $authUser->token = $user->token;
                $authUser->save();
            }
            return $authUser;
        }

        return User::create([
            'name'          => $user->name,
            'email'         => $user->email,
            'username'      => $this->generateUsername($user->email),
            $provider.'_id' => $user->id,
            'avatar'        => $user->avatar
        ]);
    }

    /**
     * Generate username from email
     * @param $email
     * @return array|string
     */
    private function generateUsername($email){
        $username = explode("@", $email);
        $username = $username[0];

        $count = $this->checkOccurrences($username);
        if($count > 0){
            $count++;
            $username = $username.$count;
        }
        return $username;
    }

    /**
     * Check Occurrences of username in users table
     * @param $username
     * @return mixed
     */
    private function checkOccurrences($username){
        return User::where('username','=',$username)->count();
    }

    protected function registered(Request $request, $user)
    {
        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
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
            return Redirect::to('auth/facebook');
        }

        $authUser = $this->findOrCreateUser($user, 'facebook');

        Auth::login($authUser, true);

        return Redirect::to('/');
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
            return Redirect::to('auth/google');
        }

        $authUser = $this->findOrCreateUser($user, 'google');

        Auth::login($authUser, true);

        return Redirect::to('/');
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
}

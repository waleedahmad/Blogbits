<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{

    public function configView()
    {
        return view('config')->with('config', $this->getConfig());
    }

    /**
     * BlogBits Configuration settings
     * @param Request $request
     * @param $type
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function config(Request $request, $type)
    {
        if ($request->isMethod('POST')) {
            switch ($type) {
                case 'blog':
                    if ($this->updateConfig($request->all(), $type)) {
                        session()->flash('blog_flash', 'Blog config updated.');
                        return redirect('/config#blog');
                    }
                    break;
                case 'scheduler':
                    if ($this->updateConfig($request->all(), $type)) {
                        session()->flash('scheduler_flash', 'Scheduler config updated.');
                        return redirect('/config#scheduler');
                    }
                    break;
                default:
                    return redirect('/config');
            }
        }
    }

    /**
     * Update configiration
     * @param $config
     * @param $type
     * @return bool
     */
    public function updateConfig($config, $type)
    {
        foreach ($this->getConfigProps($type) as $prop) {

            if($prop === 'scheduler_start_time' || $prop === 'scheduler_end_time'){
                $config[$prop] = intval(substr($config[$prop], 0, 2));
            }

            Config::where('name', '=', $prop)->update([
                'value' => $config[$prop]
            ]);
        }

        return true;
    }

    /**
     * Get Enabled Configuration props
     * @return \Illuminate\Support\Collection
     */
    public function getConfigProps($type)
    {
        return Config::where('type', '=', $type)->pluck('name');
    }

    /**
     * Return blogbits app config array
     * @return array
     */
    public function getConfig()
    {
        return [
            'post_link' => Config::where('name', '=', 'post_link')->first()->value,
            'pinterest' => Config::where('name', '=', 'pinterest')->first()->value,
            'facebook' => Config::where('name', '=', 'facebook')->first()->value,
            'active_blog' => Config::where('name', '=', 'active_blog')->first()->value,
            'sync_folder' => Config::where('name', '=', 'sync_folder')->first()->value,
            'default_tags' => Config::where('name', '=', 'default_tags')->first()->value,
            'batch_post_limit' => Config::where('name', '=', 'batch_post_limit')->first()->value,
            'scheduler_frequency' => Config::where('name', '=', 'scheduler_frequency')->first()->value,
            'scheduler_start_time' => Config::where('name', '=', 'scheduler_start_time')->first()->value,
            'scheduler_end_time' => Config::where('name', '=', 'scheduler_end_time')->first()->value,
            'user_email' => Auth::user()->email,
            'frequencies' => [
                'everyMinute',
                'everyFiveMinutes',
                'everyTenMinutes',
                'everyThirtyMinutes',
                'hourly'
            ]
            
        ];
    }

    /**
     * Return Post batch limit
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBatchPostLimit()
    {
        return response()->json(['batch_limit' => Config::where('name', '=', 'batch_post_limit')->first()->value]);
    }

    /**
     * Update user account settings
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function userConfig(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if ($this->userValidator(
            $request->all(),
            $this->getUpdateRules($email, $password)
        )->passes()
        ) {
            if ($this->updateUser($this->getUpdateUser($email, $password))) {
                session()->flash('account_flash', 'Account settings updated.');
                return redirect('/config#account');
            }
        }
    }

    /**
     * return User to be updated
     * @param $email
     * @param $password
     * @return array
     */
    protected function getUpdateUser($email, $password)
    {
        $userUpdate = [
            'email' => $email
        ];

        if ($password) {
            $userUpdate['password'] = bcrypt($password);
        }

        return $userUpdate;
    }

    /**
     * Update User in database
     * @param $user
     * @return mixed
     */
    public function updateUser($user)
    {
        return User::where('email', '=', Auth::user()->email)->update($user);
    }

    /**
     * Get update rules for validator
     * @param $email
     * @param $password
     * @return array
     */
    protected function getUpdateRules($email, $password)
    {
        return [
            'email' => ($this->isEmailChanged($email)) ? '|unique:users' : '',
            'password' => ($password) ? 'min:6' : ''
        ];
    }

    /**
     * Check if user requested an Email change
     * @param $email
     * @return bool
     */
    public function isEmailChanged($email)
    {
        return Auth::user()->email != $email;
    }

    /**
     * User Validator
     * @param $user
     * @param $extra
     * @return mixed
     */
    public function userValidator($user, $extra)
    {
        return Validator::make($user, [
            'email' => 'required|email' . $extra['email'],
            'password' => $extra['password']
        ]);
    }

    public function updateSchedulerTimings(Request $request){

        return Config::where('name', '=', 'scheduler_start_time')->update([
            'value'  =>  $request->input('start')
        ]) && Config::where('name', '=', 'scheduler_end_time')->update([
            'value'    =>  $request->input('end')
        ]) ? response()->json(true) : response()->json(false);
    }
}

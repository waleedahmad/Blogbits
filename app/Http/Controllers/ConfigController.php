<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    public function getConfig()
    {
        return response()->json([
            'blog' => Config::where('type','=', 'blog')->pluck('value', 'name'),
            'social' => Config::where('type','=', 'social')->pluck('value', 'name'),
            'scheduler' => Config::where('type','=', 'scheduler')->pluck('value', 'name'),
            'frequencies' => [
                'everyMinute',
                'everyFiveMinutes',
                'everyTenMinutes',
                'everyThirtyMinutes',
                'hourly'
            ],
            'user' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'username' => Auth::user()->username,
            ]
        ]);
    }

    /**
     * BlogBits Configuration settings
     * @param Request $request
     * @param $type
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function config(Request $request, $type)
    {
        if ($this->updateConfig($request->all(), $type)) {
            return response()->json([
                'updated' => true
            ]);
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
            if($prop === 'scheduler_start_time' || $prop === 'scheduler_end_time' || $prop === 'social_scheduler_start_time' || $prop === 'social_scheduler_end_time'){
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

        $validator = Validator::make($request->all(), [
            'email' => 'required|' . ($request->email != Auth::user()->email ? 'email|unique:users,email' : ''),
            'username' => 'required|' . ($request->username != Auth::user()->username ? 'unique:users,username' : ''),
            'name' => 'required',
            'password' => '' . ($request->password ? 'min:6' : ''),
        ]);
        if ($validator->passes()) {
            if(Auth::user()->update([
                'email' => $request->email,
                'username' => $request->username,
                'name' => $request->name,
                'password' => $request->password ? bcrypt($request->password) : ''
            ])){
                return response()->json([
                    'updated' => true,
                ]);
            }
        } else {
            return response()->json([
                'updated' => false
            ]);
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
     * Check if user requested an Email change
     * @param $email
     * @return bool
     */
    public function isEmailChanged($email)
    {
        return Auth::user()->email != $email;
    }

    /**
     * Updates scheduler timings
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSchedulerTimings(Request $request){

        $social = ($request->input('type') === 'social') ? 'social_' : '';

        return Config::where('name', '=', $social.'scheduler_start_time')->update([
            'value'  =>  $request->input('start')
        ]) && Config::where('name', '=', $social.'scheduler_end_time')->update([
            'value'    =>  $request->input('end')
        ]) ? response()->json(true) : response()->json(false);
    }
}

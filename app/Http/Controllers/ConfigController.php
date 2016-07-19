<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;

use App\Http\Requests;

class ConfigController extends Controller
{
    /**
     * BlogBits Configuration settings
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function config(Request $request){
        if($request->isMethod('GET')){
            return view('config')->with('config', $this->getConfig());
        }

        if($request->isMethod('POST')){
            if($this->updateConfig($request->all())){
               session()->flash('updated','Config updated.');
                return redirect('/config');
            }
        }
    }

    /**
     * Update configiration
     * @param $config
     * @return bool
     */
    public function updateConfig($config){
        foreach($this->getConfigProps() as $prop){
            Config::where('name','=', $prop)->update([
               'value'  =>  $config[$prop]
            ]);
        }

        return true;
    }

    /**
     * Get Enabled Configuration props
     * @return \Illuminate\Support\Collection
     */
    public function getConfigProps(){
        return Config::all()->pluck('name');
    }

    /**
     * Return blogbits app config array
     * @return array
     */
    public function getConfig(){
        return [
            'post_link'  => Config::where('name','=','post_link')->first()->value,
            'pinterest' =>  Config::where('name','=','pinterest')->first()->value,
            'facebook'  =>  Config::where('name','=','facebook')->first()->value,
            'active_blog' =>  Config::where('name','=','active_blog')->first()->value,
            'sync_folder' =>  Config::where('name','=','sync_folder')->first()->value,
            'default_tags' =>  Config::where('name','=','default_tags')->first()->value,
            'batch_post_limit' =>  Config::where('name','=','batch_post_limit')->first()->value,
        ];
    }
}

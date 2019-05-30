<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function isEmailAddressAlreadyUsed($email)
    {
        if(User::where('email',$email)->count() > 0)
        {
            return "TRUE";
        }
        else
        {
            return "FALSE";
        }
    }
    
    public function addUser(Request $request)
    {
        //$name, $email, $password, $api_key
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->access_key = $request->api_key;
        $user->save();
        return "User succesfully created";
    }
    
    public function getAccessKey(Request $request)
    {
        return User::where('email','=',$request->email)->where('password','=',$request->password)->first()->access_key;
    }
}

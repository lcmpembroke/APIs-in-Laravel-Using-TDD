<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Error;
use Hamcrest\Type\IsObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\ErrorLogHandler;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $email = $request->email;

       var_dump($request->email. ' with password: ' .$request->password . 'aaaaa');

       if (Auth::check())
       {
           var_dump('passed auth check...about to test is $user is an object...');
           //$user = User::whereEmail($email)->first();
           $user = User::where('email',$email);
           var_dump($user->email);
        $token = User::whereEmail($email)->first()->createToken($email);
       }

        // if(Auth::attempt(['email' => $email, 'password' => $request->password], false, false)) 
        // {
        //     $token = User::whereEmail($email)->first()->createToken($email);
        //     var_dump($token);
        // } else 
        // {
        //     var_dump("attempt for authentication failed....******");
        //    // invalid credentials, act accordingly
        // }


    }
}
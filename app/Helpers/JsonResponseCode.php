<?php

namespace App\Helpers;


use Illuminate\Http\Request;

class JsonResponseCode
{
    public static function getUserToken(Request $request){
        $token = $request->header('Authorization');
        $token_avg = !empty($request->header('token-avg'))
            ? $request->header('token-avg')
            : (!empty($request->header('token')) ? $request->header('token') : '');
        if(empty($token_avg) && !empty($token_avg)) return null;
        //check case ko truyen header Authorization, chen token-avg thay the
        if(empty($token) && !empty($token_avg)){{
            $token = $token_avg;
            $request->headers->set('Authorization', 'Bearer '.$token_avg);
        }}
        $user_id = TokenValidator::retrieveUserId($token);
        return $user_id;
    }



}

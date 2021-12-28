<?php


namespace App\Helpers;

use Illuminate\Http\Request;

class GetUserByToken
{
    public static function getUserToken(Request $request){
        $token = $request->header('Authorization', null);
        if(empty($token) || is_null($token)) return null;
        $user_id = TokenValidator::retrieveUserId($token);

    }

}

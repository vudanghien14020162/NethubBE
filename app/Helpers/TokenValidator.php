<?php


namespace App\Helpers;
use DB;

class TokenValidator
{
    public static function retrieveUserId($token){
        $token_parts = explode('.', $request);
        //check valid token
        if(!is_array($token_parts) || empty($token_parts[1])) return false;
        $token_header = $token_parts[1];
        //base 64 decode to get a json string
        $token_header_json = base64_decode($token_header);
        $token_header_array = json_decode($token_header_json, true);
        //check token_header
        if(empty($token_header_json) || !is_array($token_header_array)) return false;
        $user_token = !empty($token_header_array['jti']) ? $token_header_array['jti'] : '';
        if(empty($user_token)) return false;
        $exp = !empty($token_header_array['exp']) ? $token_header_array['exp'] : '';
        if(empty($exp)) return false;
        if($exp > time()){
            $rs = DB::table('oauth_access_tokens')->where('id', $user_token)->first();
            if(!empty($rs) && !empty($rs->user_id)){
                return $rs->user_id;
            }
            return false;
        }
        return false;
    }
}

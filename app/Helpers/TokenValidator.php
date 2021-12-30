<?php


namespace App\Helpers;
use DB;
use function MongoDB\BSON\toRelaxedExtendedJSON;

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

    public static function checkAccessToken($token){
        //kiem tra token, break up the into its three parts
        $token_parts = explode('.', $token);
        //check valid token
        if(!is_array($token_parts) || empty($token_parts[1])) return false;
        $token_header = $token_parts[1];
        //base64 decode to get a json string
        $token_header_json = base64_decode($token_header);
        $token_header_array = json_decode($token_header_json, true);
        //check token_header
        if(empty($token_header_json) || !is_array($token_header_array)) return false;
        $user_token = !empty($token_header_array['jti']) ? $token_header_array['jti'] : '';
        if(empty($user_token)) return false;
        $exp = !empty($token_header_array['exp']) ? $token_header_array['exp'] : '';
        if(empty($exp)) return false;
        $user_id = 0;
        $rs = DB::table('oauth_access_token')->where('id', $user_token)->first();
        if(!empty($rs) && !empty($rs->user_id)){
            $user_id = $rs->user_id;
        }
        $is_expired = true;
        if($exp > time()){
            $is_expired = false;
        }
        $expired_time = date('Y-m-d H:i:s', $exp);
        return [
            'user_id'                   => $user_id,
            'is_expired'                => $is_expired,
            'expired_time'              => $expired_time,
            'expired_time_timestamp'    => $exp
        ];
    }
}

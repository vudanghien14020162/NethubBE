<?php


namespace App\Helpers;


use App\Models\HelpersModel\WhiteListUserHelper;
use App\Models\SmsLog;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Bsms
{
    private static function _call_bsms($sever, $method, $params){
        $res = array(
            'status' => false,
            'msg' => 'Disconnected',
            'data' => null
        );

        try {
            $client = new Client();
            $response = $client->request($sever, $method, $params);
            $resl = json_decode($response->getBody()->getContents());
            if(isset($resl->status)){
                if($resl->status->code != "OK"){
                    $res["msg"] = $resl->status->message ?? "Lỗi kết nối BSMS";
                }else{
                    $res = array(
                        "status" => true,
                        "msg" => "Success",
                        "data" => isset($resl->data) ? $resl->data : null,
                    );
                }
            }
        }catch (\Exception $exception){
            error_log($exception->getMessage());
        }
        return $res;
    }

    private static function _get_token(){
        $server = config('services.bsms.host') . '/crmapi/rest/v2/authentication/token';
        $params = array(
            'headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode(
                array(
                    'username' => config('services.bsms.account'),
                    'password' => config('services.bsms.password'),
                    'organisation' => config('services.bsms.organisation')
                )
            )
        );
        $resl = self::_call_bsms($server, "POST", $params);
        $token = null;
        if($resl["status"]){
            \Request::session()->put('bsms_token', $resl["data"]->token);
            $token = $resl["data"]->token;
        }
        return $token;
    }

    public static function get_by_smartcard_bsms($sc_serial){
        $server = config('services.bsms.host') . '/bsmsapi/rest/installeditem/show?stb=&sc=' . $sc_serial;
        $params = array();
        return self::_call_bsms($server, "GET", $params);
    }

    public static function send_sms_via_plus_avg($phone, $sms_message){
        $status = false;
        $message = 'Can not send sms';
        $url = 'https:://plus.avg.vn/V2/sendOtpForMobile';
        $type = 'avg';
        $sign = md5($phone.$sms_message.$type);
        $data = [
            'client_id' => 3,
            'client_secret' => 'QjZJNIALJVXc7ntEo8Axu7FT6631BXUKb0tZBUV6',
            'version' => '1.1',
            'phone' => $phone,
            'msg' => $sms_message,
            'type' => $type,
            'sign' => $sign
        ];
        $rs = Crm::crulPost($url, $data);
        if(isset($rs['header']['http_code']) && $rs['header']['http_code'] == 200){
            try {
                $rs = $rs['data']??[];
                $rs = json_decode($rs);
                if(isset($rs->status_code) & $rs->status_code == 200){
                    $status = true;
                    $message = $rs->error_description??$rs->extra_data??'Unknown errror';
                }else{
                    $message = $rs->error_description??$rs->extra_data??'Unknown error';
                }
            }catch (\Exception $ex){
                $message = $ex->getMessage();
            }
        }else{
            $message = 'Can not connect to api';
        }
        return [
            'status' => $status,
            'msg' => $message
        ];
    }
    public static function send_sms($phone, $otp, $option = []){
        if(WhiteListUserHelper::isDefaultOtp($phone)){
            return true;
        }
        try {
            $message = $otp . ConstResponse::$message_sms_otp_nethub;
            if(isset($option['password']) && $option['password']){
                $message = $otp . ConstResponse::$message_sms_password;
            }
            $rps = self::send_sms_via_plus_avg($phone, $message);
            if($rps['status']){
                $ins = array(
                    'phone' => $phone,
                    'message' => $message,
                    'sent' => 1,
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                );
                SmsLog::insert($ins);
            }else{
                return false;
            }
            return true;
        }catch (\Exception $exception){
            Log::info("message" . $exception->getMessage());
            return false;
        }
    }
}

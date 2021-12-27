<?php


namespace App\Http\Controllers;


use App\Helpers\BaseResponse;
use App\Helpers\Bsms;
use App\Helpers\ConstResponse;
use App\Helpers\Response;
use App\Models\Base\BaseDataResponse;
use App\Models\HelpersModel\ConfigHelper;
use App\Models\HelpersModel\MenuHelper;
use App\Models\HelpersModel\OtpHelper;
use App\Models\HelpersModel\UserHelper;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request){

        $validatedData = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'string|email|unique:users',
            'mobile' => 'required|string|unique:users|min:10|max:13',
            'password' => 'required|string|confirmed|min:6|max:20'
        ], [
            'name.string' => 'Trường họ tên không đúng định dạng chuỗi.',
            'email.string' => 'Trường email nhập không đúng định dạng chuỗi.',
            'email.email' => 'Trường email nhập không đúng định dạng.',
            'email.unique' => 'Trường email đã tồn tại.',
            'mobile.required' => 'Số điện thoại không được bỏ trống.',
            'mobile.string' => 'Số điện thoại không đúng định dạng chuỗi',
            'mobile.unique' => 'Số điện thoại đã tồn tại.',
            'mobile.min' => 'Số điện thoại ít nhất có 10 số',
            'mobile.max' => 'Số điện thoại cao nhất có 13 số',
            'password.required' => 'Password không được để trống.',
            'password.string' => 'Password không đúng định dạng chuỗi',
            'password.min' => 'Password không thấp quá 5 ký tự',
            'password.max' => 'Password vượt quá 20 ký tự'
        ]);

        if(count($validatedData->errors()) > 1){
            $msg = 'Vui lòng kiểm tra lại tài khoản.';
            return BaseResponse::responseRequestJsonError(Response::HTTP_BAD_REQUEST, $msg);
        }
        if($validatedData->fails()){
            $msg = !empty($validatedData->errors()->first()) ? $validatedData->errors()->first() : 'Lỗi đăng ký';
            return BaseResponse::responseRequestJsonError(Response::HTTP_BAD_REQUEST, $msg);
        }
        $user = UserHelper::createUser($request);
        if($user == null){
            $msg = "Tạo tài khoản không thành công.";
            return BaseResponse::responseRequestJsonError(Response::HTTP_BAD_REQUEST, $msg);
        }
        $user->save();
        $userId = $user->id;
        if(isset($userId) && !is_null($userId)){
            $otp = OtpHelper::createOtp($user->mobile, ConstResponse::EXPIRE_TIME_OTP);
            Bsms::send_sms($user->mobile, $otp);
        }else{
            $msg = 'Số lần gửi lại mã OTP đã hết. Vui lòng thử lại sau 10 phút.';
            return BaseResponse::responseRequestJsonError(ConstResponse::$error_send_otp_number_max, $msg);
        }
        $msg = 'Đăng ký tài khoản thành công.';
        $exprie_time = [
            'expire_time' => Carbon::createFromTimestamp(Carbon::now('Asia/Ho_Chi_Minh'))->addMinute(ConstResponse::EXPIRE_TIME_OTP)->timestamp
        ];
        $response['otp'] = $exprie_time;
        $response['expire_time'] = Carbon::createFromTimestamp(Carbon::now('Asia/Ho_Chi_Minh'))->addMinute(ConstResponse::EXPIRE_TIME_OTP)->timestamp;
        return BaseResponse::responseRequestJsonSuccess($response, $msg);
    }

    public function login(Request $request){
        $empty_class = new \stdClass();
        $validator = Validator::make($request->all(), [
            'emailorphone' => 'required|number|min:10|max:13',
            'password' => 'required|string|min:6|max:20'
        ], [
            'emailorphone.required' => 'Số điện thoại không được bỏ trống.',
            'emailorphone.string' => 'Số điện thoại không đúng định dạng chuỗi',
            'emailorphone.min' => 'Số điện thoại ít nhất có 10 số',
            'emailorphone.max' => 'Số điện thoại cao nhất có 13 số',
            'password.required' => 'Password không được để trống.',
            'password.string' => 'Password không đúng định dạng chuỗi',
            'password.min' => 'Password không thấp quá 5 ký tự',
            'password.max' => 'Password vượt quá 20 ký tự'
        ]);
        if(count($validator->errors()->all()) > 1){
            $msg  = 'Vui lòng kiểm tra tài khoản.';
            return BaseResponse::responseRequestJsonError($empty_class, $msg);
        }
        $msg = !empty($validator->errors()->first()) ? $validator->errors()->first() : 'Không thành công.';
        if($msg){
            return BaseResponse::responseRequestJsonError($empty_class, $msg);
        }
        $login_by = '';
        $emailorphone = $request->emailorphone;
        $request->merge([$login_by => $emailorphone]);

        $credentials = request([$login_by, 'password']);
        dd($credentials);
        if(!auth()->attempt($credentials)){
            $empty_class = new \stdClass();
            $msg = "Tài khoản hoặc mật khẩu không chính xác.";
            return BaseResponse::responseRequestJsonError($empty_class, $msg);
        }
        $user = auth()->user();
        if($user->status == 0){
            $emptyMobile = new \stdClass();
            $msg = 'Tài khoản chưa được kích hoạt. Vui lòng nhập Otp để kích hoạt tài khoản';
        }


    }
}

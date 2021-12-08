<?php


namespace App\Helpers;


class BaseResponse
{
    public static function responseRequestJsonSuccess($data, $message = 'success'){
        $datas = [
            'status_code'    => Response::HTTP_OK,
            'message'       => $message,
            'data'          => $data
        ];
        return response()->json($datas);
    }

    public static function responseRequestJsonError($data, $message = 'error'){
        $datas = [
            'status_code'    => Response::HTTP_BAD_REQUEST,
            'message'       => $message,
            'data'          => $data
        ];
        return response()->json($datas);
    }

}

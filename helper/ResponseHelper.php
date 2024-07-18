<?php
namespace Helper;

use Illuminate\Http\Exceptions\HttpResponseException;

class ResponseHelper{
    public static function SuccessReponse($data, $status, $message, $type){
        return response()->json([
            'status' => $status,
            "type" => $type,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public static function BadRequestResponse($type, $status, $message){
        return response()->json([
            'status' => $status,
            "type" => $type,
            'message' => $message,
        ], 400);
    }


    public static function UnauthorizedResponse(){
        throw new HttpResponseException(response()->json([
            'status' => false,
            "type" => "Unauthorized",
            'message' => "Unauthorized"
        ], 401));
    }

    public static function UnprocessableEntityReponse($type, $status, $message){
        throw new HttpResponseException(response()->json([
            'status' => $status,
            "type" => $type,
            'message' => $message
        ], 422));
    }

    public static function NotFoundReponse($type, $message){
        throw new HttpResponseException(response()->json([
            'status' => false,
            "type" => $type,
            'message' => $message
        ], 404));
    }
}
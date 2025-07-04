<?php

class ResponseService {

    public static function response($payload, $statusCode){
        $response = [];
        $response["status"] = $statusCode;
        $response["payload"] = $payload;
        return json_encode($response);
    }


}
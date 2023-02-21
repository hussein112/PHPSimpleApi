<?php

class Response{
    
    use Errors;

    public static function success(string $title = null, string $message = null, array|string $data = null, string $id = null){
        http_response_code(200);
       
        if($title){
            $response[$title] = $message;
        }
        if($id){
            $response['id'] = $id;
        }
        if($data){
            $response['user'] = $data;
        }
        echo json_encode($response);
        exit;
    }
}
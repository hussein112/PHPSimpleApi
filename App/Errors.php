<?php


trait Errors{

    public static function notFound(string $id, string $title = null, string $content = null){
        http_response_code(404);
        echo json_encode([
            ($title) ?? "Not Found" => ($content) ?? "User {$id} not Found"
        ]);
        exit;
    }


    public static function ClientError(string $title = null, string $content = null){
        http_response_code(400);
        echo json_encode([
            ($title) ?? "Uncaught Error" => ($content) ?? "Something Went Wrong"
        ]);
        exit;
    }


    public static function methodNotAllowed(){
        http_response_code(405);
        echo json_encode(
            ["Allowed Methods" => "GET, POST, DELETE, PATCH"]
        );
    }

}
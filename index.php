<?php

declare(strict_types=1);

spl_autoload_register(function($class){
    require __DIR__ . "\App\\$class.php";
});

set_exception_handler("Exceptions::handle");

$db = new DB('localhost', 'test', 'root', '');

$con = $db->getConnection();

$user = new UsersGateway($db);

header("Content-type: application/json");


$url_chunks = explode("/", $_SERVER['REQUEST_URI']);
if($url_chunks[2] != "user"){
    http_response_code(404);
    Errors::notFound($url_chunks[2], null, "Page not Found");
}


$id = ($url_chunks[3]) ?? null;

$controller = new UserController($user);
$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);


// Debug
function printArray($arr){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}
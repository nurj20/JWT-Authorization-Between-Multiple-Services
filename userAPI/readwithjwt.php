<?php
ini_set('display_errors',1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/jason; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== "POST"){
    http_response_code(404);
    echo json_encode(Array(
        'status' => 0,
        'message' => "Invalid HTTP request methods used!"
    ));
    exit();
}

$headers = getallheaders();
$jwt = $headers['Authorization'] ;
if (empty($jwt) ){
    http_response_code(500);
    echo json_encode(Array(
        'status' => 0,
        'message' => "token required!"
    ));
    exit();  
}
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

$secretkey = "jwt1234";
try{
$token = JWT::decode($jwt, $secretkey, array("HS256") );
http_response_code(200);
echo json_encode(Array(
    'status' => 1,
    'message' => 'success',
    'token' => $token
));

}
catch(Exception $ex){
    http_response_code(500);
echo json_encode(Array(
    'status' =>0 ,
    'message' => $ex->getMessage()
    
));


}

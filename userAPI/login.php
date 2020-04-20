<?php
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

$data = json_decode(file_get_contents("php://input"));
if (empty($data->email) || empty($data->password)){
    http_response_code(500);
    echo json_encode(Array(
        'status' => 0,
        'message' => "email and password required!"
    ));
    exit();  
}

require "../users/User.php";
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

$user = new User();
if(($result = $user->login($data->email, $data->password)) !== false)
{
    $payload = Array(
        'iss'=>'localhost',
        'iat'=> time(),
        'nbf' => time()+20,
        'exp' => time()+60,
        'aud' => 'regsteredusers',
        'email'=> $data->email,
        'pwd' => $data->password,
        'id' => $result
    );
    $secretkey = "jwt1234";
    $token = JWT::encode($payload, $secretkey);
    http_response_code(200);
    echo json_encode(Array(
        'status' => 1,
        'message' => "user loggedin successfully",
        'jwt' =>$token
    ));
    exit();  
}
else{
    http_response_code(500);
    echo json_encode(Array(
        'status' => 0,
        'message' => "invalid credentials"
    ));
    exit();
}




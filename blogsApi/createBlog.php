<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; UTF-8");

require "../blogs/Blog.php";
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] !== "POST")
{
    http_response_code(404);
    echo json_encode(Array(
        'status' => 0,
        'message' => "Used HTTP request method not supported" 
    ));
    exit();
}

$data = json_decode(file_get_contents("php://input"));
if (empty($data->user_id) || empty($data->title) || empty($data->details) || empty($data->category_id))
 {
     http_response_code(500);
     echo json_encode(Array(
         'status' =>0,
         'message' => 'error: insufficent data provides'
     ));
    exit(); 
 }

 $headers = getallheaders();
 $jwt = $headers['Authorization'];
 if (is_null($jwt) )
 {
    http_response_code(500);
    echo json_encode(Array(
        'status' =>0,
        'message' => 'authorization failed'
    ));
   exit();   
 }

 try{
    $secretkey = "jwt1234";
    $tokendata=JWT::decode($jwt, $secretkey,array("HS256"));  
    $data->user_id=$tokendata->id;
 }
 catch(Exception $ex){
    http_response_code(500);
    echo json_encode(Array(
        'status' =>0,
        'message' => 'authorization failed'
    ));
   exit();   
 }

$blog = new Blog();
$response = $blog->createBlog($data);
if($response === true){
    http_response_code(200);
    echo json_encode(Array(
        'status' =>1,
        'message' => 'success: registration successful',      
    ));   
}
else{
    http_response_code(500);
    echo json_encode(Array(
        'status' =>0,
        'message' => 'error:Blog creation unseuccessfu ',
        'error' => $response
    ));
}
 $blog->close();

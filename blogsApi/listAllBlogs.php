<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; UTF-8");

require "../blogs/Blog.php";
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] !== "GET")
{
    http_response_code(404);
    echo json_encode(Array(
        'status' => 0,
        'message' => "Used HTTP request method not supported" 
    ));
    exit();
}

// $data = json_decode(file_get_contents("php://input"));
// if (empty($data->user_id) || empty($data->title) || empty($data->details) || empty($data->category_id))
//  {
//      http_response_code(500);
//      echo json_encode(Array(
//          'status' =>0,
//          'message' => 'error: insufficent data provides'
//      ));
//     exit(); 
//  }

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
    // $data->user_id=$tokendata->id;
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
// /////////////////////////////////////////////////
$response = $blog->index();
if(is_null($response)){
    http_response_code(200);
    echo json_encode(Array(
        'status' =>1,
        'message' => 'No blog posts available at the moments',      
    ));   
}
else{
    // $error = "";
    // if (strpos($response, "Duplicate entry") !== false)
    //     if(strpos($response, "@") !== false)
    //     $error = "email address already taken";
    //     else
    //     $error = "username already taken";
    // $response = json_encode($response);
    http_response_code(500);
    echo json_encode(Array(
        'status' =>0,
        'message' => 'Following blogs currently active ',
        'message' => $response
    ));
}
 $blog->close();

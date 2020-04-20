<?php
ini_set("display_errors", 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; UTF-8");

require "../users/User.php";

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
if (empty($data->name) || empty($data->username) || empty($data->email) || empty($data->password))
 {
     http_response_code(500);
     echo json_encode(Array(
         'status' =>0,
         'message' => 'error: insufficent data provides'
     ));
    exit(); 
 }

$user = new User();
$response = $user->register($data);
if($response === true){
    http_response_code(200);
    echo json_encode(Array(
        'status' =>1,
        'message' => 'success: registration successful',      
    ));   
}
else{
    // $error = "";
    if (strpos($response, "Duplicate entry") !== false)
        if(strpos($response, "@") !== false)
        $error = "email address already taken";
        else
        $error = "username already taken";
    http_response_code(500);
    echo json_encode(Array(
        'status' =>0,
        'message' => 'error:registration unsuccessful ',
        'error' => $error
    ));
}
 $user->close();

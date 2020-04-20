<?php
require "../dbh/Database.php";

class User{

    private $name;
    private $username;
    private $emailname;
    private $pwd;
    private $created_at;
    private $updated_at;
    private $conn;

function __construct(){
    $db = new Database();
     $this->conn = $db->connect();
}

public function close(){
    $this->conn->close();
}
// register(insert) new user to users table
public function register($data){

    $pwd =password_hash($data->password, PASSWORD_DEFAULT);
    $query = "insert into users (name, username, email, password, created_at, updated_at) values (?, ?, ?, ?, now(), now());";
    $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssss", $data->name, $data->username, $data->email,$pwd );
        // try{
          if (  $stmt->execute())
          {
            return true
            ;}
          else
            return $stmt->error;
         
}

public function login($email, $pwd){
$query = "select password, id from users where email=?;";
$stmt = $this->conn->prepare($query);
$stmt->bind_param('s', $email);
if($stmt->execute()){
    $rows = $stmt->get_result();
    $row=$rows->fetch_assoc();

    if (password_verify($pwd, $row['password'])){
        return $row['id'];
    }
    else return false;
}
}

}
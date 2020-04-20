<?php
require "../dbh/Database.php";

class Blog{

    private $user_id;
    private $title;
    private $details;
    private $category_id;

function __construct(){
    $db = new Database();
     $this->conn = $db->connect();
}

public function close(){
    $this->conn->close();
}
// register(insert) new user to users table
public function createBlog($data){

    $query = "insert into blogs (user_id, title, details, category_id) values (?, ?, ?, ?);";
    $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssss", $data->user_id, $data->title, $data->details,$data->category_id );
          if (  $stmt->execute())
             return true;
          else return $stmt->error;

    }

        // List all Blog posts
        public function index(){

            $query = "select * from blogs ;";
            $blogs = $this->conn->query($query);
        
                  if (  $blogs->num_rows > 0)
                  {
                    $rowArray = Array();
                    while($row = $blogs->fetch_assoc())
                   { 
                    $blog = Array(
                        "user_id" => $row['user_id'],
                        "title" =>$row['title'],
                        "details" =>$row['details'] ,
                        "category_id" =>$row['category_id'] ,
                    );
                        array_push($rowArray,$blog);
                    }
                    return $rowArray;
                  }
                  else
                    return Array();
                 
                }
        
}


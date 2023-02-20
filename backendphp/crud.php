<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json;");




include 'config.php';


$user = file_get_contents('php://input'); // to read the data from react(witch is in Json)

// we can see it in inspect -- Network -- Payload and Preview

$method = $_SERVER['REQUEST_METHOD']; // there is a case of GEt when we want to bring data , and case of POSt when we want to send data
switch($method){

        // Recieve data from database
    case "GET":
        $con=crud::connect();
        // echo $_SERVER['REQUEST_URI']; exit;
        

        $sql="SELECT * FROM users";
        $path = explode('/', $_SERVER['REQUEST_URI']); // explode exepting 2 parameters first how do you want to explode the string , then  the path
        // print_r($path);  // to show you array of the data
        if(isset($path[4]) && !is_numeric($path[4])){
         // to see if there is an id and it is a number in index of that array
            $sql .= " WHERE id =:id";
            $db = $con->prepare($sql);
            $db->bindValue(':id' , $path[4]);
            $db->execute();
            $data= $db->fetch(PDO::FETCH_ASSOC);
        }else{

            $db =$con->prepare($sql);
            $db->execute();
            $data= $db->fetchAll(PDO::FETCH_ASSOC);
        
        }

    echo json_encode($data);
    break;

        // Send data to database
    case "POST":
        $user = json_decode(file_get_contents('php://input')); // to make php read this as an object from react
        
        $db = crud::connect()->prepare("INSERT INTO users ( id ,name, email, password , gender ,Created_at) VALUES (:id ,:name,:email,:password, :gender ,:created)");
        $created_at = date('Y-m-d');
        $db->bindValue(':id' , $user->id); // to reach the name email and mobile from data 
        $db->bindValue(':name' , $user->name); // to reach the name email and mobile from data 
        $db->bindValue(':email' , $user->email);
        $db->bindValue(':password' , $user->password);
        $db->bindValue(':gender' , $user->gender);
        $db->bindValue(':created' , $created_at);
        if($db -> execute()) {
            $response = ['status' =>1, 'message'=>"Record created succcesfully"];
        }else{
            $response = ['status' =>0, 'message'=>"Record Faild to creat"];
        }
        echo json_encode($response); // to send this message as a Json (you can read it in inspect -- Newtwork)
        break;
    }
<?php


class crud{
 
 static function connect(){
   try{

   $con=new PDO('mysql:localhost=localhost;dbname=backendapi','root','');
   return $con;
 

}catch(PDOException $error){

   echo 'the error ' . $error->getMessage();


}}}
$con=crud::connect();

?>
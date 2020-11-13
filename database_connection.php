<?php
$servername="mysql:host=localhost;dbname=cafeteria";
$username="cafeteria";
$password="itiProject-2020";
try{
$conn=new PDO($servername,$username,$password);

}catch(PDOExeption $e){
    echo "Faild Connect To Database".$e->getMessage();
}
?>

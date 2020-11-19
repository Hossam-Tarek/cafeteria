<?php
$servername="mysql:host=localhost;dbname=cafeteria";
$username="root";
$password="0000";
try{
$conn=new PDO($servername,$username,$password);

}catch(PDOExeption $e){
    echo "Faild Connect To Database".$e->getMessage();
}
?>

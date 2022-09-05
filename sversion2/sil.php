<?php
require "databaseconnection.php";

if(isset($_GET['sid'])){
    veriSil($conn, mysqli_real_escape_string($conn,$_GET['sid']));
}
function veriSil($conn, $id){
    $sql_delete = "DELETE FROM student WHERE sid='$id'";
    mysqli_query($conn,$sql_delete) or die(mysqli_error($conn)) or die('Connection failed :'.mysqli_connect_error()); 
}
?>
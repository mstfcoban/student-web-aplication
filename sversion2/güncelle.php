<?php
require "databaseconnection.php";

if(isset($_GET['sid'])){
  $id = mysqli_real_escape_string($conn,$_GET['sid']);
  $name = mysqli_real_escape_string($conn,$_GET['fname']);
  $surname = mysqli_real_escape_string($conn,$_GET['lname']);
  $birthplace = mysqli_real_escape_string($conn,$_GET['birthplace']);
  $birthdate = mysqli_real_escape_string($conn,$_GET['birthdate']);
  veritabaniGuncelle($conn, $id, $name, $surname, $birthplace, $birthdate);
}
function veritabaniGuncelle($conn, $id, $name, $lname, $bplace, $bdate){
  $sql_update = "UPDATE student SET fname='$name', lname='$lname', birthplace='$bplace', birthdate='$bdate' WHERE sid='$id'";
  $result = mysqli_query($conn, $sql_update) or die('Connection failed :'.mysqli_connect_error());
  echo isset($result);
}
?>
<?php
require "databaseconnection.php";

if(isset($_GET['fname'])){
  $name = mysqli_real_escape_string($conn,$_GET['fname']);
  $surname = mysqli_real_escape_string($conn,$_GET['lname']);
  $birthplace = mysqli_real_escape_string($conn,$_GET['birthplace']);
  $birthdate = mysqli_real_escape_string($conn,$_GET['birthdate']);
  yeniKayitEkle($conn, $name, $surname, $birthplace, $birthdate);
}
function yeniKayitEkle($conn, $name, $surname, $birthplace, $birthdate){
  $sql_insert = "INSERT INTO student (`fname`, `lname`, `birthplace`, `birthdate`) VALUES ('$name','$surname','$birthplace','$birthdate')";
  mysqli_query($conn, $sql_insert) or die('Connection failed :'.mysqli_connect_error());
  $id = $conn->insert_id;
  echo $id;
}
?>
<?php
require "databaseconnection.php";

if($_GET['durum']=='bul'){
  $id = mysqli_real_escape_string($conn,$_GET['sid']);
  $name = mysqli_real_escape_string($conn,$_GET['fname']);
  $surname = mysqli_real_escape_string($conn,$_GET['lname']);
  $birthplace = mysqli_real_escape_string($conn,$_GET['birthplace']);
  $birthdate = mysqli_real_escape_string($conn,$_GET['birthdate']);
  ara($conn, $id, $name, $surname, $birthplace, $birthdate);
}
else if($_GET['durum']=='date'){
  datebul($conn);
}
else{
  http_response_code(403);
}
function ara($conn,$id,$fname,$lname,$bplace,$bdate){
  $sql_search = "SELECT * FROM student WHERE sid LIKE '$id%' AND fname LIKE '$fname%' AND lname LIKE '$lname%' AND birthplace LIKE '$bplace%' AND birthdate LIKE '$bdate%'";
  $result = mysqli_query($conn, $sql_search) or die('Connection failed :'.mysqli_connect_error());
  while($row = mysqli_fetch_array($result)) {
    echo "
      <tr id='$row[sid]'>
        <td id='0$row[sid]'>".$row['sid']."</td>
        <td id='$row[fname]$row[sid]'>".$row['fname']."</td>  
        <td id='$row[lname]$row[sid]'>".$row['lname']."</td>
        <td id='$row[birthplace]$row[sid]'>".$row['birthplace']."</td>
        <td id='$row[birthdate]$row[sid]'>".$row['birthdate']."</td>
        <td><input class='btn btn-default' onclick='sil($row[sid])' id='sil' data-id='$row[sid]' type='submit' name='sil' value='SİL'></td>
        <td id='$row[sid]guncelle'><input class='btn btn-default' onclick='guncelle()' id='guncelle' 
                   data-id='$row[sid]' data-fname='$row[fname]' data-lname='$row[lname]' 
                   data-bplace='$row[birthplace]' data-bdate='$row[birthdate]'
                   type='submit' name='guncelle' value='GÜNCELLE'></td>
      </tr>";
  } 
}

function datebul($conn){
  $sql_date = "SELECT birthdate FROM student";
  $result = mysqli_query($conn, $sql_date);
  echo "
        <option value= >  </option>
      ";
  while($row = mysqli_fetch_array($result)) {
    echo "
        <option value={$row['birthdate']} > $row[birthdate] </option>
      ";
  }
}
?>
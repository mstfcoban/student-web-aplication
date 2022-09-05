<?php
define('SERVERNAME',"localhost");
define('DATABASE',"studentwebaplication");
define('USERNAME',"root");

$conn = mysqli_connect(SERVERNAME, USERNAME, "", DATABASE) or die('Connection failed :'.mysqli_connect_error());

?>
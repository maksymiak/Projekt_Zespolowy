<?php

//Dane do połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "strona_spamerska";

//Połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
else{
	echo "Connection";
}
?>
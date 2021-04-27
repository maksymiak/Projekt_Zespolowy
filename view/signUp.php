<?php
//Połączenie z bazą znajduje się w pliku config.php
include("config.php");
session_start();

//Pobrane z formularza wartości
$login = $_POST['login'];
$password = $_POST['password'];

//Zapytanie odpowiedzialne za dodanie nowego użytkownika
$sql = "INSERT INTO user (login, password)
VALUES ('$login', '$password')";

if ($conn->query($sql) === TRUE) {
	//Jeżeli wszystko poszło dobrze to dodajemy login użtkownika do sesji
	//i przechodzimy do admin_panel.php
  $_SESSION['login_user'] = $login;
  header("location: admin_panel.php");
} else {
  echo "Error: " . $conn->error;
}
$conn->close();

?>
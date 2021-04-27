<?php
//config.php jest odpowiedzialny za połączanie z bazą danych
include("config.php");
session_start();

//wartości pobrane z formularza
$login = $_POST['login'];
$password = $_POST['password'];

//zapytanie wywołujące id z użytkownika z podanych wartości
$sql = "SELECT id FROM user WHERE login = '$login' and password = '$password'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
	//Jeżeli rsultat jest większy od zera, czyli użytkownik istnieje
	//dodajemy zapisujemy w sesji login użytkownika i przechodzimy
	//do admin_panel.php
	$_SESSION['login_user'] = $login;
    header("location: admin_panel.php");
} else {
	//Jeżeli nie istnieje użytkownik przechodzimy do home.html
  header("location: home.html");
}

mysqli_close($conn);

?>
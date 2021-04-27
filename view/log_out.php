<?php
//rozpoczęcie sesji
session_start();
//usuniecie zmiennej login_user
unset($_SESSION['login_user']);
//przeniesienie do home.html
header("Location:home.html");
?>
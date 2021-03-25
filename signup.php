<?php

require_once "php/builder.php";

session_start();
if (isset($_SESSION["username"])){
	header("Location: home.php");
	exit();
}

$output = build("html/signup.html");

$error = isset($_GET["err"]) ? "<p id=\"error\">" . $_GET["err"] . "</p>" : "<p id=\"error\"></p>";
$nome = isset($_GET["nome"]) ? $_GET["nome"] : "";
$cognome = isset($_GET["cognome"]) ? $_GET["cognome"] : "";
$user = isset($_GET["username"]) ? $_GET["username"] : "";
$email = isset($_GET["email"]) ? $_GET["email"] : "";

$output = str_replace("<p id=\"error\"></p>", $error, $output);
$output = str_replace("%nome%", $nome, $output);
$output = str_replace("%cognome%", $cognome, $output);
$output = str_replace("%user%", $user, $output);
$output = str_replace("%email%", $email, $output);
$output = str_replace("%signupphp%", "php/signup.php", $output);

echo $output;

<?php 

require_once "php/builder.php";

session_start();
if (isset($_SESSION["username"])){
	header("Location: home.php");
	exit();
}

$output = build("html/login.html");

$error = isset($_GET["error"]) ? "<p id=\"error\">" . urldecode($_GET["error"]) . "</p>" : "<p id=\"error\"></p>";
$username = isset($_GET["user"]) ? urldecode($_GET["user"]) : "";

$output = str_replace("<p id=\"error\"></p>", $error, $output);
$output = str_replace("%username%", $username, $output);
$output = str_replace("%loginphp%", "php/login.php", $output);

echo $output;

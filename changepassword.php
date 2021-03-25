<?php

require_once "php/builder.php";
require_once "php/db_connection.php";
require_once "php/sanitizer.php";

session_start();
if (!isset($_SESSION["username"])){
	header("Location: login.php");
	exit();
}

$conn = OpenCon();

$output = build("html/changepassword.html");
$error = isset($_GET["error"]) ? "<p id=\"error\">" . urldecode($_GET["error"]) . "</p>" : "<p id=\"error\"></p>";
$output = str_replace("<p id=\"error\"></p>", $error, $output);
$output = str_replace("%changepassword%", "php/changepassword.php", $output);

echo $output;

CloseCon($conn);

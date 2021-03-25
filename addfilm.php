<?php

session_start();
date_default_timezone_set('Europe/Rome');
if (!isset($_SESSION["username"]))
	abort("login.php");
if ($_SESSION["username"] !== "admin")
	abort("user.php");

require_once "php/builder.php";
require_once "php/sanitizer.php";
require_once "php/db_connection.php";

$output = build("html/addfilm.html");
	$conn = OpenCon();
	$output = str_replace("%Intestazione%","Inserisci un nuovo film" , $output);
	$output = str_replace("%Titolo%", "", $output);
	$output = str_replace("%genere%", "", $output);
	$output = str_replace("%durata%", "", $output);
	$output = str_replace("%anno%", "", $output);
	$output = str_replace("%regia%","", $output);
	$output = str_replace("%cast%", "", $output);
	$output = str_replace("%trama%", "", $output);
	$output = str_replace("%codfilm%", "", $output);
$output = str_replace("%addfilm%", "php/addfilm.php", $output);
echo $output;


function abort($url){
	header("Location: " . $url);
	exit();
}

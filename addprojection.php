<?php

session_start();
if (!isset($_SESSION["username"]))
	abort("login.php");
if ($_SESSION["username"] !== "admin")
	abort("user.php");

require_once "php/sanitizer.php";


require_once "php/builder.php";
require_once "php/db_connection.php";
date_default_timezone_set('Europe/Rome');

$conn = OpenCon();


$data = date('d-m-Y');
$ora = date('H:i');

$output = build("html/editprojection.html");
$output = str_replace("%Titolo%", "Aggiungi un nuova proiezione", $output);
$output = str_replace("%ListaFilm%", listaFilm(), $output);
$output = str_replace("%Data%", $data, $output);
$output = str_replace("%Ora%", $ora, $output);
$output = str_replace("%Sale%", sale(), $output);

$output = str_replace("%editprojection%", "php/addprojection.php", $output);

echo $output;


function abort($url){
	header("Location: " . $url);
	exit();
}

function listaFilm(){
	global $conn;
	$output = "";
	$sql = "SELECT cod, titolo FROM film";
	$films = $conn->query($sql);
	while($film = $films->fetch_assoc())
		$output .= "<option value=\"" . $film["cod"] . "\">" . strip_tags($film["titolo"]) . "</option>";
	return $output;
}

function sale(){
	global $conn;
	$output = "";
	$sql = "SELECT * FROM sala";
	$sale = $conn->query($sql);
	while($sala = $sale->fetch_assoc())
		$output .= "<option value=\"" . $sala["nome"] . "\">" . $sala["nome"] . "</option>";
	return $output;
}

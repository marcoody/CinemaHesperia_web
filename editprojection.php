<?php

session_start();
if (!isset($_SESSION["username"]))
	abort("login.php");
if ($_SESSION["username"] !== "admin")
	abort("user.php");

require_once "php/sanitizer.php";
if (isset($_GET["proiezione"]))
	$idProiezione = sanitizeNumber($_GET["proiezione"]);
if (!isset($idProiezione))
	abort("upcomingschedule.php");

require_once "php/builder.php";
require_once "php/db_connection.php";
date_default_timezone_set('Europe/Rome');

$conn = OpenCon();
$sql = "SELECT *, programmazione.cod AS idProiezione
	FROM programmazione
	JOIN film on programmazione.film = film.cod
	WHERE programmazione.cod = '$idProiezione'";
$proiezioni = $conn->query($sql);

if ($proiezioni->num_rows == 0)
	abort("upcomingschedule.php");
$proiezione = $proiezioni->fetch_assoc();
$idFilm = $proiezione["film"];
$sala = $proiezione["sala"];
$data = date_format(date_create($proiezione["data"]), "d-m-Y");
$ora = date_format(date_create($proiezione["ora"]), "H:i");


$output = build("html/editprojection.html");
$output = str_replace("%Titolo%", $proiezione["titolo"], $output);
$output = str_replace("%ListaFilm%", listaFilm($idFilm), $output);
$output = str_replace("%Data%", $data, $output);
$output = str_replace("%Ora%", $ora, $output);
$output = str_replace("%Sale%", sale($sala), $output);
$output = str_replace("%Proiezione%", $proiezione["idProiezione"], $output);
$output = str_replace("%editprojection%", "php/editprojection.php", $output);

echo $output;


function abort($url){
	header("Location: " . $url);
	exit();
}

function listaFilm($idFilm){
	global $conn;
	$output = "";
	$sql = "SELECT cod, titolo FROM film";
	$films = $conn->query($sql);
	while($film = $films->fetch_assoc())
		$output .= "<option value=\"" . $film["cod"] . "\"" . ($film["cod"] === $idFilm ? " selected=\"selected\"" : "") . ">" . strip_tags($film["titolo"]) . "</option>";
	return $output;
}

function sale($salaSelezionata){
	global $conn;
	$output = "";
	$sql = "SELECT * FROM sala";
	$sale = $conn->query($sql);
	while($sala = $sale->fetch_assoc())
		$output .= "<option value=\"" . $sala["nome"] . "\"" . ($sala["nome"] === $salaSelezionata ? " selected=\"selected\"" : "") . ">" . $sala["nome"] . "</option>";
	return $output;
}

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

$output = build("html/editfilm.html");

if (isset($_GET["cod"])){
	$idFilm = sanitizeNumber($_GET["cod"]);
	$conn = OpenCon();
	$sql = "SELECT *
		FROM film
		WHERE cod='$idFilm'";
	$result = $conn->query($sql);
	if ($result->num_rows === 0) {
		abort("filmcatalog.php?staus=error");
	}
	$film = $result->fetch_assoc();
	$cod = $film["cod"];
	$titolo = $film["titolo"];
	$genere = $film["genere"];
	$durata = $film["durata"];
	$anno = $film["anno"];
	$regia = $film["regia"];
	$cast = $film["cast"];
	$trama = $film["trama"];
	$intestazione = $film["titolo"];
}
else{
	$inputIdFilm = "";
	$intestazione = "Aggiungi un nuovo film";
	$titolo = "";
}
$tasto = "<a href=\"php/deletefilm.php?film=". $cod ."\" class=\"bottone admin\">Cancella questo film</a>";
//$output = str_replace("<div>test</div>",$tasto , $output);
$output = str_replace("%Intestazione%", $intestazione, $output);
$output = str_replace("%Titolo%", strip_tags($titolo), $output);
$output = str_replace("%genere%", strip_tags($genere), $output);
$output = str_replace("%durata%", strip_tags($durata), $output);
$output = str_replace("%anno%", strip_tags($anno), $output);
$output = str_replace("%regia%",strip_tags( $regia), $output);
$output = str_replace("%cast%", strip_tags($cast), $output);
$output = str_replace("%trama%", strip_tags($trama), $output);
$output = str_replace("%codfilm%", strip_tags($cod), $output);


$output = str_replace("%editfilm%", "php/editfilm.php", $output);
echo $output;


function abort($url){
	header("Location: " . $url);
	exit();
}

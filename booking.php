<?php

require_once "php/builder.php";
require_once "php/db_connection.php";
require_once "php/sanitizer.php";

session_start();
if (!isset($_SESSION["username"])){
	header("Location: login.php");
	exit();
}
if(isset($_GET["proiezione"])){
	$proiezioneId = sanitizeNumber($_GET["proiezione"]);
}
if (!isset($proiezioneId)) {
	header("Location: schedule.php");
	exit();
}

date_default_timezone_set('Europe/Rome');
$date = date('Y/m/d');
$conn = OpenCon();

$sql = "SELECT * FROM programmazione JOIN film ON programmazione.film = film.cod WHERE programmazione.cod='$proiezioneId'";

$proiezioneresult = $conn->query($sql);
$proiezione = $proiezioneresult->fetch_assoc();

$date = date_format(date_create($proiezione["data"]), "d-m-Y");
$time = date_format(date_create($proiezione["ora"]), "H:i");
$sala = $proiezione["sala"];

$username= $_SESSION["username"];
$sql= "SELECT nome, cognome FROM utente WHERE username = '$username'";
$utenteresult = $conn->query($sql);
$utente = $utenteresult->fetch_assoc();

$posti = postiLiberi();

$output = build("html/booking.html");
$output = str_replace("%nomefilmgStrip%", strip_tags($proiezione["titolo"]), $output);
$output = str_replace("%nome%", $utente["nome"], $output);
$output = str_replace("%cognome%", $utente["cognome"], $output);
$output = str_replace("%nomefilm%", $proiezione["titolo"], $output);
$output = str_replace("%immagine%", $proiezione["immagine"], $output);
$output = str_replace("%data%", $date, $output);
$output = str_replace("%orario%", $time, $output);
$output = str_replace("%sala%", $proiezione["sala"], $output);
$output = str_replace("%postioccupati%", $posti["occupati"] . "/" . $posti["totali"], $output);
$output = str_replace("%proiezione%", $proiezioneId, $output);
$output = str_replace("<posti/>", selectPosti($posti["liberi"]), $output);

echo $output;

CloseCon($conn);

function selectPosti($liberi) {
	$output = "";
	$nmax = 10;

	if($nmax > $liberi){
		$nmax = $liberi;
	}

	for($i = 1; $i < $nmax; $i = $i + 1){
		$output .= '<option value="' . $i . '">' . $i . '</option>';
	}

	return $output;
}

function postiLiberi() {
	global $conn;
	global $username;
	global $proiezioneId;

	$sql = "SELECT sum(prenotazione.posti) AS occupati, sala.posti AS totali
		FROM prenotazione
		JOIN programmazione ON prenotazione.proiezione = programmazione.cod
		JOIN sala on programmazione.sala = sala.nome
		WHERE proiezione=$proiezioneId";

	$posti = $conn->query($sql)->fetch_assoc();
	$totali = $posti["totali"];
	$occupati = isset($posti["occupati"]) ? $posti["occupati"] : 0;
	$liberi = $totali - $occupati;
	return array("liberi"=>$liberi, "occupati"=>$occupati, "totali"=>$totali);
}

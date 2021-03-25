<?php

require_once "php/builder.php";
require_once "php/db_connection.php";
require_once "php/sanitizer.php";

date_default_timezone_set('Europe/Rome');

if(isset($_GET["proiezione"])){
	$proiezioneId = sanitizeNumber($_GET["proiezione"]);
}
if (!isset($proiezioneId)) {
	header("Location: schedule.php");
	exit();
}

$conn = OpenCon();

$sql = "SELECT *
	FROM programmazione
	JOIN film ON programmazione.film = film.cod
	WHERE programmazione.cod = '$proiezioneId'";

$filmresult = $conn->query($sql);
if($filmresult->num_rows == 0){
	CloseCon($conn);
	header("Location: schedule.php");
	exit();
}
$film = $filmresult->fetch_assoc();

$output = build("html/filmdetails.html");

$output = str_replace("%TitoloStrip%", strip_tags($film["titolo"]), $output);
$output = str_replace("%Titolo%", $film["titolo"], $output);
$output = str_replace("%Regista%", $film["regia"], $output);
$output = str_replace("%Anno%", $film["anno"], $output);
$output = str_replace("%Genere%", $film["genere"], $output);
$output = str_replace("%Durata%", $film["durata"], $output);
$output = str_replace("%Cast%", $film["cast"], $output);
$output = str_replace("%Trama%", $film["trama"], $output);
$output = str_replace("%Immagine%", $film["immagine"], $output);
$output = str_replace("<proiezioni/>", listaProiezioni(), $output);

CloseCon($conn);

echo $output;


function listaProiezioni() {
	global $conn;
	global $film;
	global $proiezioneId;

	$data = $film["data"];
	$filmId = $film["film"];

	$queryProiezioni = "SELECT *
		FROM programmazione
		WHERE data = '$data' AND film = '$filmId'
		ORDER BY ora";
	$proiezioni = $conn->query($queryProiezioni);
	if($proiezioni->num_rows == 0)
		return "";

	$output = "<h3 class=\"accent\">Proiezioni</h3>";
	$output.="<form action=\"booking.php\" id=\"form_details\" method=\"get\">
						<fieldset>
						<legend> Scegli un orario: </legend>";
																																
	while($proiezione = $proiezioni->fetch_assoc()){
		$time = date_format(date_create($proiezione["ora"]), "H:i");
		$output.= '<input
				type="radio"
				name="proiezione"
				value="' . $proiezione["cod"] . '"
				id="p' . $proiezione["cod"] . '"/>
			<label for="p' . $proiezione["cod"] . '">' .
				$time .	' &ndash; Sala ' . $proiezione["sala"] .
			'</label><br/>';
	}
	$output.= '<input type="submit" class="bottone" value="Prenota"/>
		</fieldset></form>';

	$unselected = "value=\"" . $proiezioneId . "\"";
	$selected = $unselected . " checked=\"checked\"";
	$output = str_replace($unselected, $selected, $output);

	return $output;
}

<?php

require_once "php/builder.php";
require_once "php/db_connection.php";
date_default_timezone_set('Europe/Rome');

session_start();
if (!isset($_SESSION["username"])){
	header("Location: login.php");
	exit();
}

$output = build("html/reservation.html");
$error = isset($_GET["error"]) ? "<p class=\"error\">" . urldecode($_GET["error"]) . "</p>" : "";

$success = "";
if (isset($_GET["success"])){
	if ($_GET["success"] == "insert") {
		$success = "<p class=\"success\">Prenotazione effettuata con successo</p>";
	}
	elseif ($_GET["success"] == "update") {
		$success = "<p class=\"success\">Prenotazione aggiornata con successo</p>";
	}
	elseif ($_GET["success"] == "delete") {
		$success = "<p class=\"success\">Prenotazione annullata con successo</p>";
	}

}
$output = str_replace("<p class=\"success\"></p>", $success, $output);
$output = str_replace("<p class=\"error\"></p>", $error, $output);
$output = str_replace("<div id=\"prenotazione\"/>", listaPrenotazioni(), $output);
echo $output;


function listaPrenotazioni(){
	$output = "";
	$conn = OpenCon();
	$utente = $_SESSION["username"];
	$sql = "SELECT *
		FROM prenotazione
		JOIN programmazione ON prenotazione.proiezione = programmazione.cod
		JOIN film ON programmazione.film = film.cod
		WHERE prenotazione.utente = '$utente'
		ORDER BY data DESC, ora DESC";
	if($result = $conn->query($sql)){
		while($prenotazione = $result->fetch_assoc()){
			$output .= "<div class=\"prenotazione\">
				<img src=\"" . $prenotazione["immagine"] . "\" alt=\"" . strip_tags($prenotazione["titolo"]) . "\"/>
				<div>
					<ul>
						<li><span class=\"accent\">Film: </span>" . $prenotazione["titolo"] . "</li>
						<li><span class=\"accent\">Durata: </span>" . $prenotazione["durata"] . " minuti</li>
						<li><span class=\"accent\">Data e ora della proiezione: </span>" . $prenotazione["data"] . ", " . date_format(date_create($prenotazione["ora"]), "H:i") . "</li>
						<li><span class=\"accent\">Sala: </span>" . $prenotazione["sala"] . "</li>
						<li><span class=\"accent\">Numero posti prenotati: </span>" . $prenotazione["posti"] . "</li>
					</ul>
				</div>
				<form action=\"php/unbook.php\" method=\"post\">
          <fieldset>
            <legend>" . $prenotazione["titolo"] . ", data " . $prenotazione["data"] . "</legend>
            <input type=\"hidden\" name=\"proiezione\" value=\"" . $prenotazione["proiezione"] . "\"/>
            <button type=\"submit\" class=\"bottone rosso right\"><span class=\"fa fa-trash\"></span>&nbsp;&nbsp;Annulla prenotazione</button>
          </fieldset>
        </form>
			</div>";
		}
	}
	if($output == ""){
		$output = "<div class=\"container\"><p>Nessuna prenotazione effettuata.</p></div>";
	}

	CloseCon($conn);

	return $output;
}

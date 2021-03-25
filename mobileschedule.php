<?php

session_start();
date_default_timezone_set('Europe/Rome');

if (!isset($_SESSION["username"])){
	header("Location: login.php");
	exit();
}
if ($_SESSION["username"] !== "admin") {
	header("Location: user.php");
	exit();
}

require_once "php/builder.php";
require_once "php/sanitizer.php";
require_once "php/db_connection.php";

$status = "";
if (isset($_GET["status"])){
	if ($_GET["status"] === "success")
		$status = "<p class=\"success\">Proiezione modificata con successo.</p>";
	if ($_GET["status"] === "noupdate")
		$status = "<p class=\"success\">La proiezione non è stata modificata.</p>";
	if ($_GET["status"] === "error")
		$status = "<p class=\"error\">Errore nell'aggiornamento dei dati.</p>";
}

$output = build("html/mobileschedule.html");
$output = str_replace("<lista_proiezioni/>", listaProiezioni(), $output);
$output = str_replace("<status/>", $status, $output);

echo $output;


function listaProiezioni(){
	$conn = OpenCon();
	$date = date('Y-m-d');
	$time = date('H:i:s');

	$sql = "SELECT *, programmazione.cod AS id
		FROM programmazione
		JOIN film ON programmazione.film = film.cod
		WHERE data = '$date'
		ORDER BY ora";

	$proiezioni = $conn->query($sql);

	if($proiezioni->num_rows == 0){
		CloseCon($conn);
		return "Nessuna proiezione";
	}

	$output = "<table class=\"proie_table\" summary=\"Proiezioni in programma\">
		<caption class=\"nodisplay\">Proiezioni del giorno</caption>
		<thead>
			<tr>
				<th>Titolo</th>
				<th>Ora</th>
				<th>Azioni</th>
			</tr>
		</thead>
		<tbody>";

	while($proiezione = $proiezioni->fetch_assoc()){
		$output .= "<tr><td>" . $proiezione["titolo"] . "</td>
			<td>" . date_format(date_create($proiezione["ora"]), "H:i") . "</td>
			<td><a href=\"editprojection.php?proiezione=" . $proiezione["id"] . "\">Modifica</a></td></tr>";
	}

	$output .= "</tbody>
		</table>";

	CloseCon($conn);
	return $output;
}
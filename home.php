<?php

require_once "php/builder.php";
require_once "php/db_connection.php";

$output = build("html/home.html");
$output = str_replace("<locandina/>", filmDiOggi(), $output);

echo $output;


Function filmDiOggi(){
	date_default_timezone_set('Europe/Rome');
	$date = date('Y/m/d');
	$conn = OpenCon();
	$output = "";

	$queryFilm = "SELECT programmazione.cod AS proiezione, titolo, immagine
		FROM film
		JOIN programmazione ON programmazione.film = film.cod
		WHERE programmazione.data = '$date'
		GROUP BY programmazione.film
		LIMIT 3";

	$filmProiettati = $conn->query($queryFilm);

	if($filmProiettati->num_rows == 0)
		return "Nessuna proiezione";

	while($film = $filmProiettati->fetch_assoc()){
		$output .= "<div class=\"locandina\">";
		$output .= "<div class=\"image\">
				<a href=\"filmdetails.php?proiezione=" . $film["proiezione"] . "\">
					<img src=\"" . $film["immagine"] . "\" alt=\"" . strip_tags($film["titolo"]) . "\"/>
				</a>
				<a href=\"filmdetails.php?proiezione=" . $film["proiezione"] . "\" class=\"button_hover\">PRENOTA</a>
			</div>";
		$output .= "<h3><a href=\"filmdetails.php?proiezione=" . $film["proiezione"] . "\" class=\"title\">" . $film["titolo"] . "</a></h3>";
		$output .= "</div>";
	}

	CloseCon($conn);

	return $output;
}

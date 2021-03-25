<?php

require_once "php/builder.php";
require_once "php/sanitizer.php";
require_once "php/db_connection.php";

date_default_timezone_set('Europe/Rome');
$day = isset($_GET["day"]) ? sanitizeNumber($_GET["day"]) : 0;

$output = build("html/schedule.html");
$output = str_replace("<tabs/>", dayTabs(),$output);
$output = str_replace("<locandina/>", filmDelGiorno(), $output);

echo $output;


function filmDelGiorno(){
	global $day;
	$selectedDate = date('Y/m/d', strtotime("+$day days"));
	$conn = OpenCon();
	$output = "";

	$queryFilm = "SELECT programmazione.cod AS proiezione, titolo, immagine
		FROM film
		JOIN programmazione ON programmazione.film = film.cod
		WHERE programmazione.data = '$selectedDate'
		GROUP BY programmazione.film";

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


function dayTabs(){
	global $day;

	$settimana= array(
		"Domenica",
		"Luned&igrave;",
		"Marted&igrave;",
		"Mercoled&igrave;",
		"Gioved&igrave;",
		"Venerd&igrave;",
		"Sabato"
	);
	$output = "<h2>Film in programma " . ($day == 0 ? "oggi" : strtolower($settimana[(date("w") + $day) % 7])) . "</h2>";
	$output .= '<ul class="tabs">';

	$daySum = date_create(date('Y/m/d'));
	for($i = 0; $i < 7; $i = $i + 1){
		$output .= "<li>";
		if($i == $day){
			$output .= "<a class=\"active\">";
		}
		else{
			$output .= "<a href=\"schedule.php?day=$i\">";
		}
		if($i == 0){
			$output .= "Oggi";
		}
		else{
			$output .= $settimana[date_format($daySum,'w')];
		}
		$output .= "</a></li>";

		date_add($daySum, date_interval_create_from_date_string( "1 days"));
	}
	$output.='</ul>';
	return $output;
}

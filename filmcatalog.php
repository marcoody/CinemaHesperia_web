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
$imgerr = "";

if (isset($_GET["img"])){
	if ($_GET["img"] == 1)
		$imgerr = "<p class=\"error\">Nessuna immagine caricata</p>";
	if ($_GET["img"] == 2)
			$imgerr = "<p class=\"error\">L'immagine non è stata caricata perchè troppo pesante</p>";
			if ($_GET["img"] == 0)
					$imgerr = "<p class=\"success\">Immagine modificata con successo</p>";
}

if (isset($_GET["status"])){
	if ($_GET["status"] === "success" || $_GET["img"] == 0)
		$status = "<p class=\"success\">Film modificato con successo.</p>";
		if ($_GET["status"] === "add_success")
			$status = "<p class=\"success\">Film aggiunto con successo.</p>";
	if ($_GET["status"] === "noupdate" && $_GET["img"] != 0 )
		$status = "<p class=\"success\">Il film non è stato modificato.</p>";
	if ($_GET["status"] === "error")
		$status = "<p class=\"error\">Errore nell'aggiornamento dei dati.</p>";
}

$output = build("html/filmcatalog.html");
$output = str_replace("<lista_film/>", listaFilm(), $output);
$output = str_replace("<status/>", $status, $output);
$output = str_replace("<img_err/>", $imgerr, $output);

echo $output;


function listaFilm(){
	$conn = OpenCon();
	$output = "";

	$sql = "SELECT *
		FROM film";


	$films = $conn->query($sql);

	if($films->num_rows == 0){
		CloseCon($conn);
		return "Nessun film";
	}

	while($film_list = $films->fetch_assoc()){
		$output .= "<div class=\"locandina\">";
		$output .= "<div class=\"image\">
				<a href=\"editfilm.php?cod=" . $film_list["cod"] . "\">
					<img src=\"" . $film_list["immagine"] . "\" alt=\"" . strip_tags($film_list["titolo"]) . "\"/>
				</a>
			</div>";
		$output .= "<h3><a href=\"editfilm.php\" class=\"title\">" . $film_list["titolo"] . "</a></h3>";
		$output .= "</div>";
	}



	CloseCon($conn);
	return $output;
}

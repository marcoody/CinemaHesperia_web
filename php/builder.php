<?php

// accesso diretto al file builder vietato
if(strpos($_SERVER["PHP_SELF"], "php/builder.php"))
	header("Location: ../home.php");

// dato il percorso di un file html con tag predisposti per la sostituzione, questa funzione provvederà ad inserirvi all'interno header, footer e tag meta
function build($html_location) {
	if (!isset($_SESSION)) {
		session_start();
	}
	$output = file_get_contents($html_location);
	$header = file_get_contents("html/header.html");
	$footer = file_get_contents("html/footer.html");
	$includes = file_get_contents("html/metainclude.html");
	$header = str_replace(basename($_SERVER["PHP_SELF"]), "#\" class=\"active", $header);

	$output = str_replace("<div id=\"header\"/>", $header, $output);
	$output = str_replace("<div id=\"footer\"/>", $footer, $output);
	$output = str_replace("<meta/>", $includes, $output);

	if (isset($_SESSION["username"])) {
		if ($_SESSION["username"] === "admin") {
			$output = str_replace("<a id=\"login\"/>", "<a href=\"php/logout.php\">Logout</a></li><li class=\"right\"><a href=\"admin.php\">Amministrazione</a>", $output);
		}
		else {
			$output = str_replace("<a id=\"login\"/>", "<a href=\"php/logout.php\">Logout</a></li><li class=\"right\"><a href=\"user.php\">Area Personale</a>", $output);
		}
	}
	else {
		$output = str_replace("<a id=\"login\"/>", "<a href=\"login.php\">Log in</a>", $output);
	}

	// rimozione link circolari
	$output = str_replace(basename($_SERVER["PHP_SELF"]), "#", $output);

	return $output;
}

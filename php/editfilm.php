<?php

session_start();
date_default_timezone_set('Europe/Rome');

if (!isset($_SESSION["username"]))
	abort("login.php");
if ($_SESSION["username"] !== "admin")
	abort("user.php");

require_once "sanitizer.php";
require "upload_image.php";


require_once "db_connection.php";
if (isset($_POST["codfilm"]))
	$cod = sanitizeNumber($_POST["codfilm"]);
	if (isset($_POST["titolo"]))
	  $titolo = sanitizeString($_POST["titolo"]);
if (isset($_POST["genere"]))
		  $genere = sanitizeString($_POST["genere"]);
			if (isset($_POST["durata"]))
				$durata = sanitizeNumber($_POST["durata"]);
				if (isset($_POST["anno"]))
					$anno = sanitizeNumber($_POST["anno"]);

			if (isset($_POST["regia"]))
					  $regia = sanitizeString($_POST["regia"]);
						if (isset($_POST["cast"]))
								  $cast = sanitizeString($_POST["cast"]);
									if (isset($_POST["trama"]))
											  $trama = sanitizeString($_POST["trama"]);


$conn = OpenCon();

$sql_image = "UPDATE film
SET immagine = 'images/covers/$name_file'
WHERE cod = '$cod'";

if($name_file && $uploadOk == 0) {
	$result = $conn->query($sql_image);
}


$insert = $conn->prepare("UPDATE film
 SET titolo = ?,
 genere = ?,
 durata = ?,
 anno = ?,
 regia = ?,
 cast = ?,
 trama = ?
 WHERE cod = ?");
$insert->bind_param("ssssssss", $titolo, $genere, $durata, $anno, $regia, $cast, $trama, $cod);
$insert->execute();



/*if ($insert->affected_rows ==1)
	abort("../filmcatalog.php?status=success");
 else
 	abort("../filmcatalog.php?status=noupdate");*/

	if ($insert->affected_rows ==1)
		abort("../filmcatalog.php?status=success&img=".$uploadOk."");
	 else{
	 	abort("../filmcatalog.php?status=noupdate&img=".$uploadOk."");
	}

function abort($url){
	global $conn;
	header("Location: " . $url);
	if (isset($conn)) {
		CloseCon($conn);
	}
	exit();
}

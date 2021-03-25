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

if (isset($_POST["titolo"]))
	$titolo = validateHtml($_POST["titolo"]);
if (isset($_POST["genere"]))
	$genere = validateHtml($_POST["genere"]);
if (isset($_POST["durata"]))
	$durata = sanitizeNumber($_POST["durata"]);
if (isset($_POST["anno"]))
	$anno = sanitizeNumber($_POST["anno"]);
if (isset($_POST["regia"]))
	$regia = validateHtml($_POST["regia"]);
if (isset($_POST["cast"]))
	$cast = validateHtml($_POST["cast"]);
if (isset($_POST["trama"]))
	$trama = validateHtml($_POST["trama"]);

$conn = OpenCon();

if($uploadOk == 0){
	$img ="images/covers/$name_file";
}
else{
	$img ="images/covers/no_loca.jpg";
}

$insert = $conn->prepare("INSERT INTO film (titolo, genere, durata, anno, regia, cast, trama, immagine ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$insert->bind_param("ssssssss", $titolo, $genere, $durata, $anno, $regia, $cast, $trama, $img);
$insert->execute();


if ($insert->affected_rows ==1)
	abort("../filmcatalog.php?status=add_success&img=".$uploadOk."");
else
	abort("../filmcatalog.php?status=noupdate&img=".$uploadOk."");

$insert->close();


function abort($url){
	global $conn;
	header("Location: " . $url);
	if (isset($conn)) {
		CloseCon($conn);
	}
	exit();
}

<?php

session_start();
if (!isset($_SESSION["username"]))
	abort("login.php");
if ($_SESSION["username"] !== "admin")
	abort("user.php");

require_once "sanitizer.php";
if (isset($_POST["proiezione"]))
	$proiezione = sanitizeNumber($_POST["proiezione"]);
if (isset($_POST["film"]))
	$film = sanitizeNumber($_POST["film"]);
if (isset($_POST["data"])){
	$fdata = filter_var($_POST["data"], FILTER_VALIDATE_REGEXP,
		array("options" => array("regexp"=>"/^(0[1-9]|[1-2][0-9]|3[0-1])(-|\/)(0[1-9]|1[0-2])(-|\/)[0-9]{4}$/")));
	$fdata = str_replace("/", "-", $fdata);
	if($fdata !== FALSE)
		$data = date("Y-m-d", strtotime($fdata));
}
if (isset($_POST["ora"]))
	$ora = filter_var($_POST["ora"], FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/")));
if (isset($_POST["sala"]))
	$sala = sanitizeString($_POST["sala"]);

if (!isset($proiezione) || !isset($film) || !isset($data) || !isset($ora) || !isset($sala))
	abort("../upcomingschedule.php?status=error");

require_once "db_connection.php";
date_default_timezone_set('Europe/Rome');

$conn = OpenCon();
$insert = $conn->prepare("INSERT INTO  programmazione
(film,data,ora,sala) VALUES (?, ?, ?, ?)");
$insert->bind_param("ssss", $film, $data, $ora, $sala);
$insert->execute();

if ($insert->affected_rows ==1)
	abort("../upcomingschedule.php?status=add_success");
else
	abort("../upcomingschedule.php?status=noupdate");
	$insert->close();

function abort($url){
	global $conn;
	header("Location: " . $url);
	if (isset($conn)) {
		CloseCon($conn);
	}
	exit();
}

<?php

session_start();
if (!isset($_SESSION["username"]))
	abort("login.php");
if ($_SESSION["username"] !== "admin")
	abort("user.php");

require_once "sanitizer.php";
if (isset($_GET["proiezione"]))
	$idProiezione = sanitizeNumber($_GET["proiezione"]);

require_once "db_connection.php";
date_default_timezone_set('Europe/Rome');

$conn = OpenCon();

$sql = "DELETE  FROM programmazione
WHERE cod= '$idProiezione'";
$result = $conn->query($sql);

if ($conn->affected_rows === 1)
	abort("../upcomingschedule.php?status=success");
else
	abort("../upcomingschedule.php?status=noupdate");


function abort($url){
	global $conn;
	header("Location: " . $url);
	if (isset($conn)) {
		CloseCon($conn);
	}
	exit();
}

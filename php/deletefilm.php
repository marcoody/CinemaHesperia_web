<?php

session_start();
if (!isset($_SESSION["username"]))
	abort("login.php");
if ($_SESSION["username"] !== "admin")
	abort("user.php");

require_once "sanitizer.php";
if (isset($_GET["film"]))
	$idFilm = sanitizeNumber($_GET["film"]);

require_once "db_connection.php";
date_default_timezone_set('Europe/Rome');

$conn = OpenCon();

$sql = "DELETE  FROM film
WHERE cod= '$idFilm'";
$result = $conn->query($sql);

if ($conn->affected_rows === 1)
abort("../filmcatalog.php?status=success");
else
abort("../filmcatalog.php?status=noupdate");


function abort($url){
	global $conn;
	header("Location: " . $url);
	if (isset($conn)) {
		CloseCon($conn);
	}
	exit();
}

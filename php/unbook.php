<?php

require_once "db_connection.php";
require_once "sanitizer.php";

if(isset($_POST["proiezione"])){
	$proiezione = sanitizeNumber($_POST["proiezione"]);
}
if(!isset($proiezione)){
	header("Location: ../reservation.php");
	exit();
}
session_start();
if(!isset($_SESSION["username"])){
	header("Location: login.php");
	exit();
}
$username = $_SESSION["username"];

$conn = OpenCon();

$sql = "DELETE
		FROM prenotazione
		WHERE proiezione=$proiezione AND utente='$username'";

if($conn->query($sql) && $conn->affected_rows == 1){
	header("Location: ../reservation.php?success=delete");
}
else{
	header("Location: ../reservation.php?error=" . urlencode("Annullamento della prenotazione fallito"));
}
CloseCon($conn);
exit();

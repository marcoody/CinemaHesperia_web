<?php

require_once "db_connection.php";
require_once "sanitizer.php";

if(isset($_POST["posti"])){
	$posti = sanitizeNumber($_POST["posti"]);
}
if(isset($_POST["proiezione"])){
	$proiezione = sanitizeNumber($_POST["proiezione"]);
}
if(!isset($posti) || !isset($proiezione)){
	header("Location: ../schedule.php");
	exit();
}
session_start();
if(!isset($_SESSION["username"])){
	header("Location: ../login.php");
	exit();
}
$username = $_SESSION["username"];

$conn = OpenCon();

$sql = "SELECT sala.posti - sum(prenotazione.posti)
		FROM prenotazione
		JOIN programmazione ON prenotazione.proiezione = programmazione.cod
		JOIN sala on programmazione.sala = sala.nome
		WHERE proiezione=$proiezione";
$postiliberi = $conn->query($sql)->fetch_assoc();
if($posti > $postiliberi){
	CloseCon($conn);
	header("Location: ../booking.php?proiezione=$proiezione");
	exit();
}

$sql = "SELECT * FROM prenotazione WHERE proiezione='$proiezione' AND utente='$username'";
$result = $conn->query($sql);

if($result->num_rows > 0){
	$prenotazioneprecente = $result->fetch_assoc();
	$posti = $posti + $prenotazioneprecente["posti"];
	$sql = "UPDATE prenotazione
		SET posti=$posti
		WHERE proiezione=$proiezione AND utente='$username'";
	if(mysqli_query($conn, $sql)){
		header("Location: ../reservation.php?success=update");
	}
	else{
		header("Location: ../reservation.php?error=" . urlencode("Prenotazione fallita"));
	}
}
else{
	$sql= "INSERT INTO prenotazione (proiezione, utente, posti) VALUES ('$proiezione','$username', $posti)";

	if(mysqli_query($conn, $sql)){
		header("Location: ../reservation.php?success=insert");
	}
	else{
		header("Location: ../reservation.php?error=" . urlencode("Prenotazione fallita"));
	}
}
CloseCon($conn);
exit();

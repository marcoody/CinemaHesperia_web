<?php

require_once "db_connection.php";
require_once "sanitizer.php";

session_start();
if (!isset($_SESSION["username"])){
	header("Location: login.php");
	exit();
}

$username = $_SESSION["username"];
$oldpassword = sanitizeString($_POST["old-password"]);
$newpassword = sanitizeString($_POST["new-password"]);
$repeatpassword = sanitizeString($_POST["psw-repeat"]);

if (strlen($newpassword) < 8) {
	header("Location: ../changepassword.php?error=" . urlencode("La nuova password deve avere almeno 8 caratteri"));
	exit();
}
if ($newpassword !== $repeatpassword) {
	header("Location: ../changepassword.php?error=" . urlencode("Le password non combaciano"));
	exit();
}

$connessione = OpenCon();

$stmt = $connessione->prepare("SELECT password FROM utente WHERE username=?");
if ($stmt &&
	$stmt->bind_param("s", $username) &&
	$stmt->execute() &&
	$stmt->store_result() &&
	$stmt->bind_result($storedPassword)) {
	$stmt->fetch();
	$stmt->close();
} else {
	CloseCon($connessione);
	header("Location: ../changepassword.php?error=" . urlencode("Errore nell'accesso al database"));
	exit();
}

if(password_verify($oldpassword, $storedPassword)){
	$password = password_hash($newpassword, PASSWORD_DEFAULT);
	$insert = $connessione->prepare("UPDATE utente SET password = ? WHERE username = ?");
	$insert->bind_param("ss", $password, $username);
	$insert->execute();
	header("Location: ../user.php");
}
else{
	header("Location: ../changepassword.php?error=" . urlencode("Password errata"));
}

CloseCon($connessione);
exit();

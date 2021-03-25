<?php 

require_once "db_connection.php";
require_once "sanitizer.php";

if (!isset($_SESSION)) {
	session_start();
}

$username = strtolower(sanitizeString($_POST["username"]));
$password = sanitizeString($_POST["password"]);
if (isset($username) && isset($password)) {
	$connessione = OpenCon();

	$stmt = $connessione->prepare("SELECT password FROM utente WHERE username=? OR email=?");
	if ($stmt &&
		$stmt->bind_param("ss", $username, $username) &&
		$stmt->execute() &&
		$stmt->store_result() &&
		$stmt->bind_result($storedPassword)) {
		$stmt->fetch();
		$stmt->close();
	} else {
		CloseCon($connessione);
		header("Location: ../login.php?error=" . urlencode("Errore nell'accesso al database"));
		exit();
	}
	CloseCon($connessione);

	if (password_verify($password, $storedPassword)) {
		$_SESSION["username"] = $username;
		header("Location: ../user.php");
		exit();
	}
}
header("Location: ../login.php?error=" . urlencode("Credenziali non valide") . "&user=" . urlencode($username));
exit();

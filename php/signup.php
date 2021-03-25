<?php

require_once "sanitizer.php";
require_once "db_connection.php";

if (!isset($_SESSION)) {
	session_start();
}

$user["nome"] = sanitizeString($_POST["nome"]);
$user["cognome"] = sanitizeString($_POST["cognome"]);
$user["username"] = strtolower(sanitizeString($_POST["username"]));
$user["email"] = sanitizeEmail($_POST["email"]);
$user["password"] = $_POST["password"];
$user["psw-repeat"] = $_POST["psw-repeat"];

// controllo che i campi una volta sanitizzati non siano vuoti
if (!$user["nome"] ||
	!$user["email"] ||
	!$user["cognome"] ||
	!$user["username"]) {
	redirect("Compila tutti i campi!");
}

// controllo password
if (isset($_POST["password"])&& isset($_POST["psw-repeat"])) {
	$password = $user["password"];
	if (strlen($password) >= 8 && $password == $user["psw-repeat"]) {
		$user["password"] = password_hash($password, PASSWORD_DEFAULT);
	}
	else {
		redirect("Inserisci una password di almeno 8 caratteri!");
	}
}

$connessione = OpenCon();
// controllo che l'utente non sia già registrato
$userCheck = $connessione->prepare("SELECT * FROM utente WHERE username = ? OR email = ?");
$userCheck->bind_param("ss", $user["username"], $user["email"]);
$userCheck->execute();
$userCheck->store_result();
$userCount = $userCheck->num_rows;
$userCheck->close();
if ($userCount > 0) {
	$user["username"] = "";
	redirect("Utente già registrato.");
}


// inserisco l'utente nel database
$insert = $connessione->prepare("INSERT INTO utente (username, nome, cognome, email, password) VALUES (?, ?, ?, ?, ?)");
$insert->bind_param("sssss", $user["username"], $user["nome"], $user["cognome"], $user["email"], $user["password"]);
$insert->execute();
if($insert->affected_rows > 0) {
	$insert->close();
	CloseCon($connessione);
	$_SESSION["username"] = $user["username"];
	header("Location: ../home.php");
	exit();
} else {
	$insert->close();
	redirect("Errore nella registrazione dei dati.");
}

// funzione per ritornare alla pagina di registrazione allegando i campi già inseriti tramite GET
function redirect($err = "")
{
	global $user;
	global $connessione;
	if(isset($connessione)){
		CloseCon($connessione);
	}

	$lista_campi = array("nome", "cognome", "username", "email");
	$parametriGet = "";
	foreach ($lista_campi as $campo) {
		if (isset($user[$campo])) {
			$parametriGet .= $campo . "=" . urlencode($user[$campo]) . "&";
		}
	}
	$parametriGet .= "err=" . urlencode($err);

	header("Location: ../signup.php?" . $parametriGet);
	exit();
}

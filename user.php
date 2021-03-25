<?php

require_once "php/builder.php";
require_once "php/db_connection.php";

session_start();
if (!isset($_SESSION["username"])){
	header("Location: login.php");
	exit();
}
if ($_SESSION["username"] === "admin") {
	header("Location: admin.php");
	exit();
}

$output = build("html/user.html");
$output = str_replace("<div id=\"utente\"/>", datiUtente(), $output);

echo $output;

function datiUtente(){
	$output = "";
	$conn = OpenCon();
	$utente = $_SESSION["username"];
	$sql = "SELECT *
		FROM utente
		WHERE username = '$utente'";
	$result = $conn->query($sql);
	$user = $result->fetch_assoc();

	$output .= "<div class=\"utente\">
			<ul>
				<li>
					<span xml:lang=\"en\" class=\"accent\">Username: </span>" .
					$user["username"] .
				"</li>
				<li>
					<span class=\"accent\">Nome e cognome: </span>" .
					$user["nome"] . " " . $user["cognome"] .
				"</li>
				<li>
					<span xml:lang=\"en\" class=\"accent\">Email: </span>" .
					$user["email"] .
				"</li>
			</ul>
		</div>";

	CloseCon($conn);
	return $output;
}

<?php

if(strpos($_SERVER["PHP_SELF"], "php/db_connection.php"))
	header("Location: ../home.php");

function OpenCon() {
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = null;
	$db = "cinema";
	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn->error);
	return $conn;
}

function CloseCon($conn) {
	$conn->close();
}

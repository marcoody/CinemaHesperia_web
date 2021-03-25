<?php

session_start();
if (!isset($_SESSION["username"])){
	header("Location: login.php");
	exit();
}
if ($_SESSION["username"] !== "admin") {
	header("Location: user.php");
	exit();
}

require_once "php/builder.php";
require_once "php/db_connection.php";

$output = build("html/admin.html");

echo $output;

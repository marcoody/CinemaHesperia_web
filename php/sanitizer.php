<?php

if(strpos($_SERVER["PHP_SELF"], "php/sanitizer.php"))
	header("Location: ../home.php");

function sanitizeNumber ($n) {
	$temp = filter_var($n, FILTER_SANITIZE_NUMBER_INT);
	return $temp > 0 ? $temp : 0;
}

function sanitizeEmail ($e) {
	return filter_var($e, FILTER_SANITIZE_EMAIL);
}

function sanitizeString ($s) {
	return filter_var(trim($s), FILTER_SANITIZE_STRING);
}

function validateNumber ($n) {
	return filter_var($n, FILTER_VALIDATE_INT);
}

function validateEmail ($e) {
	return filter_var($e, FILTER_VALIDATE_EMAIL);
}

function validateHtml ($h) {
	return filter_var($h, FILTER_VALIDATE_REGEXP,
		array("options" => array("regexp"=>"/^[\da-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð :;,.'\"--()\/<>=]+$/")));
}

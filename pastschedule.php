<?php

session_start();
date_default_timezone_set('Europe/Rome');

if (!isset($_SESSION["username"])){
	header("Location: login.php");
	exit();
}
if ($_SESSION["username"] !== "admin") {
	header("Location: user.php");
	exit();
}

require_once "php/builder.php";
require_once "php/sanitizer.php";
require_once "php/db_connection.php";
if (isset($_GET["page"])){
	$page = $_GET["page"];
}
else {
	$page = 1;
}
$limit = 10;
$output = build("html/pastschedule.html");
$output = str_replace("<lista_proiezioni/>", listaProiezioni(), $output);
$output = str_replace("<paginazione/>", paginazione(), $output);
echo $output;


function listaProiezioni() {
	global $page;
	global $limit;
	$conn = OpenCon();
	$date = date('Y/m/d');
	$time = date('H:i:s');
	$now_page = ($page-1) * $limit;
	$sql = "SELECT *, SUM(posti) AS prenotazioni
		FROM programmazione
		JOIN film ON programmazione.film = film.cod
		LEFT JOIN prenotazione on programmazione.cod = prenotazione.proiezione
		WHERE data < '$date' OR (data = '$date' AND ora < '$time')
		GROUP BY programmazione.cod
		ORDER BY data DESC,ora DESC
			LIMIT $limit OFFSET $now_page";

	$proiezioni = $conn->query($sql);

	if($proiezioni->num_rows == 0){
		CloseCon($conn);
		return "Nessuna proiezione";
	}

	$output = "<table class=\"proie_table\" summary=\"proiezioni passate\">
		<caption class=\"nodisplay\">Proiezioni già effettuate</caption>
		<thead>
			<tr>
				<th>Titolo</th>
				<th>Data</th>
				<th>Ora</th>
				<th>Sala</th>
				<th>Prenotazioni</th>
				<th>Azioni</th>
			</tr>
		</thead>
		<tbody>";

	while($proiezione = $proiezioni->fetch_assoc()){
		$output .= "<tr><td>" . $proiezione["titolo"] . "</td>
			<td>" . $proiezione["data"] . "</td>
			<td>" . $proiezione["ora"] . "</td>
			<td>" . $proiezione["sala"] . "</td>
			<td>" . $proiezione["prenotazioni"] . "</td>
			<td ><a class=\"delete_butt\" href=\"php/deleteprojection.php?proiezione=" . $proiezione["cod"] . "\">Elimina</a></td></tr>";
	}

	$output .= "</tbody>
		</table>";

	CloseCon($conn);
	return $output;
}

function paginazione(){
	global $page;
	global $limit;
	$conn = OpenCon();
	$date = date('Y-m-d');
	$time = date('H:i:s');
	$sqltry = mysqli_query(
	$conn, "SELECT COUNT(*) As n_proiezioni
		FROM programmazione
		JOIN film ON programmazione.film = film.cod
		LEFT JOIN prenotazione on programmazione.cod = prenotazione.proiezione
		WHERE data < '$date' OR (data = '$date' AND ora < '$time')");

	$n_proiezioni = mysqli_fetch_array($sqltry);
	$n_proiezioni = $n_proiezioni['n_proiezioni'];
	if($n_proiezioni % $limit > 0){
		$n_proiezioni = floor($n_proiezioni / $limit)+1;
	}
	else {
		$n_proiezioni = floor($n_proiezioni / $limit);
	}
	$previous_page= floor($page - 1);
	$next_page= floor($page + 1);
	$output = "<ul class=\"paginazione\">";

	if($page <= 1){
		$output.= "<li class='disabled'></li>";
	}

	if($page > 1){
		$output.= "<li><a href='?page=" . $previous_page. "'>Precedente</a></li>";
	}

	if($page >= $n_proiezioni){
		$output.= "<li class='disabled'></li>";
	}

	if ($n_proiezioni <= 6){
		for ($counter = 1; $counter <= $n_proiezioni-1; $counter++){
			if ($counter == $page) {
				$output.= "<li ><a class='active'>". $counter . "</a></li>";
			}
			else{
				$output.= "<li><a href='?page= " . $counter ."'>" . $counter . "</a></li>";
			}
		}
	}
	else{
		for ($counter = 1; $counter <= $n_proiezioni-1; $counter++){
			if($counter == $page-3 &&  $counter >= 1){
				$output.= "<li><a href='?page=1'>1</a></li>";
				if($counter!=1){
					$output.= "<li><a>...</a></li>";
				}
			}
			if( $counter >= $page-2 & $counter <= $page+2 ){
				if ($counter == $page) {
					$output.= "<li ><a class='active'>". $counter . "</a></li>";
				}
				else{
					$output.= "<li><a href='?page= " . $counter ."'>" . $counter . "</a></li>";
				}
			}
			if($counter == $page+3){
				$output.= "<li><a>...</a></li>";
			}
		}
	}
	if($page <= $n_proiezioni ){
		if ( $n_proiezioni == $page) {
			$output.= "<li ><a class='active'>". $n_proiezioni . "</a></li>";
		}
		else{
			$output.= "<li><a href='?page=" . $n_proiezioni . "'>". $n_proiezioni ."</a></li>";
		}
	}
	if($page < $n_proiezioni) {
		$output.= "<li><a href='?page=". $next_page . "'>Successiva</a></li>";
	}
	$output.="</ul>";

	CloseCon($conn);
	return $output;
}

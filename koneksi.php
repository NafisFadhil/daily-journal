<?php 

date_default_timezone_set('Asia/Jakarta');

$servername = "localhost";
$username = "root";
$password = "root";
$db = "webdailyjournal";

$conn = new mysqli($servername, $username, $password, $db);

if($conn->connect_error) {
	die("connection failled : " . $conn->connect_error);
}

// echo "Connection successfully </hr>";

?>

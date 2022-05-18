<?php include "./environment.php"; ?>

<?php

if(isset($_COOKIE['login'])) {

	list($email, $cookieHash) = split(',', $_COOKIE['login']);

	if(hash('sha256', $email . $_ENV['secret']) != $cookieHash) {
		header("Location: ./index.php");
	}
}

if(isset($_POST['submitRegister'])) {

	if(empty($_POST['emailRegister']) || empty($_POST['passwordRegister']) || empty($_POST['confirmPasswordRegister'])) {
		die("[LOGIN] No Register Info");
	}

	$mysqli = new mysqli($_ENV['servername'], $_ENV['username'], $_ENV['password'], $_ENV['database']);

	if($mysqli->connect_error) {
		die("[DB] Connection Failed" . " " . $mysqli->connect_error);
	}

	$email = $mysqli->real_escape_string($_POST['emailRegister']);
	if($_POST['passwordRegister'] != $_POST['confirmPasswordRegister']) {
		die("Passwords Do Not Match");
	}

	$password = hash('sha256', $_POST['passwordRegister']);

	$query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
	$result = $mysqli->query($query);

	if($result == null) {
		die("[DB] Register Failed");
	}

	setcookie('login', $email . ',' .  hash('sha256', $email) . $_ENV['secret'], time() + 3600);

	header("Location: ./index.php");
}

?>

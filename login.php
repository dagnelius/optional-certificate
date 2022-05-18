<?php include "./environment.php"; ?>

<?php

if(isset($_COOKIE['login'])) {

	list($email, $cookieHash) = split(',', $_COOKIE['login']);
	
	if(hash('sha256', $email . $_ENV['secret']) != $cookieHash) {
		header("Location: ./index.php");
	}
}

if(isset($_POST['submitLogin'])) {

	if(empty($_POST['emailLogin']) || empty($_POST['passwordLogin'])) {
		die("[LOGIN] No Login Info");
	}
	
	$mysqli = new mysqli($_ENV['servername'], $_ENV['username'], $_ENV['password'], $_ENV['database']);
	
	if($mysqli->connect_error) {
		die("[DB] Connection Failed" . " " . $mysqli->connect_error); 
	}
	
	$email = $mysqli->real_escape_string($_POST['emailLogin']);
	$password = $_POST['passwordLogin'];

	$query = "SELECT * FROM users WHERE email = '$email'";
	$result = $mysqli->query($query);
	

	var_dump($result);
	var_dump(!$result);

	if($result->num_rows == null) {
		die("[LOGIN] Incorrect Email or Password");
	}

	while($row = $result->fetch_assoc()) {
		if(hash('sha256', $password) != $row['password']) {
			die("[LOGIN] Incorrect Email or Password");
		}
	}

	setcookie('login', $email . ',' .  hash('sha256', $email) . $_ENV['secret'], time() + 3600);

	header("Location: ./index.php");
}

?>

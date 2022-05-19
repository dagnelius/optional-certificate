<?php include "./environment.php"; ?>

<?php
if(isset($_COOKIE['login'])) {

	list($email, $cookieHash) = split(',', $_COOKIE['login']);

	if(hash('sha256', $email . $_ENV['secret']) == $cookieHash) {
		header("Location: ./index.php");
	}
}

if($_SERVER['VERIFIED'] == "SUCCESS") {
	$certificate = $_SERVER['DN'];
	$certificateArray = explode(',', $certificate);
	$emailAddress = $certificateArray[0];
	$email = explode('=', $emailAddress);
	$email = $email[1];

	$mysqli = new mysqli($_ENV['servername'], $_ENV['username'], $_ENV['password'], $_ENV['database']);

	if($mysqli->connect_error) {
		die('[DB] Connection Failed' . " " . $mysqli->connect_error);
	}

	$query = "SELECT * FROM users WHERE email = '$email'";
	$result = $mysqli->query($query);

	if($result->num_rows == null) {
		die('[LOGIN] Incorrect Email or Password');
	}

	setcookie('login', $email . ',' . hash('sha256', $email . $_ENV['secret']), time() + 3600);

	header('Location: ./index.html');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Register</title>

    <script defer src="./script.js"></script>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <section>
	<code>Login:</code><br>
	<form action="./login.php" method="POST">
            <input type="email" placeholder="email" name="emailLogin">
            <input type="password" placeholder="password" name="passwordLogin">
            <input type="submit" name="submitLogin">
        </form>
    </section>
    <section>
	<code>Register:</code><br>
        <form action="./register.php" method="POST">
            <input type="email" placeholder="email" name="emailRegister">
            <input type="password" placeholder="password" name="passwordRegister">
            <input type="password" placeholder="confirm password" name="confirmPasswordRegister">
            <input type="submit" name="submitRegister">
        </form>
    </section>
</body>
</html>

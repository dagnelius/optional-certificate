<?php
if(isset($_COOKIE['login'])) {

	list($email, $cookieHash) = split(',', $_COOKIE['login']);

	if(hash('sha256', $email . $_ENV['secret']) != $cookieHash) {
		header("Location: ./index.php");
	}
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
	<form action="./login.php" method="POST">
            <input type="email" placeholder="email" name="emailLogin">
            <input type="password" placeholder="password" name="passwordLogin">
            <input type="submit" name="submitLogin">
        </form>
    </section>
    <section>
        <form action="./register.php" method="POST">
            <input type="email" placeholder="email" name="emailRegister">
            <input type="password" placeholder="password" name="passwordRegister">
            <input type="password" placeholder="confirm password" name="confirmPasswordRegister">
            <input type="submit" name="submitRegister">
        </form>
    </section>
</body>
</html>

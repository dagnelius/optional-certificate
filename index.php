<?php include "./environment.php"; ?>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!$_COOKIE['login']) {
	header("Location: ./gateway.php");
}

if(isset($_COOKIE['login'])) {

	$array = explode(',', $_COOKIE['login']);

        if(hash('sha256', $array[0] . $_ENV['secret']) != $array[1]) {
                header("Location: ./gateway.php");
        }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <section>
    </section>
    <section>
	<code>Sertifikats: </code>
	<form action='./certificates.php' method="POST">
		<input type="submit" name="certificateButton" value="Free Certificate">
    </section>
</body>
</html>

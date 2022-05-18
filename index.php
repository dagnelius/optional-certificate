<?php
if(!$_COOKIE['login']) {
	header("Location: ./gateway.php");
}

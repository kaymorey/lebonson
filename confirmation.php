<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('confirmation.html.twig');

	session_start();

	if(!isset($_SESSION['cart'])) {
		header('Location: index.php');
	}
	if(!isset($_SESSION['customer'])) {
		header('Location: index.php');
	}

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"cart" => $_SESSION['cart']
	)));
<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('delivery.html.twig');

	session_start();

	$address = getAddressCustomer($idCustomer);

	if(!isset($_SESSION['cart'])) {
		header('Location: index.php');
	}
	if(!isset($_SESSION['mail'])) {
		header('Location: index.php');
	}

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH
	)));
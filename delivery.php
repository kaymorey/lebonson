<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('delivery.html.twig');

	session_start();

	if(!isset($_SESSION['cart'])) {
		header('Location: index.php');
	}
	if(!isset($_SESSION['customer'])) {
		header('Location: index.php');
	}

	$address = getAddressCustomer($_SESSION['customer']);

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"address" => $address
	)));
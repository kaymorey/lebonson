<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('account.html.twig');

	session_start();

	if(!isset($_SESSION['customer'])) {
		header('Location: index.php');
	}

	$orders = getOrdersByCustomer($_SESSION['customer']);

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"categoryMenu" => "account",
		"orders" => $orders
	)));
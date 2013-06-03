<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('account.html.twig');

	session_start();

	if(!isset($_SESSION['customer'])) {
		header('Location: index.php');
	}

	$customer = getCustomerById($_SESSION['customer']);
	$orders = getOrdersByCustomer($_SESSION['customer']);
	$delivery = getAddressCustomer($customer['id']);

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"categoryMenu" => "account",
		"customer" => $customer,
		"orders" => $orders,
		"delivery" => $delivery
	)));
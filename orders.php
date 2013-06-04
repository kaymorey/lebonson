<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('order.html.twig');

	session_start();

	if(!isset($_SESSION['customer'])) {
		header('Location: index.php');
	}
	if(!isset($_GET['order'])) {
		header('Location: index.php');
	}
	$customer = $_SESSION['customer'];
	$order = getOrderById($_GET['order']);
	$delivery = getAddressById($order['id_delivery_address']);
	$billing = getAddressById($order['id_billing_address']);

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"categoryMenu" => "account",
		"customer" => $customer,
		"order" => $order,
		"delivery" => $delivery,
		"billing" => $billing
	)));
<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/orders/details.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}
	if(!isset($_GET['order'])) {
		header('Location: index.php');
	}

	$order = getOrderById($_GET['order']);
	$customer = getCustomerById($order['id_customer']);
	$delivery = getAddressById($order['id_delivery_address']);
	$billing = getAddressById($order['id_billing_address']);
	$details = getOrderDetails($order['id']);

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'orders',
		'root' => '../../',
		'order' => $order,
		'details' => $details,
		'customer' => $customer,
		'delivery' => $delivery,
		'billing' => $billing
	)));
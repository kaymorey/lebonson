<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/orders/index.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}

	$orders = getAllOrders();
	$editOrder = null;

	if(isset($_GET['edit']) && is_numeric($_GET['edit']) && isOrder($_GET['edit'])) {
		$editOrder = $_GET['edit'];
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'orders',
		'root' => '../../',
		'orders' => $orders,
		'editOrder' => $editOrder
	)));
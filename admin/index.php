<?php
	require '../conf/top.php';
	$template = $tpl->loadTemplate('admin/index.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: login.php');
	}

	$nbOrders = getNbOrders();
	$averageAmount = getAverageAmountOrders();
	$nbCustomers = getNbCustomers();
	$averageOrders = round($nbOrders / $nbCustomers, 0);
	$bestProducts = getBestProducts();
	$bestCustomers = getBestCustomers();

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'home',
		'root' => '../',
		'nbOrders' => $nbOrders,
		'averageAmount' => $averageAmount,
		'nbCustomers' => $nbCustomers,
		'averageOrders' => $averageOrders,
		'bestProducts' => $bestProducts,
		'bestCustomers' => $bestCustomers
	)));
<?php
	require '../conf/top.php';
	$template = $tpl->loadTemplate('admin/products.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: login.php');
	}

	$products = getAllProducts();

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'products'
	)));
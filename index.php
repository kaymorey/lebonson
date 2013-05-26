<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('index.html.twig');

	session_start();

	if(isset($_GET['logout'])) {
		session_destroy();
	}

	if(!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}
	
	$nbProductsCart = nbProductsCart();

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"nbProductsCart" => $nbProductsCart
	)));
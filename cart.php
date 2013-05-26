<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('cart.html.twig');

	session_start();

	if(!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}
	$nbProductsCart = nbProductsCart();
	$addProduct = null;

	if(isset($_GET['add']) && is_numeric($_GET['add']) && getProductById($_GET['add']) != null) {
		addToCart($_GET['add']);
		$addProduct = getProductById($_GET['add']);
		$template = $tpl->loadTemplate('ajax/add-cart.html.twig');
	}
	if(isset($_GET['changeQuantity']) && is_numeric($_GET['changeQuantity']) && getProductById($_GET['changeQuantity']) != null) {
		changeQuantityCart($_GET['changeQuantity'], $_GET['quantity']);
		$nbProductsCart = nbProductsCart();
		echo $nbProductsCart;
		return;
	}
	if(isset($_GET['remove']) && is_numeric($_GET['remove']) && getProductById($_GET['remove']) != null) {
		removeFromCart($_GET['remove']);
		$nbProductsCart = nbProductsCart();
		echo $nbProductsCart;
		return;
	}

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"nbProductsCart" => $nbProductsCart,
		"cart" => $_SESSION['cart'],
		"product" => $addProduct
	)));
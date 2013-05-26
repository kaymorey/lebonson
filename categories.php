<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('categories.html.twig');

	session_start();

	if(!isset($_GET['category'])) {
		header('Location: index.php');
	}
	if(!getCategoryBySlug($_GET['category'])) {
		header('Location: index.php');	
	}

	if(!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}
	
	$nbProductsCart = nbProductsCart();
	$category = getCategoryBySlug($_GET['category']);
	$products = getProductsByCategory($category['id']);
	$mainArtists = getMainArtistsByCategory($category['id']);

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"nbProductsCart" => $nbProductsCart,
		"category" => $category,
		"products" => $products,
		"mainArtists" => $mainArtists
	)));
<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('categories.html.twig');

	session_start();

	$sort = null;
	$date = null;
	$art = null;

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

	if(isset($_GET['sort'])) {
		$sort = $_GET['sort'];
	}
	if(isset($_GET['date'])) {
		$date = $_GET['date'];
	}
	if(isset($_GET['art'])) {
		$art = $_GET['art'];
	}
	if(empty($sort) && empty($date) && empty($art)) {
		$products = getProductsByCategory($category['id']);
	}
	else {
		$products = getProductsByMultipleArgs($category['id'], $sort, $date, $art);
	}
	$mainArtists = getMainArtistsByCategory($category['id']);

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"categoryMenu" => $category['slug'],
		"nbProductsCart" => $nbProductsCart,
		"category" => $category,
		"products" => $products,
		"mainArtists" => $mainArtists,
		"sort" => $sort,
		"date" => $date,
		"art" => $art
	)));
<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('product.html.twig');

	session_start();

	if(!isset($_GET['product'])) {
		header('Location: index.php');
	}
	$product = getProductBySlug($_GET['product']);
	$category = getCategoryById($product['id_category']);

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"product" => $product,
		"categoryMenu" => $category['slug']
	)));
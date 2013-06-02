<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('product.html.twig');

	session_start();

	if(!isset($_GET['product'])) {
		header('Location: index.php');
	}
	$product = getProductBySlug($_GET['product']);


	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"product" => $product
	)));
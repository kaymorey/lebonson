<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/products/index.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}

	$products = getAllProducts();
	$addProduct = null;
	$editProduct = null;
	$rmvProduct = null;

	if(isset($_GET['add']) && is_numeric($_GET['add'])) {
		$addProduct = getProductById($_GET['add']);
		if(!$addProduct) {
			$addProduct = null;
		}
	}
	if(isset($_GET['edit']) && is_numeric($_GET['edit'])) {
		$editProduct = getProductById($_GET['edit']);
		if(!$editProduct) {
			$editProduct = null;
		}
	}
	if(isset($_GET['remove']) && $_GET['remove'] == '1') {
		$rmvProduct = true;
	}


	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'products',
		'root' => '../../',
		'products' => $products,
		'addProduct' => $addProduct,
		'editProduct' => $editProduct,
		'rmvProduct' => $rmvProduct
	)));
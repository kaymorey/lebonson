<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/categories/remove.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}
	if(!isset($_GET['category']) || !is_numeric($_GET['category'])) {
		header('Location: index.php');
	}
	$idCategory = $_GET['category'];
	$category = getCategoryById($idCategory);
	if(!$category) {
		header('Location: index.php');
	}

	$hasProducts = categoryHasProducts($idCategory);

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(!$hasProducts) {
			removeCategory($idCategory);
		}
		else {
			$products = getProductsByCategory($idCategory);
			foreach($products as $product) {
				unlink('../../uploads/products/'.$product['image']);
				removeProduct($product['id']);
			}
			removeCategory($idCategory);
		}
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'categories',
		'root' => '../../',
		'category' => $category,
		'hasProducts' => $hasProducts
	)));
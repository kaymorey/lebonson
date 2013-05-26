<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/products/remove.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}
	if(!isset($_GET['product']) || !is_numeric($_GET['product'])) {
		header('Location: index.php');
	}
	$idProduct = $_GET['product'];
	$product = getProductById($idProduct);
	if(!$product) {
		header('Location: index.php');
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$image = getImageProduct($idProduct);
		// Suppression de l'image
		unlink('../../uploads/products/'.$image);
		// Suppression des images imagine
		$dir = '../../uploads/products';
		$extension = strstr($image, '.');
		$filename = strstr($image, '.', true);
		foreach(glob($dir.'/'.$filename.'_*x*'.$extension) as $file) {
			if(!unlink($file)) {
				$errors[] = 'Erreur lors de la suppression des images imagine';
			}
		}
		removeProduct($idProduct);
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'products',
		'root' => '../../',
		'product' => $product
	)));
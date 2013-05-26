<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/artists/remove.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}
	if(!isset($_GET['artist']) || !is_numeric($_GET['artist'])) {
		header('Location: index.php');
	}
	$idArtist = $_GET['artist'];
	$artist = getArtistById($idArtist);
	if(!$artist) {
		header('Location: index.php');
	}

	$hasProducts = artistHasProducts($idArtist);

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(!$hasProducts) {
			removeArtist($idArtist);
		}
		else {
			$products = getProductsByArtist($idArtist);
			foreach($products as $product) {
				unlink('../../uploads/products/'.$product['image']);
				removeProduct($product['id']);
			}
			removeArtist($idArtist);
		}
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'artists',
		'root' => '../../',
		'artist' => $artist,
		'hasProducts' => $hasProducts
	)));
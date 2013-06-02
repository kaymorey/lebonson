<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('confirmation.html.twig');

	session_start();

	$errors = array();

	if(!isset($_SESSION['cart'])) {
		header('Location: index.php');
	}
	if(!isset($_SESSION['customer'])) {
		header('Location: index.php');
	}
	if(!isset($_GET['d']) || !isset($_GET['b'])) {
		header('Location: index.php');
	}
	$delivery = getAddressById($_GET['d']);
	$billing = getAddressById($_GET['b']);

	if(isset($_GET['confirm']) && $_GET['confirm'] == "true") {
		$addOrder = addOrder($_SESSION['customer'], $delivery['id'], $billing['id']);
		if(!$addOrder) {
			$errors[] = 'Erreur lors de la confirmation de la commande.'; 
		} 
		else {
			emptyCart();
			header('Location: account.php');
		}
	}

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"cart" => $_SESSION['cart'],
		"delivery" => $delivery,
		"billing" => $billing,
		"errors" => $errors
	)));
<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('authentication.html.twig');

	session_start();

	$errorsRegistration = array();
	$errorsConnexion = array();
	$addCustomer = null;
	$mail = null;
	$cart = true;

	if(!isset($_SESSION['cart'])) {
		header('Location: index.php');
	}
	if(isset($_SESSION['customer']) && isCustomer($_SESSION['customer'])) {
		header('Location: delivery.php');
	}
	if(isset($_POST['action']) && $_POST['action'] == 'registration') {
		include 'registration.php';
		$errorsRegistration = $errors;
	}
	else if(isset($_POST['action']) && $_POST['action'] == 'connexion') {
		include 'connexion.php';
		$errorsConnexion = $errors;
	}
	else if(!isset($_POST['conditions']) || !$_POST['conditions']) {
		$cart = false;
	}

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"categoryMenu" => "authentication"
		"errorsRegistration" => $errorsRegistration,
		"errorsConnexion" => $errorsConnexion,
		"addCustomer" => $addCustomer,
		"mail" => $mail,
		"cart" => $cart
	)));
<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('authentication.html.twig');

	session_start();

	$errorsRegistration = array();
	$errorsConnexion = array();
	$addCustomer = null;
	$mail = null;

	if(!isset($_SESSION['cart'])) {
		header('Location: index.php');
	}
	if(isset($_SESSION['customer']) && isCustomer($_SESSION['customer'])) {
		// Redirection livraison
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
		header('Location: cart.php');
	}

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"errorsRegistration" => $errorsRegistration,
		"errorsConnexion" => $errorsConnexion,
		"addCustomer" => $addCustomer,
		"mail" => $mail
	)));
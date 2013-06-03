<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('perso-informations.html.twig');

	session_start();

	$errorsInformations = array();
	$errorsAddress = array();
	$customer = getCustomerById($_SESSION['customer']);
	$address = getAddressCustomer($customer['id']);

	$editCustomer = null;

	if(!isset($_SESSION['customer'])) {
		header('Location: index.php');
	}

	if(isset($_POST['action']) && $_POST['action'] == 'perso_infos') {
		$update = true;
		include 'registration.php';
		$customer = getCustomerById($_SESSION['customer']);
		$errorsInformations = $errors;
	}
	else if(isset($_POST['action']) && $_POST['action'] == 'connexion') {
		include 'connexion.php';
		$errorsAddress = $errors;
	}

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"customer" => $customer,
		"address" => $address,
		"errorsInformations" => $errorsInformations,
		"errorsAddress" => $errorsAddress,
		"editCustomer" => $editCustomer
	)));
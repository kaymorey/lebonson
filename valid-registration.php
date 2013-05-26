<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('valid-registration.html.twig');
	
	if(!isset($_GET['valid_register']) || !isset($_GET['key']) || !isCustomer($_GET['valid_register'])) {
		header('Location: index.php');
	}
	else {
		$errors = array();

		$mail = $_GET['valid_register'];
		$key = $_GET['key'];
		$valid = validCustomer($mail, $key);
	}

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"errors" => $errors,
		"validRegistration" => $valid
	)));
<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('perso-informations.html.twig');

	session_start();

	$errorsInformations = array();
	$errorsAddress = array();
	$customer = getCustomerById($_SESSION['customer']);
	$address = getAddressCustomer($customer['id']);

	$editCustomer = null;
	$editAddress = null;
	$lastname = null;
	$firstname = null;
	$address1 = null;
	$address2 = null;
	$postcode = null;
	$city = null;

	if(!isset($_SESSION['customer'])) {
		header('Location: index.php');
	}

	if(isset($_POST['action']) && $_POST['action'] == 'perso_infos') {
		$update = true;
		include 'registration.php';
		$customer = getCustomerById($_SESSION['customer']);	
		$errorsInformations = $errors;
	}
	else if(isset($_POST['action']) && $_POST['action'] == 'address') {
		if(!isset($_POST['civility'])) {
			$civility = "";
		}
		else {
			$civility = $_POST['civility'];
		}
		$lastname = $_POST['lastname'];
		$firstname = $_POST['firstname'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$postcode = $_POST['postcode'];
		$city = $_POST['city'];

		// Supprimer les balises HTML et PHP
		$lastname = strip_tags($lastname);
		$firstname = strip_tags($firstname);
		$address1 = strip_tags($address1);
		$address2 = strip_tags($address2);
		$postcode = strip_tags($postcode);
		$city = strip_tags($city);

		// Supprimer les espaces en début et fin de chaine
		$lastname = trim($lastname);
		$firstname = trim($firstname);
		$address1 = trim($address1);
		$address2 = trim($address2);
		$postcode = trim($postcode);
		$city = trim($city);

		if(empty($lastname) || empty($firstname) || empty($address1) || empty($postcode) || empty($city)) {
			$errorsAddress[] = 'Veuillez remplir tous les champs suivis d\'un astérisque.';
		}
		else {
			// Vérifier la civilité
			if($civility != "Mme" && $civility != "Mlle" && $civility != "M.") {
				$errorsAddress[] = 'Veuillez choisir une des options proposées pour la civilité.';
			}
			// Vérifier noms et prénoms
			if(!preg_match("#[A-Za-z]#", $lastname)) {
				$errorsAddress[] = 'Le nom n\'est pas au bon format.';
			}
			if(!preg_match("#[A-Za-z]#", $firstname)) {
				$errorsAddress[] = 'Le prénom n\'est pas au bon format.';
			}
			// Vérifier code postal
			if(!preg_match("#[0-9]{5}#", $postcode)) {
				$errorsAddress[] = 'Le code postal n\'est pas au bon format';
			}
		}
		if(empty($errorsAddress)) {
			// ---- Adresse de livraison ----
			$defaultAddress = $address['id'];
			// Mettre l'adresse par défaut à 0 (ne pas la supprimer car elle a )
			if(!empty($defaultAddress)) {
				setDefaultAddress($defaultAddress, 0);
			}
			// Si l'adresse est déjà en base
			$isAddress = isAddress($customer['id'], $civility, $firstname, $lastname, $address1, $address2, $postcode, $city, 'delivery');
			// Modifier son champ défaut à 1
			if($isAddress != false) {
				echo('pap');
				setDefaultAddress($isAddress, 1);
			}
			// Sinon l'ajouter en base
			else {
				$addAddress = addAddress($customer['id'], $civility, $firstname, $lastname, $address1, $address2, $postcode, $city, 1, 'delivery');
				if(!$addAddress) {
					$errorsAddress[] = 'Erreur lors de l\'exécution de la requête';
				}
			}
			if(empty($errorsAddress)) {
				$editAddress = true;
				$address = getAddressCustomer($customer['id']);
			}
		}
	}

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"customer" => $customer,
		"address" => $address,
		"errorsInformations" => $errorsInformations,
		"errorsAddress" => $errorsAddress,
		"editCustomer" => $editCustomer,
		"editAddress" => $editAddress,
		"lastname" => $lastname,
		"firstname" => $firstname,
		"address1" => $address1,
		"address2" => $address2,
		"postcode" => $postcode,
		"city" => $city
	)));
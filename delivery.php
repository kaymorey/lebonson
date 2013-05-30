<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('delivery.html.twig');

	session_start();

	if(!isset($_SESSION['cart'])) {
		header('Location: index.php');
	}
	if(!isset($_SESSION['customer'])) {
		header('Location: index.php');
	}

	$errors = array();
	$errorsB = array();
	$lastname = null;
	$firstname = null;
	$address1 = null;
	$address2 = null;
	$postcode = null;
	$city = null;
	$lastnameB = null;
	$firstnameB = null;
	$address1B = null;
	$address2B = null;
	$postcodeB = null;
	$cityB = null;
	$idCustomer = $_SESSION['customer'];

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(!isset($_POST['lastname']) || !isset($_POST['firstname'])  || !isset($_POST['address1']) || !isset($_POST['address2']) || !isset($_POST['postcode']) || !isset($_POST['city']) || !isset($_POST['lastnameB']) || !isset($_POST['firstnameB']) || !isset($_POST['address1B']) || !isset($_POST['address2B']) || !isset($_POST['postcodeB']) || !isset($_POST['cityB'])) {
			$errors[] = 'Formulaire non conforme.';
		}
		else {
			// --- Adresse de livraison ----
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
			if(!isset($_POST['default'])) {
				$default = 0;
			}
			else {
				$default = 1;
			}

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
				$errors[] = 'Veuillez remplir tous les champs suivis d\'un astérisque.';
			}
			else {
				// Vérifier la civilité
				if($civility != "Mme" && $civility != "Mlle" && $civility != "M.") {
					$errors[] = 'Veuillez choisir une des options proposées pour la civilité.';
				}
				// Vérifier noms et prénoms
				if(!preg_match("#[A-Za-z]#", $lastname)) {
					$errors[] = 'Le nom n\'est pas au bon format.';
				}
				if(!preg_match("#[A-Za-z]#", $firstname)) {
					$errors[] = 'Le prénom n\'est pas au bon format.';
				}
				// Vérifier code postal
				if(!preg_match("#[0-9]{5}#", $postcode)) {
					$errors[] = 'Le code postal n\'est pas au bon format';
				}
			}
			// ---- Adresse de facturation ----
			if(isset($_POST['billing'])) {
				$civilityB = $_POST['civilityB'];
				$lastnameB = $_POST['lastnameB'];
				$firstnameB = $_POST['firstnameB'];
				$address1B = $_POST['address1B'];
				$address2B = $_POST['address2B'];
				$postcodeB = $_POST['postcodeB'];
				$cityB = $_POST['cityB'];

				// Supprimer les balises HTML et PHP
				$lastnameB = strip_tags($lastnameB);
				$firstnameB = strip_tags($firstnameB);
				$address1B = strip_tags($address1B);
				$address2B = strip_tags($address2B);
				$postcodeB = strip_tags($postcodeB);
				$cityB = strip_tags($cityB);

				// Supprimer les espaces en début et fin de chaine
				$lastnameB = trim($lastnameB);
				$firstnameB = trim($firstnameB);
				$address1B = trim($address1B);
				$address2B = trim($address2B);
				$postcodeB = trim($postcodeB);
				$cityB = trim($cityB);

				if(empty($lastnameB) || empty($firstnameB) || empty($address1B) || empty($postcodeB) || empty($cityB)) {
					$errorsB[] = 'Veuillez remplir tous les champs suivis d\'un astérisque.';
				}
				else {
					// Vérifier la civilité
					if($civilityB != "Mme" && $civilityB != "Mlle" && $civilityB != "M.") {
						$errorsB[] = 'Veuillez choisir une des options proposées pour la civilité.';
					}
					// Vérifier noms et prénoms
					if(!preg_match("#[A-Za-z]#", $lastnameB)) {
						$errorsB[] = 'Le nom n\'est pas au bon format.';
					}
					if(!preg_match("#[A-Za-z]#", $firstnameB)) {
						$errorsB[] = 'Le prénom n\'est pas au bon format.';
					}
					// Vérifier code postal
					if(!preg_match("#[0-9]{5}#", $postcodeB)) {
						$errorsB[] = 'Le code postal n\'est pas au bon format';
					}
				}
			}
			else {
				$civilityB = $civility;
				$lastnameB = $lastname;
				$firstnameB = $firstname;
				$address1B = $address1;
				$address2B = $address2;
				$postcodeB = $postcode;
				$cityB = $city;
			}
			if(empty($errors) && empty($errorsB)) {
				// ---- Adresse de livraison ----
				// Vérifier si l'adresse n'est pas déjà répertoriée en base
				$isAddress = isAddress($idCustomer, $civility, $firstname, $lastname, $address1, $address2, $postcode, $city, 'delivery');
				if($isAddress != false) {
					$addressDelivery = $isAddress;
				}
				else {
					// Ajouter l'adresse en base
					$addAddress = addAddress($idCustomer, $civility, $firstname, $lastname, $address1, $address2, $postcode, $city, $default, 'delivery');
					if(!$addAddress) {
						$errors[] = 'Erreur lors de l\'exécution de la requête';
					}
					$addressDelivery = getLastAddress($idCustomer, 'delivery');
				}
				// ---- Adresse de facturation
				// Vérifier si l'adresse n'est pas déjà répertoriée en base
				$isAddress = isAddress($idCustomer, $civilityB, $firstnameB, $lastnameB, $address1B, $address2B, $postcodeB, $cityB, 'billing');
				if($isAddress != false) {
					$addressBilling = $isAddress;
				}
				else {
					// Ajouter l'adresse en base
					$addAddress = addAddress($idCustomer, $civilityB, $firstnameB, $lastnameB, $address1B, $address2B, $postcodeB, $cityB, 0, 'billing');
					if(!$addAddress) {
						$errors[] = 'Erreur lors de l\'exécution de la requête';
					}
					$addressBilling = getLastAddress($idCustomer, 'billing');
				}

				if(empty($errors) && empty($errorsB)) {
					header('Location: confirmation.php?d='.$addressDelivery.'&b='.$addressBilling);
				}
			}
		}
	}

	$address = getAddressCustomer($idCustomer);

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"address" => $address,
		"errorsDelivery" => $errors,
		"errorsBilling" => $errorsB,
		"lastname" => $lastname,
		"firstname" => $firstname,
		"address1" => $address1,
		"address2" => $address2,
		"postcode" => $postcode,
		"city" => $city,
		"lastnameB" => $lastnameB,
		"firstnameB" => $firstnameB,
		"address1B" => $address1B,
		"address2B" => $address2B,
		"postcodeB" => $postcodeB,
		"cityB" => $cityB,
	)));
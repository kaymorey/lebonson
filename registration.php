<?php
	$errors = array();

	if(!isset($_POST['lastname']) || !isset($_POST['firstname']) || !isset($_POST['mail']) || !isset($_POST['passwd']) || !isset($_POST['passwd-confirm'])) {
		$errors[] = 'Formulaire non conforme.';
	}
	else {
		if(!isset($_POST['civility'])) {
			$civility = "";
		}
		else {
			$civility = $_POST['civility'];
		}
		$lastname = $_POST['lastname'];
		$firstname = $_POST['firstname'];
		$mail = $_POST['mail'];
		$passwd = $_POST['passwd'];
		$passwdConfirm = $_POST['passwd-confirm'];

		// Supprimer les balises HTML et PHP
		$lastname = strip_tags($lastname);
		$firstname = strip_tags($firstname);
		$mail = strip_tags($mail);
		$passwd = strip_tags($passwd);
		$passwdConfirm = strip_tags($passwdConfirm);

		// Supprimer les espaces en début et fin de chaine
		$lastname = trim($lastname);
		$firstname = trim($firstname);
		$mail = trim($mail);
		$passwd = trim($passwd);
		$passwdConfirm = trim($passwdConfirm);

		// Vérifier que les champs ne sont pas vides
		if(empty($lastname) || empty($firstname) || empty($mail) || empty($passwd) || empty($passwdConfirm)) {
			$errors[] = 'Veuillez remplir tous les champs.';
		}
		else {
			$regexMail = "#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#";
			$regexPasswd = "#[a-zA-Z0-9]{4,}#";

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
			if(!preg_match($regexMail, $mail)) {
				$errors[] = 'L\'adresse mail n\'est pas valide.';
			}
			if(!preg_match($regexPasswd, $passwd)) {
				$errors[] = 'Le mot de passe dont être composé d\'au moins 4 caractères alphanumériques.';
			}
			if($passwd != $passwdConfirm) {
				$errors[] = 'Les mots de passe doivent être identiques.';
			}
			if(isCustomerMail($mail)) {
				$errors[] = 'Un utilisateur est déjà enregistré avec cette adresse email.';
			}
		}
		if(empty($errors)) {
			$key = uniqid();
			$salt = generateSalt();
			$passwd = crypt($passwd, $salt);
			$addCustomer = addCustomer($civility, $lastname, $firstname, $mail, $passwd, $key);
			sendRegistrationMail($mail, $key);
		}
	}
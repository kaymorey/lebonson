<?php
	if(!isset($_POST['mail']) || !isset($_POST['passwd']) || !isset($_POST['passwd-confirm'])) {
		$errors[] = 'Formulaire non conforme.';
	}
	else {
		$mail = $_POST['mail'];
		$passwd = $_POST['passwd'];
		$passwdConfirm = $_POST['passwd-confirm'];

		// Supprimer les balises HTML et PHP
		$mail = strip_tags($mail);
		$passwd = strip_tags($passwd);
		$passwdConfirm = strip_tags($passwdConfirm);

		// Supprimer les espaces en début et fin de chaine
		$mail = trim($mail);
		$passwd = trim($passwd);
		$passwdConfirm = trim($passwdConfirm);

		// Vérifier que les champs ne sont pas vides
		if(empty($mail) || empty($passwd) || empty($passwdConfirm)) {
			$errors[] = 'Veuillez remplir tous les champs.';
		}
		else {
			$regexMail = "#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#";
			$regexPasswd = "#[a-zA-Z0-9]{4,}#";

			if(!preg_match($regexMail, $mail)) {
				$errors[] = 'L\'adresse mail n\'est pas valide.';
			}
			if(!preg_match($regexPasswd, $passwd)) {
				$errors[] = 'Le mot de passe dont être composé d\'au moins 4 caractères alphanumériques.';
			}
			if($passwd != $passwdConfirm) {
				$errors[] = 'Les mots de passe doivent être identiques.';
			}
			if(isCustomer($mail)) {
				$errors[] = 'Un utilisateur est déjà enregistré avec cette adresse email.';
			}
		}
		if(empty($errors)) {
			$key = uniqid();
			$salt = generateSalt();
			$passwd = crypt($passwd, $salt);
			$addCustomer = addCustomer($mail, $passwd, $key);
			sendRegistrationMail($mail, $key);
		}
	}
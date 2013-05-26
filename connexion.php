<?php
	if(!isset($_POST['mail']) || !isset($_POST['passwd'])) {
		$errors[] = 'Formulaire non conforme.';
	}
	else {
		$mail = $_POST['mail'];
		$passwd = $_POST['passwd'];

		// Supprimer les balises HTML et PHP
		$mail = strip_tags($mail);
		$passwd = strip_tags($passwd);

		// Supprimer les espaces en début et fin de chaine
		$mail = trim($mail);
		$passwd = trim($passwd);

		// Vérifier que les champs ne sont pas vides
		if(empty($mail) || empty($passwd)) {
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
			$customer = checkCustomer($mail, $passwd);
			if(!$customer) {
				$errors[] = 'L\'identifiant ou le mot de passe est erroné.';
			}
		}
		if(empty($errors)) {
			$_SESSION['customer'] = $customer;
			header('Location: delivery.php');
		}
	}
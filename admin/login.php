<?php
	require '../conf/top.php';
	$template = $tpl->loadTemplate('admin/login.html.twig');

	$errors = array();
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(!isset($_POST['login']) || !isset($_POST['passwd']))
		{
			$errors[] = 'Formulaire non conforme.';
		}
		else {
			$login = $_POST['login'];
			$passwd = $_POST['passwd'];

			// Supprimer les balises HTML et PHP
			$login = strip_tags($login);
			$passwd = strip_tags($passwd);

			// Supprimer les espaces en début et fin de chaine
			$login = trim($login);
			$passwd = trim($passwd);

			if(empty($login) || empty($passwd)) {
				$errors[] = 'Veuillez remplir tous les champs';
			}
			else {
				if($login == ADMIN_USER && $passwd == ADMIN_PASS) {
					session_start();
					$_SESSION['admin'] = true;
					header('Location: index.php');
				}
				else {
					$errors[] = 'Mauvaise combinaison login / mot de passe';
				}
			}
		}
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'errors' => $errors
	)));
<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/artists/add.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}

	$errors = array();
	$lastname = null;
	$firstname = null;
	$biography = null;

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(!isset($_POST['lastname']) || !isset($_POST['firstname']) || !isset($_POST['biography'])) {
			$errors[] = 'Formulaire non conforme.';
		}
		else {
			$lastname = $_POST['lastname'];
			$firstname = $_POST['firstname'];
			$biography = $_POST['biography'];

			// Supprimer les balises HTML et PHP
			$lastname = strip_tags($lastname);
			$firstname = strip_tags($firstname);
			$biography = strip_tags($biography);

			// Supprimer les espaces en début et fin de chaine
			$lastname = trim($lastname);
			$firstname = trim($firstname);
			$biography = trim($biography);

			if(empty($lastname)) {
				$errors[] = 'Veuillez indiquer un nom d\'artiste.'; 
			}
			else {
				if(isArtist($lastname, $firstname)) {
					$errors[] = 'Cet artiste est déjà répertorié dans la base de données.';
				}
				else {
					$addArtist = addArtist($lastname, $firstname, $biography);
					if(!$addArtist) {
						$errors[] = 'Erreur lors de l\'exécution de la requête';
					}
					else {
						$addArtist = getArtistByNames($lastname, $firstname);
						header('Location: index.php?add='.$addArtist['id']);
					}
				}
			}
		}
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'artists',
		'root' => '../../',
		'errors' => $errors,
		'lastname_post' => $lastname,
		'firstname_post' => $firstname,
		'biography_post' => $biography
	)));
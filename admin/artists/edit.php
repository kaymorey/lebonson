<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/artists/edit.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}
	if(!isset($_GET['artist']) || !is_numeric($_GET['artist'])) {
		header('Location: index.php');
	}
	$idArtist = $_GET['artist'];
	$artist = getArtistById($idArtist);
	if(!$artist) {
		header('Location: index.php');
	}

	$errors = array();


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
				$editArtist = editArtist($idArtist, $lastname, $firstname, $biography);
				if(!$editArtist) {
					$errors[] = 'Erreur lors de l\'exécution de la requête';
				}
				else {
					header('Location: index.php?edit='.$idArtist);
				}
			}
		}
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'artists',
		'root' => '../../',
		'errors' => $errors,
		'artist' => $artist
	)));
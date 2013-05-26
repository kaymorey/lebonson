<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/categories/add.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}

	$errors = array();
	$title = null;

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(!isset($_POST['title'])) {
			$errors[] = 'Formulaire non conforme.';
		}
		else {
			$title = $_POST['title'];

			// Supprimer les balises HTML et PHP
			$title = strip_tags($title);

			// Supprimer les espaces en début et fin de chaine
			$title = trim($title);

			if(empty($title)) {
				$errors[] = 'Veuillez indiquer un nom de catégorie.'; 
			}
			else {
				if(isCategory($title)) {
					$errors[] = 'Cette catégorie est déjà répertoriée dans la base de données.';
				}
				else {
					$slug = slug($title);
					$addCategory = addCategory($title, $slug);
					if(!$addCategory) {
						$errors[] = 'Erreur lors de l\'exécution de la requête';
					}
					else {
						$addCategory = getCategoryByTitle($title);
						header('Location: index.php?add='.$addCategory['id']);
					}
				}
			}
		}
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'categories',
		'root' => '../../',
		'errors' => $errors,
		'title_post' => $title
	)));
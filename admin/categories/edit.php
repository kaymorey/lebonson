<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/categories/edit.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}
	if(!isset($_GET['category']) || !is_numeric($_GET['category'])) {
		header('Location: index.php');
	}
	$idCategory = $_GET['category'];
	$category = getCategoryById($idCategory);
	if(!$category) {
		header('Location: index.php');
	}

	$errors = array();


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
				$slug = slug($title);
				$editCategory = editCategory($idCategory, $title, $slug);
				if(!$editCategory) {
					$errors[] = 'Erreur lors de l\'exécution de la requête';
				}
				else {
					header('Location: index.php?edit='.$idCategory);
				}
			}
		}
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'categories',
		'root' => '../../',
		'errors' => $errors,
		'category' => $category
	)));
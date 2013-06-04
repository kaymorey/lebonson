<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/orders/edit.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}
	if(!isset($_GET['order']) || !is_numeric($_GET['order'])) {
		header('Location: index.php');
	}
	$idOrder = $_GET['order'];
	$order = getOrderById($idOrder);
	if(!$order) {
		header('Location: index.php');
	}

	$errors = array();


	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(!isset($_POST['status'])) {
			$errors[] = 'Formulaire non conforme.';
		}
		else {
			$status = $_POST['status'];

			if(empty($status)) {
				$errors[] = 'Veuillez indiquer un statut pour la commande.'; 
			}
			if($status != "En préparation" && $status != "Envoyée" && $status != "Annulée") {
				$errors[] = 'Veuillez choisir une des options proposées pour la civilité.';
			}
			if(empty($errors)) {
				$editOrder = editOrder($idOrder, $status);
				if(!$editOrder) {
					$errors[] = 'Erreur lors de l\'exécution de la requête';
				}
				else {
					header('Location: index.php?edit='.$idOrder);
				}
			}
		}
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'orders',
		'root' => '../../',
		'errors' => $errors,
		'order' => $order
	)));
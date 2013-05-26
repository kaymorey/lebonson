<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/categories/index.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}

	$categories = getAllCategories();
	$addCategory = null;
	$editCategory = null;
	$rmvCategory = null;

	if(isset($_GET['add']) && is_numeric($_GET['add'])) {
		$addCategory = getCategoryById($_GET['add']);
		if(!$addCategory) {
			$addCategory = null;
		}
	}
	if(isset($_GET['edit']) && is_numeric($_GET['edit'])) {
		$editCategory = getCategoryById($_GET['edit']);
		if(!$editCategory) {
			$editCategory = null;
		}
	}
	if(isset($_GET['remove']) && $_GET['remove'] == '1') {
		$rmvCategory = true;
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'categories',
		'root' => '../../',
		'categories' => $categories,
		'addCategory' => $addCategory,
		'editCategory' => $editCategory,
		'rmvCategory' => $rmvCategory
	)));
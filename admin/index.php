<?php
	require '../conf/top.php';
	$template = $tpl->loadTemplate('admin/index.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: login.php');
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'home',
		'root' => '../'
	)));
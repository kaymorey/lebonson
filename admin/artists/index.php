<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/artists/index.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}

	$artists = getAllArtists();
	$editArtist = null;
	$addArtist = null;
	$rmvArtist = null;

	if(isset($_GET['add']) && is_numeric($_GET['add'])) {
		$addArtist = getArtistById($_GET['add']);
		if(!$addArtist) {
			$addArtist = null;
		}
	}
	if(isset($_GET['edit']) && is_numeric($_GET['edit'])) {
		$editArtist = getArtistById($_GET['edit']);
		if(!$editArtist) {
			$editArtist = null;
		}
	}
	if(isset($_GET['remove']) && $_GET['remove'] == '1') {
		$rmvArtist = true;
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'artists',
		'root' => '../../',
		'artists' => $artists,
		'editArtist' => $editArtist,
		'addArtist' => $addArtist,
		'rmvArtist' => $rmvArtist
	)));
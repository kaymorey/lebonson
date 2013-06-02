<?php
	require 'conf/top.php';
	$template = $tpl->loadTemplate('account.html.twig');

	session_start();

	echo($template->render(array(
		"MEDIA_PATH" => MEDIA_PATH,
		"categoryMenu" => "account"
	));
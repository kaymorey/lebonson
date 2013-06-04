<?php
	require '../../conf/top.php';
	$template = $tpl->loadTemplate('admin/customers/index.html.twig');

	session_start();
	if(!isset($_SESSION['admin'])) {
		header('Location: ../login.php');
	}

	$customers = getAllCustomers();
	$editCustomer = null;

	if(isset($_GET['edit']) && is_numeric($_GET['edit']) && isCustomer($_GET['edit'])) {
		$editCustomer = getCustomerById($_GET['edit']);
	}

	echo($template->render(array(
		'MEDIA_PATH' => MEDIA_PATH,
		'categoryMenu' => 'customers',
		'root' => '../../',
		'customers' => $customers,
		'editCustomer' => $editCustomer
	)));
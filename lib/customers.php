<?php
function addCustomer($mail, $passwd, $key) {
	global $db;
	$req = $db->prepare('INSERT INTO customer(id, email, passwd, firstname, lastname, active, activation_key) VALUES("", :mail, :passwd, "", "", 0, :key)');
	$req->execute(array(
		':mail' => $mail,
		':passwd' => $passwd,
		':key' => $key
	));

	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function isCustomer($mail) {
	global $db;
	$req = $db->prepare('SELECT customer.id FROM customer WHERE customer.email=:mail');
	$req->execute(array(
		':mail' => $mail
	));

	$customer = $req->fetch();
	if(empty($customer)) {
		return false;
	}
	else {
		return true;
	}
}
function validCustomer($mail, $key) {
	global $db;
	// Vérifier si le mail est enregistré en base avec cette clé d'activation
	$req = $db->prepare('SELECT customer.id FROM customer WHERE customer.email = :mail AND customer.activation_key = :key');
	$req->execute(array(
		':mail' => $mail,
		':key' => $key
	));
	$customer = $req->fetch();
	if(empty($customer)) {
		return false;
	}

	// Activer le compte
	$req = $db->prepare('UPDATE customer SET active = 1 WHERE customer.email = :mail AND customer.activation_key = :key');
	$req->execute(array(
		':mail' => $mail,
		':key' => $key
	));
	if(!$req) {
		return false;
	}
	return true;
}
function checkCustomer($mail, $passwd) {
	global $db;
	$req = $db->prepare('SELECT customer.passwd, customer.id FROM customer WHERE customer.email = :mail');
	$req->execute(array(
		':mail' => $mail
	));
	$result = $req->fetch();
	$passwdHash = $result['passwd'];
	if(crypt($passwd, $passwdHash) != $passwdHash) {
		return false;
	}
	$idCustomer = $result['id'];
	return $idCustomer;
}
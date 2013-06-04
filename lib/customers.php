<?php
function getAllCustomers() {
	global $db;
	$req = $db->prepare('SELECT * FROM customer');
	$req->execute();

	$customers = $req->fetchAll();
	return $customers;
}
function addCustomer($civility, $lastname, $firstname, $mail, $passwd, $key) {
	global $db;
	$req = $db->prepare('INSERT INTO customer(id, email, passwd, civility, firstname, lastname, active, activation_key) VALUES("", :mail, :passwd, :civility, :firstname, :lastname, 0, :key)');
	$req->execute(array(
		':civility' => $civility,
		':firstname' => $firstname,
		':lastname' => $lastname,
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
function editCustomer($idCustomer, $civility, $lastname, $firstname, $mail, $passwd) {
	global $db;
	$req = $db->prepare('UPDATE customer SET civility = :civility, lastname = :lastname, firstname = :firstname, email = :mail, passwd = :passwd WHERE customer.id = :idCustomer');
	$req->execute(array(
		':idCustomer' => $idCustomer,
		':civility' => $civility,
		':firstname' => $firstname,
		':lastname' => $lastname,
		':mail' => $mail,
		':passwd' => $passwd
	));

	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function isCustomer($idCustomer) {
	global $db;
	$req = $db->prepare('SELECT customer.id FROM customer WHERE customer.id = :idCustomer');
	$req->execute(array(
		':idCustomer' => $idCustomer
	));

	$customer = $req->fetch();
	if(empty($customer)) {
		return false;
	}
	else {
		return true;
	}
}
function isCustomerMail($mail) {
	global $db;
	$req = $db->prepare('SELECT customer.id FROM customer WHERE customer.email = :mail');
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
function isActiveCustomer($idCustomer) {
	global $db;
	$req = $db->prepare('SELECT customer.id FROM customer WHERE customer.id = :idCustomer AND customer.active = 1');
	$req->execute(array(
		':idCustomer' => $idCustomer
	));
	$customer = $req->fetch();
	if(empty($customer)) {
		return false;
	}
	else {
		return true;
	}

}
function getCustomerById($idCustomer) {
	global $db;
	$req = $db->prepare('SELECT customer.* FROM customer WHERE customer.id = :idCustomer');
	$req->execute(array(
		':idCustomer' => $idCustomer 
	));

	$customer = $req->fetch();
	return $customer;
}
function getNbCustomers() {
	global $db;
	$req = $db->prepare('SELECT count(customer.id) as nbCustomers FROM customer');
	$req->execute();

	$result = $req->fetch();
	return $result['nbCustomers'];
}
function getBestCustomers() {
	global $db;
	$req = $db->prepare('SELECT customer.lastname, customer.firstname, count(orders.id_customer) as total FROM orders INNER JOIN customer ON customer.id = orders.id_customer GROUP BY customer.id ORDER BY total DESC LIMIT 0,6');
	$req->execute();

	$topCustomers = $req->fetchAll();
	return $topCustomers;
}
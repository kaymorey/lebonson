<?php
function getAddressCustomer($idCustomer) {
	global $db;
	$req = $db->prepare('SELECT address.* FROM address WHERE address.id_customer = :idCustomer');
	$req->execute(array(
		':idCustomer' => $idCustomer
	));
	$address = $req->fetch();
	
	return $address;
}
function isAddress($idCustomer, $civility, $firstname, $lastname, $address1, $address2, $postcode, $city) {
	global $db;
	$req = $db->prepare('SELECT address.id FROM address WHERE civility = :civility AND LOWER(firstname) = :firstname AND LOWER(lastname) = :lastname AND LOWER(address1) = :address1 AND LOWER(address2) = :address2 AND postcode = :postcode AND LOWER(city) = :city AND address.id_customer = :idCustomer');
	$req->execute(array(
		':civility' => $civility,
		':firstname' => strtolower($firstname),
		':lastname' => strtolower($lastname),
		':address1' => strtolower($address1),
		':address2' => strtolower($address2),
		':postcode' => $postcode,
		':city' => strtolower($city),
		':idCustomer' => $idCustomer
	));
	$address = $req->fetch();

	if(empty($address)) {
		return false;
	}
	return $address['id'];
}
function addAddress($idCustomer, $civility, $firstname, $lastname, $address1, $address2, $postcode, $city, $default, $type) {
	global $db;
	$req = $db->prepare('INSERT INTO address(id, id_customer, type, civility, address1, address2, postcode, city, firstname, lastname, defaultAddress) VALUES("", :idCustomer ,:type, :civility, :address1, :address2, :postcode, :city, :firstname, :lastname, :default)');
	$req->execute(array(
		':idCustomer' => $idCustomer,
		':type' => $type,
		':civility' => $civility,
		':address1' => $address1,
		':address2' => $address2,
		':postcode' => $postcode,
		':city' => $city,
		':firstname' => $firstname,
		':lastname' => $lastname,
		':default' => $default
	));
	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function getLastAddress($idCustomer, $type) {
	global $db;
	$req = $db->prepare('SELECT address.id FROM address WHERE address.id_customer = :idCustomer AND address.type = :type ORDER BY id DESC');
	$req->execute(array(
		':idCustomer' => $idCustomer,
		':type' => $type
	));
	$address = $req->fetch();

	if(empty($address)) {
		return false;
	}
	else {
		return $address;
	}
}
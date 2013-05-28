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
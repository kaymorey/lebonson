<?php

function getAllArtists() {
	global $db;
	$req = $db->prepare('SELECT * FROM artist');
	$req->execute();

	$artists = $req->fetchAll();
	return $artists;
}
function getMainArtistsByCategory($idCategory) {
	global $db;
	$req = $db->prepare('SELECT COUNT(product.id) as nbProducts, artist.* FROM product INNER JOIN artist ON product.id_artist = artist.id WHERE product.id_category = :idCategory GROUP BY product.id_artist ORDER BY nbProducts LIMIT 0,5');
	$req->execute(array(
		':idCategory' => $idCategory
	));

	$artists = $req->fetchAll();

	return $artists;
}
function getArtistById($idArtist) {
	global $db;
	$req = $db->prepare('SELECT * FROM artist WHERE artist.id = :idArtist');
	$req->execute(array(
		':idArtist' => $idArtist
	));

	$artist = $req->fetch();
	if(empty($artist)) {
		return false;
	}
	else {
		return $artist;
	}
}
function getArtistByNames($lastname, $firstname) {
	global $db;
	$req = $db->prepare('SELECT * FROM artist WHERE artist.lastname = :lastname AND artist.firstname = :firstname');
	$req->execute(array(
		':lastname' => $lastname,
		':firstname' => $firstname
	));

	$artist = $req->fetch();
	if(empty($artist)) {
		return false;
	}
	else {
		return $artist;
	}
}
function addArtist($lastname, $firstname, $biography) {
	global $db;
	$req = $db->prepare('INSERT INTO artist(id, lastname, firstname, biography) VALUES("", :lastname, :firstname, :biography)');
	$req->execute(array(
		':lastname' => $lastname,
		':firstname' => $firstname,
		':biography' => $biography
	));

	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function editArtist($idArtist, $lastname, $firstname, $biography) {
	global $db;
	$req = $db->prepare('UPDATE artist SET lastname=:lastname, firstname=:firstname, biography=:biography WHERE artist.id = :idArtist');
	$req->execute(array(
		':lastname' => $lastname,
		':firstname' => $firstname,
		':biography' => $biography,
		':idArtist' => $idArtist
	));

	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function removeArtist($idArtist) {
	global $db;
	$req = $db->prepare('DELETE FROM artist WHERE artist.id = :idArtist');
	$req->execute(array(
		':idArtist' => $idArtist
	));

	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function isArtist($lastname, $firstname) {
	global $db;
	$req = $db->prepare('SELECT artist.id FROM artist WHERE artist.lastname = :lastname AND artist.firstname = :firstname');
	$req->execute(array(
		':lastname' => $lastname,
		':firstname' => $firstname
	));

	$artist = $req->fetch();
	if(empty($artist)) {
		return false;
	}
	return true;

}
function artistHasProducts($idArtist) {
	global $db;
	$req = $db->prepare('SELECT product.id FROM product INNER JOIN artist ON product.id_artist = artist.id WHERE artist.id = :idArtist');
	$req->execute(array(
		':idArtist' => $idArtist
	));

	$products = $req->fetchAll();

	if(empty($products)) {
		return false;
	}
	return true;
}
function getNbProductsByArtist($idArtist) {
	global $db;
	$req = $db->prepare('SELECT COUNT(product.id) AS nbProducts FROM product WHERE product.id_artist = :idArtist');
	$req->execute(array(
		':idArtist' => $idArtist
	));

	$nbProducts = $req->fetch();

	return $nbProducts['nbProducts'];
}
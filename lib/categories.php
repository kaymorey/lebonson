<?php

function getAllCategories() {
	global $db;
	$req = $db->prepare('SELECT * FROM category');
	$req->execute();

	$categories = $req->fetchAll();
	return $categories;
}
function getCategoryById($idCategory) {
	global $db;
	$req = $db->prepare('SELECT * FROM category WHERE category.id = :idCategory');
	$req->execute(array(
		':idCategory' => $idCategory
	));

	$category = $req->fetch();
	if(empty($category)) {
		return false;
	}
	else {
		return $category;
	}
}
function getCategoryByTitle($title) {
	global $db;
	$req = $db->prepare('SELECT category.id FROM category WHERE category.title = :title');
	$req->execute(array(
		':title' => $title
	));

	$category = $req->fetch();
	if(empty($category)) {
		return false;
	}
	else {
		return $category;
	}
}
function getCategoryBySlug($slug) {
	global $db;
	$req = $db->prepare('SELECT * FROM category WHERE category.slug = :slug');
	$req->execute(array(
		":slug" => $slug
	));

	$category = $req->fetch();
	if(empty($category)) {
		return false;
	}
	else {
		return $category;
	}
}
function addCategory($title, $slug) {
	global $db;
	$req = $db->prepare('INSERT INTO category(id, title, slug) VALUES("", :title, :slug)');
	$req->execute(array(
		':title' => $title,
		':slug' => $slug
	));

	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function editCategory($idCategory, $title, $slug) {
	global $db;
	$req = $db->prepare('UPDATE category SET title=:title, slug=:slug WHERE category.id = :idCategory');
	$req->execute(array(
		':title' => $title,
		':slug' => $slug,
		':idCategory' => $idCategory
	));

	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function removeCategory($idCategory) {
	global $db;
	$req = $db->prepare('DELETE FROM category WHERE category.id = :idCategory');
	$req->execute(array(
		':idCategory' => $idCategory
	));

	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function isCategory($title) {
	global $db;
	$req = $db->prepare('SELECT category.id FROM category WHERE category.title = :title');
	$req->execute(array(
		':title' => $title
	));

	$category = $req->fetch();
	if(empty($category)) {
		return false;
	}
	return true;

}
function categoryHasProducts($idCategory) {
	global $db;
	$req = $db->prepare('SELECT product.id FROM product INNER JOIN category ON product.id_category = category.id WHERE category.id = :idCategory');
	$req->execute(array(
		':idCategory' => $idCategory
	));

	$products = $req->fetchAll();

	if(empty($products)) {
		return false;
	}
	return true;
}
function getNbProductsByCategory($idCategory) {
	global $db;
	$req = $db->prepare('SELECT COUNT(product.id) AS nbProducts FROM product WHERE product.id_category = :idCategory');
	$req->execute(array(
		':idCategory' => $idCategory
	));

	$nbProducts = $req->fetch();

	return $nbProducts['nbProducts'];
}
<?php

function getAllProducts() {
	global $db;
	$req = $db->prepare('SELECT * FROM product');
	$req->execute();

	$products = $req->fetchAll();
	return $products;
}
function getProductById($idProduct) {
	global $db;
	$req = $db->prepare('SELECT * FROM product WHERE product.id = :idProduct');
	$req->execute(array(
		':idProduct' => $idProduct
	));

	$product = $req->fetch();
	return $product;
}
function getProductBySlug($slug) {
	global $db;
	$req = $db->prepare('SELECT * FROM product WHERE product.slug = :slug');
	$req->execute(array(
		':slug' => $slug
	));

	$product = $req->fetch();
	return $product;
}
function getProductsByCategory($idCategory) {
	global $db;
	$req = $db->prepare('SELECT * FROM product WHERE product.id_category = :idCategory');
	$req->execute(array(
		':idCategory' => $idCategory
	));

	$products = $req->fetchAll();
	return $products;
}
function getProductsByArtist($idArtist) {
	global $db;
	$req = $db->prepare('SELECT * FROM product WHERE product.id_artist = :idArtist');
	$req->execute(array(
		':idArtist' => $idArtist
	));

	$products = $req->fetchAll();
	return $products;
}
function getProductByTitleAndArtist($title, $idArtist) {
	global $db;
	$req = $db->prepare('SELECT product.id FROM product WHERE product.title = :title AND product.id_artist = :idArtist');
	$req->execute(array(
		':title' => $title,
		':idArtist' => $idArtist
	));

	$product = $req->fetch();
	return $product;
}
function getProductsByMultipleArgs($idCategory, $sort, $date, $idArtist) {
	global $db;

	if($sort != null && $sort == "best") {
		$sql = 'SELECT SUM(order_detail.quantity) as total, product.* FROM product INNER JOIN order_detail ON product.id = order_detail.id_product WHERE product.id_category = :idCategory AND';
	}
	else {
		$sql = 'SELECT * FROM product WHERE product.id_category = :idCategory AND';
	}
	$parameters = array();
	$parameters[':idCategory'] = $idCategory;

	if($idArtist != null) {
		$sql .= ' product.id_artist = :idArtist';
		$sql.= ' AND';
		$parameters[':idArtist'] = $idArtist;
	}
	if($date != null) {
		if($date == '3m') {
			$from = new DateTime();
			$from->sub(new DateInterval('P3M'));
		}
		elseif($date == '612m') {
			$from = new DateTime();
			$from->sub(new DateInterval('P12M'));
			$to = new DateTime();
			$to->sub(new DateInterval('P6M'));
		}
		elseif($date == '12m') {
			$to = new DateTime();
			$to->sub(new DateInterval('P12M'));
		}
		if(isset($from)) {
			$sql .= ' product.date > :from';
			$sql .= ' AND'; 
			$parameters[':from'] = $from->format('Y-m-d');
		}
		if(isset($to)) {
			$sql .= ' product.date <  :to';
			$sql .= ' AND';
			$parameters[':to'] = $to->format('Y-m-d');
		}
	}
	if(substr($sql, -4, 4) == ' AND') {
		$sql = substr($sql, 0, -4);
	}
	if($sort != null) {
		if($sort == "best") {
			$sql .= ' GROUP BY product.id ORDER BY total';
		}
		else {
			if($sort == 'titleza') {
				$sort = 'title DESC';
			}
			elseif($sort == 'pricedesc') {
				$sort = 'price DESC';
			}
			$sql .= ' ORDER BY '.$sort;
		}
	}
	$req = $db->prepare($sql);
	$req->execute($parameters);

	$products = $req->fetchAll();

	return $products;
}
function getImageProduct($idProduct) {
	global $db;
	$req = $db->prepare('SELECT product.image FROM product WHERE product.id = :idProduct');
	$req->execute(array(
		':idProduct' => $idProduct
	));

	$product = $req->fetch();
	return $product['image'];
}
function getTitleProduct($idProduct) {
	global $db;
	$req = $db->prepare('SELECT product.title FROM product WHERE product.id = :idProduct');
	$req->execute(array(
		':idProduct' => $idProduct
	));

	$product = $req->fetch();
	return $product['title'];
}
function getArtistProduct($idProduct) {
	global $db;
	$req = $db->prepare('SELECT product.id_artist FROM product WHERE product.id = :idProduct');
	$req->execute(array(
		':idProduct' => $idProduct
	));

	$product = $req->fetch();
	return $product['id_artist'];
}
function getPriceProduct($idProduct) {
	global $db;
	$req = $db->prepare('SELECT product.price FROM product WHERE product.id = :idProduct');
	$req->execute(array(
		':idProduct' => $idProduct
	));

	$product = $req->fetch();
	return $product['price'];
}
function addProduct($idArtist, $idCategory, $title, $slug, $price, $image, $editor, $date, $description, $stock) {
	global $db;
	$req = $db->prepare('INSERT INTO product(id, id_artist, id_category, title, slug, price, image, editor, date, description, in_stock)
		VALUES("", :idArtist, :idCategory, :title, :slug, :price, :image, :editor, :date, :description, :in_stock)');
	$req->execute(array(
		':idArtist' => $idArtist,
		':idCategory' => $idCategory,
		':title' => $title,
		':slug' => $slug,
		':price' => $price,
		':image' => $image,
		':editor' => $editor,
		':date' => $date,
		':description' => $description,
		':in_stock' => $stock
	));

	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function editProduct($idProduct, $idArtist, $idCategory, $title, $slug, $price, $image, $editor, $date, $description, $stock) {
	global $db;
	$req = $db->prepare('UPDATE product SET id_artist=:idArtist, id_category=:idCategory, title=:title, slug=:slug, price=:price, image=:image, editor=:editor, date=:date, description=:description, in_stock=:stock WHERE product.id = :idProduct');
	$req->execute(array(
		':idArtist' => $idArtist,
		':idCategory' => $idCategory,
		':title' => $title,
		':slug' => $slug,
		':price' => $price,
		':image' => $image,
		':editor' => $editor,
		':date' => $date,
		':description' => $description,
		':stock' => $stock,
		':idProduct' => $idProduct
	));

	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function removeProduct($idProduct) {
	global $db;
	$req = $db->prepare('DELETE FROM product WHERE product.id = :idProduct');
	$req->execute(array(
		':idProduct' => $idProduct
	));

	// Erreur lors de l'exécution de la requête
	if(!$req) {
		return false;
	}
	return true;
}
function isProduct($title, $idArtist, $idProduct = null) {
	global $db;

	$sql = 'SELECT product.id FROM product WHERE product.title = :title AND product.id_artist = :idArtist';
	$parameters = array(
		':title' => $title,
		':idArtist' => $idArtist
	);
	if($idProduct != null) {
		$sql .= ' AND product.id != :idProduct';
		$parameters[':idProduct'] = $idProduct;
	}

	$req = $db->prepare($sql);
	$req->execute($parameters);

	
	$product = $req->fetch();
	if(!empty($product)) {
		return true;
	}

	return false;
}
function isInStock($idProduct) {
	global $db;
	$req = $db->prepare('SELECT product.in_stock FROM product WHERE product.in_stock = 1 AND product.id = :idProduct');
	$req->execute(array(
		':idProduct' => $idProduct
	));

	$product = $req->fetch();
	if(empty($product)) {
		return false;
	}
	return true;
}
function getBestProducts() {
	global $db;
	$req = $db->prepare('SELECT SUM(order_detail.quantity) as total, product.* FROM product INNER JOIN order_detail ON product.id = order_detail.id_product GROUP BY product.id ORDER BY total LIMIT 0,6');
	$req->execute();
	$products = $req->fetchAll();

	return $products;
}
function getLastProducts() {
	global $db;
	$req = $db->prepare('SELECT * FROM product ORDER BY product.date DESC LIMIT 0,6');
	$req->execute();
	$products = $req->fetchAll();

	return $products;
}
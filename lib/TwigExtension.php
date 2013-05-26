<?php

// Catégories
$getNbProductsCategoryFunction = new Twig_SimpleFunction('getNbProductsCategory', function($idCategory) {
	return getNbProductsByCategory($idCategory);
});
$tpl->addFunction($getNbProductsCategoryFunction);

$getCategoryByIdFunction = new Twig_SimpleFunction('getCategoryById', function($idCategory) {
	return getCategoryById($idCategory);
});
$tpl->addFunction($getCategoryByIdFunction);

$getAllCategoriesFunction = new Twig_SimpleFunction('getAllCategories', function() {
	return getAllCategories();
});
$tpl->addFunction($getAllCategoriesFunction);

// Artistes
$getNbProductsArtistFunction = new Twig_SimpleFunction('getNbProductsArtist', function($idArtist) {
	return getNbProductsByArtist($idArtist);
});
$tpl->addFunction($getNbProductsArtistFunction);

$getArtistByIdFunction = new Twig_SimpleFunction('getArtistById', function ($idArtist) {
	return getArtistById($idArtist);
});
$tpl->addFunction($getArtistByIdFunction);

// Produits
$getProductByIdFunction = new Twig_SimpleFunction('getProductById', function ($idProduct) {
	return getProductById($idProduct);
});
$tpl->addFunction($getProductByIdFunction);

// Imagine
$resizeImageFunction = new Twig_SimpleFunction('resize', function($image, $width, $height, $mode) {
	return Image::resize($image, $width, $height, $mode);
});
$tpl->addFunction($resizeImageFunction);

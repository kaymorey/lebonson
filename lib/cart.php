<?php

function addToCart($idProduct) {
	if(isset($_SESSION['cart'][$idProduct])) {
		$_SESSION['cart'][$idProduct]++;
	}
	else {
		$_SESSION['cart'][$idProduct] = 1;
	}
}
function changeQuantityCart($idProduct, $quantity) {
	if(isset($_SESSION['cart'][$idProduct])) {
		$_SESSION['cart'][$idProduct] = $quantity;
	}
}
function removeFromCart($idProduct) {
	if(isset($_SESSION['cart'][$idProduct])) {
		unset($_SESSION['cart'][$idProduct]);
	}
}
function nbProductsCart() {
	$nbProductsCart = 0;
	foreach($_SESSION['cart'] as $product => $quantity) {
		$nbProductsCart += $quantity;
	}

	return $nbProductsCart;
}
function getAmountCart() {
	$amount = 0;
	foreach($_SESSION['cart'] as $product => $quantity) {
		$price = getPriceProduct($product);
		$amount += $price * $quantity;
	}

	return $amount;
}
function emptyCart() {
	$_SESSION['cart'] = null;
}
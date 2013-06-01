<?php
function addOrder($delivery, $billing) {
	$date = new DateTime();
	$amount = getAmountCart();

	global $db;
	$req = $db->prepare('INSERT INTO orders(id, id_delivery_address, id_billing_address, date, status, amount) VALUES("", :delivery, :billing, :date, "En préparation", :amount)');
	$req->execute(array(
		':delivery' => $delivery,
		':billing' => $billing,
		':date' => $date->format('Y-m-d H:i:s'),
		':amount' => $amount
	));
	if(!$req) {
		return false;
	}
	$order = getLastOrder();
	foreach($_SESSION['cart'] as $product => $quantity) {		
		$req = $db->prepare('INSERT INTO order_detail(id, quantity, id_product, id_order) VALUES("", :quantity, :idProduct, :idOrder)');
		$req->execute(array(
			':quantity' => $quantity,
			':idProduct' => $product,
			':idOrder' => $order
		));
	}
	if(!$req) {
		return false;
	}
	return true;
}
function getLastOrder() {
	global $db;
	$req = $db->prepare('SELECT orders.id FROM orders ORDER BY orders.id DESC');
	$req->execute();

	$order = $req->fetch();
	return $order['id'];
}
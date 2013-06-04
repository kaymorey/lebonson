<?php
function getAllOrders() {
	global $db;
	$req = $db->prepare('SELECT * FROM orders');
	$req->execute();

	$orders = $req->fetchAll();
	return $orders;
}
function isOrder($idOrder) {
	global $db;
	$req = $db->prepare('SELECT orders.id FROM orders WHERE orders.id = :idOrder');
	$req->execute(array(
		':idOrder' => $idOrder
	));
	$order = $req->fetch();

	if(empty($order)) {
		return false;
	}
	return true;
}
function addOrder($idCustomer, $delivery, $billing) {
	$date = new DateTime();
	$amount = getAmountCart();

	global $db;
	$req = $db->prepare('INSERT INTO orders(id, id_customer, id_delivery_address, id_billing_address, date, status, amount) VALUES("", :idCustomer, :delivery, :billing, :date, "En préparation", :amount)');
	$req->execute(array(
		':idCustomer' => $idCustomer,
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
function editOrder($idOrder, $status) {
	global $db;
	$req = $db->prepare('UPDATE orders SET status = :status WHERE orders.id = :idOrder');
	$req->execute(array(
		':status' => $status,
		':idOrder' => $idOrder 
	));

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
function getOrdersByCustomer($idCustomer) {
	global $db;
	$req = $db->prepare('SELECT orders.* FROM orders WHERE orders.id_customer = :idCustomer');
	$req->execute(array(
		':idCustomer' => $idCustomer
	));

	$orders = $req->fetchAll();
	return $orders;
}
function getOrderDetails($idOrder) {
	global $db;
	$req = $db->prepare('SELECT order_detail.* FROM order_detail WHERE order_detail.id_order = :idOrder');
	$req->execute(array(
		':idOrder' => $idOrder
	));

	$details = $req->fetchAll();
	return $details;
}
function getOrderById($idOrder) {
	global $db;
	$req = $db->prepare('SELECT * FROM orders WHERE orders.id = :idOrder');
	$req->execute(array(
		':idOrder' => $idOrder
	));

	$order = $req->fetch();
	return $order;
}
function getNbOrders() {
	global $db;
	$req = $db->prepare('SELECT count(orders.id) as nbOrders FROM orders');
	$req->execute();

	$result = $req->fetch();
	return $result['nbOrders'];
}
function getAverageAmountOrders() {
	global $db;
	$req = $db->prepare('SELECT ROUND(AVG(amount), 2) as average FROM orders');
	$req->execute();

	$result = $req->fetch();
	return $result['average'];
}
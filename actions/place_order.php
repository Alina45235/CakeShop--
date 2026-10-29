<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';

if (!is_logged_in()) redirect('/SHOP/login.php');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/SHOP/checkout.php');
csrf_check();

if (is_admin()) {
    flash('Администратор не может оформлять заказы');
    redirect('/SHOP/cart.php');
}

$cart = $_SESSION['cart'] ?? [];
if (!$cart) redirect('/SHOP/cart.php');

$name         = trim($_POST['name'] ?? '');
$phone        = trim($_POST['phone'] ?? '');
$address      = trim($_POST['address'] ?? '');
$comment      = trim($_POST['comment'] ?? '');
$deliveryDate = $_POST['delivery_date'] ?: null;

if (!$name || !$phone || !$address) {
    flash('Заполните обязательные поля');
    redirect('/SHOP/checkout.php');
}

try {
    $pdo->beginTransaction();

    $ids  = array_keys($cart);
    $in   = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($in) AND is_active = 1");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    if (!$products) {
        throw new Exception('Товары не найдены');
    }

    $total = 0;
    foreach ($products as $p) {
        $total += $p['price'] * $cart[$p['id']];
    }

    $stmt = $pdo->prepare("INSERT INTO orders
        (user_id, name, phone, address, comment, delivery_date, total, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'new')");
    $stmt->execute([
        $_SESSION['user_id'], $name, $phone, $address,
        $comment, $deliveryDate, $total
    ]);
    $orderId = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO order_items
        (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");
    foreach ($products as $p) {
        $stmt->execute([$orderId, $p['id'], $cart[$p['id']], $p['price']]);
    }

    $pdo->commit();
    unset($_SESSION['cart']);

    flash('Заказ оформлен');
    redirect('/SHOP/order.php?id=' . $orderId);

} catch (Exception $e) {
    $pdo->rollBack();
    flash('Ошибка оформления заказа');
    redirect('/SHOP/checkout.php');
}


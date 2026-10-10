<?php
session_start();
require __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/SHOP/');

csrf_check();

$id  = (int)($_POST['id'] ?? 0);
$qty = max(1, min(20, (int)($_POST['qty'] ?? 1)));

if ($id <= 0) redirect('/SHOP/');

$_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;

flash('Торт добавлен в корзину');
redirect($_SERVER['HTTP_REFERER'] ?? '/SHOP/cart.php');
<?php
session_start();
require __DIR__ . '/../includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    unset($_SESSION['cart'][$id]);
}

redirect('/SHOP/cart.php');
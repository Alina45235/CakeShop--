<?php
session_start();
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';

if (!is_logged_in() || !is_admin()) redirect('/SHOP/login.php');

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();

    if ($img && file_exists(__DIR__ . '/../uploads/' . $img)) {
        unlink(__DIR__ . '/../uploads/' . $img);
    }

    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    flash('Торт удалён');
}

redirect('/SHOP/admin/');
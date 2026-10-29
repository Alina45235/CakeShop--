<?php

function e($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function is_admin() {
    return ($_SESSION['role'] ?? '') === 'admin';
}

function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function cart_count() {
    return array_sum($_SESSION['cart'] ?? []);
}

function cart_total($pdo) {
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) return 0;

    $ids = array_keys($cart);
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($in)");
    $stmt->execute($ids);

    $total = 0;
    foreach ($stmt->fetchAll() as $p) {
        $total += $p['price'] * $cart[$p['id']];
    }
    return $total;
}

function flash($msg = null) {
    if ($msg !== null) {
        $_SESSION['flash'] = $msg;
        return null;
    }
    $m = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $m;
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_check() {
    $token = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
        http_response_code(403);
        exit('CSRF token mismatch');
    }
}

function status_label($status) {
    $labels = [
        'new'        => 'Новый',
        'confirmed'  => 'Подтверждён',
        'preparing'  => 'Готовится',
        'delivering' => 'В доставке',
        'done'       => 'Получен',
        'cancelled'  => 'Отменён',
    ];
    return $labels[$status] ?? $status;
}

function status_class($status) {
    return 'status status-' . $status;
}
function image_url($image) {
    if (!$image) return '/SHOP/assets/img/placeholder.jpg';
    if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
        return $image;
    }
    return '/SHOP/uploads/' . $image;
}
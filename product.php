<?php
require __DIR__ . '/includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, c.name AS cat_name
                       FROM products p
                       LEFT JOIN categories c ON c.id = p.category_id
                       WHERE p.id = ? AND p.is_active = 1");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    echo '<h1>Торт не найден</h1>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
?>

<a href="/SHOP/" style="color:#888; text-decoration:none;">← Назад в каталог</a>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px; margin-top:20px;">
    <div>
        <img src="<?= e(image_url($product['image'])) ?>"
             alt="<?= e($product['name']) ?>"
             style="width:100%; border-radius:12px;">
    </div>
    <div>
        <h1><?= e($product['name']) ?></h1>
        <?php if ($product['cat_name']): ?>
            <p style="color:#888;">Категория: <?= e($product['cat_name']) ?></p>
        <?php endif; ?>
        <p style="margin:20px 0;"><?= nl2br(e($product['description'])) ?></p>
        <?php if ($product['weight']): ?>
            <p>Вес: <?= (int)$product['weight'] ?> г</p>
        <?php endif; ?>
        <p class="card-price" style="font-size:28px; margin:20px 0;">
            <?= number_format($product['price'], 0, '.', ' ') ?> ₽
        </p>

        <form action="/SHOP/actions/add_to_cart.php" method="post" style="display:flex; gap:10px; align-items:center;">
            <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <input type="number" name="qty" value="1" min="1" max="20" style="width:80px; padding:10px; border:1px solid #ddd; border-radius:8px;">
            <button class="btn" type="submit">В корзину</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
<?php
require __DIR__ . '/includes/header.php';

$cat    = (int)($_GET['cat'] ?? 0);
$search = trim($_GET['q'] ?? '');
$min    = (int)($_GET['min'] ?? 0);
$max    = (int)($_GET['max'] ?? 0);

$where  = ["is_active = 1"];
$params = [];

if ($cat) {
    $where[]  = "category_id = ?";
    $params[] = $cat;
}
if ($search !== '') {
    $where[]  = "name LIKE ?";
    $params[] = "%$search%";
}
if ($min) {
    $where[]  = "price >= ?";
    $params[] = $min;
}
if ($max) {
    $where[]  = "price <= ?";
    $params[] = $max;
}

$sql  = "SELECT * FROM products WHERE " . implode(' AND ', $where) . " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<h1>Наши торты</h1>

<form method="get" class="filters">
    <input type="text" name="q" placeholder="Поиск..." value="<?= e($search) ?>">
    <select name="cat">
        <option value="">Все категории</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $cat == $c['id'] ? 'selected' : '' ?>>
                <?= e($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="number" name="min" placeholder="Цена от" value="<?= $min ?: '' ?>">
    <input type="number" name="max" placeholder="до" value="<?= $max ?: '' ?>">
    <button class="btn" type="submit">Найти</button>
</form>

<?php if (!$products): ?>
    <p>Ничего не найдено.</p>
<?php else: ?>
    <div class="grid">
        <?php foreach ($products as $p): ?>
            <div class="card">
                <a href="/SHOP/product.php?id=<?= $p['id'] ?>">
                    <img src="<?= e(image_url($p['image'])) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
                </a>
                <div class="card-body">
                    <h3><?= e($p['name']) ?></h3>
                    <div class="card-price"><?= number_format($p['price'], 0, '.', ' ') ?> ₽</div>
                    <a href="/SHOP/product.php?id=<?= $p['id'] ?>" class="btn">Подробнее</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
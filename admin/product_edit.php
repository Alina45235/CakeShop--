<?php
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/admin_auth.php';

$id = (int)($_GET['id'] ?? 0);

$product = [
    'name' => '', 'description' => '', 'price' => '',
    'weight' => '', 'category_id' => '', 'image' => '', 'is_active' => 1,
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if (!$product) redirect('/SHOP/admin/');
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $name  = trim($_POST['name'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $weight = (int)($_POST['weight'] ?? 0);
    $catId = (int)($_POST['category_id'] ?? 0) ?: null;
    $active = isset($_POST['is_active']) ? 1 : 0;
    $image = $product['image'] ?? null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $mime = mime_content_type($_FILES['image']['tmp_name']);

        if (isset($allowed[$mime])) {
            $ext = $allowed[$mime];
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            $dest = __DIR__ . '/../uploads/' . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                if ($image && file_exists(__DIR__ . '/../uploads/' . $image)) {
                    unlink(__DIR__ . '/../uploads/' . $image);
                }
                $image = $filename;
            }
        }
    }

    if (!$name || $price <= 0) {
        flash('Заполните название и цену');
    } else {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE products
                SET name=?, description=?, price=?, weight=?, category_id=?, image=?, is_active=?
                WHERE id=?");
            $stmt->execute([$name, $desc, $price, $weight, $catId, $image, $active, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO products
                (name, description, price, weight, category_id, image, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $desc, $price, $weight, $catId, $image, $active]);
        }
        flash('Сохранено');
        redirect('/SHOP/admin/');
    }
}
?>

<h1><?= $id ? 'Редактирование' : 'Новый торт' ?></h1>

<form method="post" enctype="multipart/form-data" style="max-width:600px;">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

    <div class="form-group">
        <label>Название *</label>
        <input type="text" name="name" required value="<?= e($product['name']) ?>">
    </div>
    <div class="form-group">
        <label>Описание</label>
        <textarea name="description" rows="4"><?= e($product['description']) ?></textarea>
    </div>
    <div class="form-group">
        <label>Цена, ₽ *</label>
        <input type="number" name="price" step="0.01" min="0" required value="<?= e($product['price']) ?>">
    </div>
    <div class="form-group">
        <label>Вес, г</label>
        <input type="number" name="weight" min="0" value="<?= e($product['weight']) ?>">
    </div>
    <div class="form-group">
        <label>Категория</label>
        <select name="category_id">
            <option value="">— без категории —</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $product['category_id'] == $c['id'] ? 'selected' : '' ?>>
                    <?= e($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Фото</label>
        <?php if ($product['image']): ?>
            <div style="margin-bottom:8px;">
                <img src="/SHOP/uploads/<?= e($product['image']) ?>" style="width:100px; border-radius:8px;">
            </div>
        <?php endif; ?>
        <input type="file" name="image" accept="image/*">
    </div>
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_active" <?= $product['is_active'] ? 'checked' : '' ?>>
            Активен
        </label>
    </div>

    <button class="btn" type="submit">Сохранить</button>
    <a href="/SHOP/admin/" class="btn btn-secondary">Отмена</a>
</form>

<?php require __DIR__ . '/../includes/footer.php'; ?>
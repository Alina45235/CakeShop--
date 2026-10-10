<?php
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/admin_auth.php';

$products = $pdo->query("SELECT p.*, c.name AS cat_name
                         FROM products p
                         LEFT JOIN categories c ON c.id = p.category_id
                         ORDER BY p.id DESC")->fetchAll();
?>

<div style="display:flex; justify-content:space-between; align-items:center;">
    <h1>Торты</h1>
    <a href="/SHOP/admin/product_edit.php" class="btn">+ Добавить</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Фото</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Цена</th>
            <th>Активен</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td>
                <?php if ($p['image']): ?>
                    <img src="<?= e(image_url($p['image'])) ?>" " style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                <?php endif; ?>
            </td>
            <td><?= e($p['name']) ?></td>
            <td><?= e($p['cat_name'] ?? '—') ?></td>
            <td><?= number_format($p['price'], 0, '.', ' ') ?> ₽</td>
            <td><?= $p['is_active'] ? 'Да' : 'Нет' ?></td>
            <td>
                <a href="/SHOP/admin/product_edit.php?id=<?= $p['id'] ?>" class="btn btn-sm">Ред.</a>
                <a href="/SHOP/admin/product_delete.php?id=<?= $p['id'] ?>"
                   class="btn btn-sm btn-danger"
                   data-confirm="Удалить торт?">×</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../includes/footer.php'; ?>
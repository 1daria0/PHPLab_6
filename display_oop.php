<?php
require_once 'classes/confessionStorage.php';

$storage = new ConfessionStorage(__DIR__ . '/data.txt');
$entries = $storage->loadAll();

// Сортировка
$sortField = $_GET['sort'] ?? 'created_timestamp';
$sortOrder = $_GET['order'] ?? 'desc';

$allowedSortFields = ['id', 'title', 'author', 'category', 'created_at', 'created_timestamp'];
if (!in_array($sortField, $allowedSortFields)) {
    $sortField = 'created_timestamp';
}

usort($entries, function($a, $b) use ($sortField, $sortOrder) {
    $valA = $a->$sortField ?? '';
    $valB = $b->$sortField ?? '';
    if ($sortOrder === 'asc') {
        return $valA <=> $valB;
    } else {
        return $valB <=> $valA;
    }
});

// Вспомогательная функция для ссылок сортировки
function getSortLink($field, $currentField, $currentOrder) {
    $newOrder = 'asc';
    if ($field === $currentField && $currentOrder === 'asc') {
        $newOrder = 'desc';
    }
    return "?sort=$field&order=$newOrder";
}

$categories = [
    'love' => 'Любовь',
    'friendship' => 'Дружба',
    'work' => 'Работа',
    'other' => 'Другое',
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все признания</title>
    <style>
        body { font-family: Arial; margin: 2rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; cursor: pointer; }
        th a { text-decoration: none; color: black; }
        .success { background: #d4edda; padding: 10px; margin-bottom: 10px; border: 1px solid #c3e6cb; }
        .btn { display: inline-block; margin-bottom: 1rem; padding: 0.5rem 1rem; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Все признания</h1>
    <?php if (isset($_GET['success'])): ?>
        <div class="success">Признание успешно добавлено!</div>
    <?php endif; ?>
    <a href="form_oop.php" class="btn">Добавить новое признание</a>

    <?php if (empty($entries)): ?>
        <p>Нет признаний. Будьте первым!</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th><a href="<?= getSortLink('id', $sortField, $sortOrder) ?>">ID</a></th>
                    <th><a href="<?= getSortLink('title', $sortField, $sortOrder) ?>">Заголовок</a></th>
                    <th>Текст</th>
                    <th><a href="<?= getSortLink('author', $sortField, $sortOrder) ?>">Автор</a></th>
                    <th><a href="<?= getSortLink('category', $sortField, $sortOrder) ?>">Категория</a></th>
                    <th><a href="<?= getSortLink('created_at', $sortField, $sortOrder) ?>">Дата</a></th>
                    <th>Анонимно</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $entry): ?>
                    <tr>
                        <td><?= htmlspecialchars($entry->id) ?></td>
                        <td><?= htmlspecialchars($entry->title) ?></td>
                        <td><?= nl2br(htmlspecialchars($entry->content)) ?></td>
                        <td>
                            <?php if ($entry->is_anonymous || empty($entry->author)): ?>
                                <em>Аноним</em>
                            <?php else: ?>
                                <?= htmlspecialchars($entry->author) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $categories[$entry->category] ?? $entry->category ?></td>
                        <td><?= htmlspecialchars($entry->created_at) ?></td>
                        <td><?= $entry->is_anonymous ? 'Да' : 'Нет' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
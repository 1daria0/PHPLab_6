<?php
require_once 'classes/ConfessionStorage.php';

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

function getSortLink($field, $currentField, $currentOrder) {
    $newOrder = 'asc';
    if ($field === $currentField && $currentOrder === 'asc') {
        $newOrder = 'desc';
    }
    return "?sort=$field&order=$newOrder";
}

$categories = [
    'love' => '💖 Любовь',
    'friendship' => '👫 Дружба',
    'work' => '💼 Работа',
    'other' => '✨ Другое',
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все признания</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 2rem;
            animation: fadeInDown 0.6s ease;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .btn-add {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 2rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .success {
            background: #4caf50;
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            text-align: center;
            animation: fadeIn 0.5s;
        }

        .sort-bar {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
        }

        .sort-link {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .sort-link:hover, .sort-link.active {
            background: white;
            color: #667eea;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            animation: fadeInUp 0.6s ease;
        }

        .card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 1.5rem;
        }

        .card-header h3 {
            font-size: 1.3rem;
            margin-bottom: 0.25rem;
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .card-content {
            padding: 1.5rem;
        }

        .confession-text {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        .card-footer {
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #666;
        }

        .empty {
            text-align: center;
            color: white;
            font-size: 1.2rem;
            padding: 3rem;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            .cards-grid {
                grid-template-columns: 1fr;
            }
            .sort-bar {
                gap: 0.5rem;
            }
            .sort-link {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 Все признания</h1>
            <p>Сердцу не прикажешь, но можно поделиться</p>
        </div>

        <div style="text-align: center;">
            <a href="form_oop.php" class="btn-add">✨ Добавить новое признание</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="success">✅ Признание успешно добавлено!</div>
        <?php endif; ?>

        <div class="sort-bar">
            <span style="color: white; align-self: center;">Сортировать по:</span>
            <a href="<?= getSortLink('created_timestamp', $sortField, $sortOrder) ?>" class="sort-link <?= $sortField === 'created_timestamp' ? 'active' : '' ?>">📅 Дате добавления</a>
            <a href="<?= getSortLink('title', $sortField, $sortOrder) ?>" class="sort-link <?= $sortField === 'title' ? 'active' : '' ?>">📝 Заголовку</a>
            <a href="<?= getSortLink('category', $sortField, $sortOrder) ?>" class="sort-link <?= $sortField === 'category' ? 'active' : '' ?>">🏷️ Категории</a>
            <a href="<?= getSortLink('created_at', $sortField, $sortOrder) ?>" class="sort-link <?= $sortField === 'created_at' ? 'active' : '' ?>">📅 Дате события</a>
            <a href="<?= getSortLink('author', $sortField, $sortOrder) ?>" class="sort-link <?= $sortField === 'author' ? 'active' : '' ?>">👤 Автору</a>
        </div>

        <?php if (empty($entries)): ?>
            <div class="empty">
                <p>😢 Пока нет ни одного признания. Будьте первым!</p>
                <a href="form_oop.php" style="color: white; margin-top: 1rem; display: inline-block;">Добавить признание →</a>
            </div>
        <?php else: ?>
            <div class="cards-grid">
                <?php foreach ($entries as $entry): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3><?= htmlspecialchars($entry->title) ?></h3>
                            <div class="card-meta">
                                <span><?= $categories[$entry->category] ?? $entry->category ?></span>
                                <span>#<?= $entry->id ?></span>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="confession-text">
                                <?= nl2br(htmlspecialchars($entry->content)) ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span>
                                <?php if ($entry->is_anonymous || empty($entry->author)): ?>
                                    👤 <em>Аноним</em>
                                <?php else: ?>
                                    👤 <?= htmlspecialchars($entry->author) ?>
                                <?php endif; ?>
                            </span>
                            <span>📅 <?= htmlspecialchars($entry->created_at) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
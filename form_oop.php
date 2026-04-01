<?php
session_start();

// Получаем старые данные и ошибки из сессии (если есть)
$old = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['form_errors'] ?? [];

// Очищаем сессионные данные после чтения
unset($_SESSION['old_input'], $_SESSION['form_errors']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новое признание</title>
    <style>
        body { font-family: Arial; margin: 2rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 0.5rem; box-sizing: border-box; }
        textarea { min-height: 100px; }
        button { padding: 0.5rem 1rem; background: #007bff; color: white; border: none; cursor: pointer; }
        .error { color: red; margin-bottom: 1rem; }
        .error-list { background: #f8d7da; padding: 0.5rem; border: 1px solid #f5c6cb; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Добавить признание</h1>

    <?php if (!empty($errors)): ?>
        <div class="error-list">
            <strong>Ошибки:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="process_oop.php" method="post">
        <div class="form-group">
            <label for="title">Заголовок *</label>
            <input type="text" id="title" name="title" required minlength="3" maxlength="100"
                   value="<?= htmlspecialchars($old['title'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="content">Признание *</label>
            <textarea id="content" name="content" required minlength="10"><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="author">Автор (оставьте пустым для анонимного)</label>
            <input type="text" id="author" name="author" maxlength="50"
                   value="<?= htmlspecialchars($old['author'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="category">Категория *</label>
            <select id="category" name="category" required>
                <option value="">-- Выберите --</option>
                <option value="love" <?= (($old['category'] ?? '') === 'love') ? 'selected' : '' ?>>Любовь</option>
                <option value="friendship" <?= (($old['category'] ?? '') === 'friendship') ? 'selected' : '' ?>>Дружба</option>
                <option value="work" <?= (($old['category'] ?? '') === 'work') ? 'selected' : '' ?>>Работа</option>
                <option value="other" <?= (($old['category'] ?? '') === 'other') ? 'selected' : '' ?>>Другое</option>
            </select>
        </div>
        <div class="form-group">
            <label for="created_at">Дата признания *</label>
            <input type="date" id="created_at" name="created_at" required
                   value="<?= htmlspecialchars($old['created_at'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_anonymous" value="1" <?= isset($old['is_anonymous']) ? 'checked' : '' ?>> Анонимно (не показывать имя автора)
            </label>
        </div>
        <button type="submit">Отправить</button>
    </form>
</body>
</html>
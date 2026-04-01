<?php
session_start();

$old = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['old_input'], $_SESSION['form_errors']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новое признание</title>
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
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .header p {
            opacity: 0.9;
        }

        .form-content {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        input, textarea, select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-group input {
            width: auto;
            margin-right: 0.5rem;
        }

        button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .error-list {
            background: #fee;
            border-left: 4px solid #f44336;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
        }

        .error-list ul {
            margin-left: 1.5rem;
            color: #d32f2f;
        }

        .error-list strong {
            color: #d32f2f;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            .header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💌 Тайное признание</h1>
            <p>Поделитесь тем, что на сердце</p>
        </div>
        <div class="form-content">
            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <strong>⚠️ Ошибки:</strong>
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
                    <label for="author">Автор (необязательно)</label>
                    <input type="text" id="author" name="author" maxlength="50"
                           value="<?= htmlspecialchars($old['author'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="category">Категория *</label>
                    <select id="category" name="category" required>
                        <option value="">-- Выберите --</option>
                        <option value="love" <?= (($old['category'] ?? '') === 'love') ? 'selected' : '' ?>>💖 Любовь</option>
                        <option value="friendship" <?= (($old['category'] ?? '') === 'friendship') ? 'selected' : '' ?>>👫 Дружба</option>
                        <option value="work" <?= (($old['category'] ?? '') === 'work') ? 'selected' : '' ?>>💼 Работа</option>
                        <option value="other" <?= (($old['category'] ?? '') === 'other') ? 'selected' : '' ?>>✨ Другое</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="created_at">Дата *</label>
                    <input type="date" id="created_at" name="created_at" required
                           value="<?= htmlspecialchars($old['created_at'] ?? '') ?>">
                </div>
                <div class="form-group checkbox-group">
                    <input type="checkbox" name="is_anonymous" value="1" id="anonymous" <?= isset($old['is_anonymous']) ? 'checked' : '' ?>>
                    <label for="anonymous" style="margin-bottom: 0;">🙈 Отправить анонимно</label>
                </div>
                <button type="submit">📨 Отправить признание</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
require_once 'validatorInterface.php';

/**
 * Валидатор для модели Confession
 */
class ConfessionValidator implements ValidatorInterface {
    private array $allowedCategories = ['love', 'friendship', 'work', 'other'];

    /**
     * {@inheritDoc}
     */
    public function validate(array $data): array {
        $errors = [];

        // Заголовок
        if (empty($data['title']) || strlen($data['title']) < 3 || strlen($data['title']) > 100) {
            $errors[] = 'Заголовок должен содержать от 3 до 100 символов.';
        }

        // Текст признания
        if (empty($data['content']) || strlen($data['content']) < 10) {
            $errors[] = 'Текст признания должен содержать не менее 10 символов.';
        }

        // Категория
        if (empty($data['category']) || !in_array($data['category'], $this->allowedCategories)) {
            $errors[] = 'Выберите корректную категорию.';
        }

        // Дата
        $date = DateTime::createFromFormat('Y-m-d', $data['created_at']);
        if (!$date || $date->format('Y-m-d') !== $data['created_at']) {
            $errors[] = 'Дата должна быть в формате ГГГГ-ММ-ДД.';
        }

        // Автор (необязательное, но не более 50 символов)
        if (isset($data['author']) && strlen($data['author']) > 50) {
            $errors[] = 'Имя автора не может быть длиннее 50 символов.';
        }

        return $errors;
    }
}
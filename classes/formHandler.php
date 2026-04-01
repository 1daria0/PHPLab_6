<?php
require_once 'confessionValidator.php';
require_once 'confessionStorage.php';

/**
 * Обработчик формы: принимает POST-данные, валидирует, сохраняет
 */
class FormHandler {
    private ConfessionValidator $validator;
    private ConfessionStorage $storage;

    /**
     * Конструктор
     *
     * @param ConfessionStorage $storage
     * @param ConfessionValidator $validator
     */
    public function __construct(ConfessionStorage $storage, ConfessionValidator $validator) {
        $this->storage = $storage;
        $this->validator = $validator;
    }

    /**
     * Обрабатывает запрос, возвращает массив с результатом
     *
     * @param array $postData Данные $_POST
     * @return array ['success' => bool, 'errors' => array, 'id' => int|null]
     */
    public function handle(array $postData): array {
        $data = [
            'title' => trim($postData['title'] ?? ''),
            'content' => trim($postData['content'] ?? ''),
            'author' => trim($postData['author'] ?? ''),
            'category' => $postData['category'] ?? '',
            'created_at' => $postData['created_at'] ?? '',
            'is_anonymous' => isset($postData['is_anonymous']) ? 1 : 0,
        ];

        $errors = $this->validator->validate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors, 'id' => null];
        }

        $confession = new Confession($data);
        $id = $this->storage->save($confession);
        return ['success' => true, 'errors' => [], 'id' => $id];
    }
}
<?php
/**
 * Модель данных "Признание"
 */
class Confession {
    public ?int $id = null;
    public string $title;
    public string $content;
    public string $author;
    public string $category;
    public string $created_at;
    public bool $is_anonymous;
    public int $created_timestamp;

    /**
     * Конструктор
     *
     * @param array $data Ассоциативный массив с данными признания
     */
    public function __construct(array $data) {
        $this->title = $data['title'] ?? '';
        $this->content = $data['content'] ?? '';
        $this->author = $data['author'] ?? '';
        $this->category = $data['category'] ?? '';
        $this->created_at = $data['created_at'] ?? '';
        $this->is_anonymous = (bool)($data['is_anonymous'] ?? false);
        $this->id = $data['id'] ?? null;
        $this->created_timestamp = $data['created_timestamp'] ?? time();
    }

    /**
     * Преобразует объект в массив для сохранения
     *
     * @return array
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author,
            'category' => $this->category,
            'created_at' => $this->created_at,
            'is_anonymous' => $this->is_anonymous ? 1 : 0,
            'created_timestamp' => $this->created_timestamp,
        ];
    }
}
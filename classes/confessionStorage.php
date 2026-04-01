<?php
require_once 'confession.php';

/**
 * Хранилище признаний в файле (JSON-строки)
 */
class ConfessionStorage {
    private string $filePath;

    /**
     * Конструктор
     *
     * @param string $filePath Путь к файлу данных
     */
    public function __construct(string $filePath) {
        $this->filePath = $filePath;
    }

    /**
     * Загружает все записи из файла
     *
     * @return Confession[]
     */
    public function loadAll(): array {
        $entries = [];
        if (file_exists($this->filePath)) {
            $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $data = json_decode($line, true);
                if ($data) {
                    $entries[] = new Confession($data);
                }
            }
        }
        return $entries;
    }

    /**
     * Сохраняет новое признание (добавляет в конец файла)
     *
     * @param Confession $confession Объект признания
     * @return int ID сохранённой записи
     */
    public function save(Confession $confession): int {
        // Получаем все записи для вычисления максимального ID
        $entries = $this->loadAll();
        $maxId = 0;
        foreach ($entries as $e) {
            if ($e->id > $maxId) {
                $maxId = $e->id;
            }
        }
        $confession->id = $maxId + 1;
        $confession->created_timestamp = time();

        $jsonLine = json_encode($confession->toArray(), JSON_UNESCAPED_UNICODE) . PHP_EOL;
        file_put_contents($this->filePath, $jsonLine, FILE_APPEND | LOCK_EX);
        return $confession->id;
    }
}
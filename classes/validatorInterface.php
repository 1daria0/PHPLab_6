<?php
/**
 * Интерфейс для валидаторов данных
 */
interface ValidatorInterface {
    /**
     * Проверяет данные и возвращает массив ошибок
     *
     * @param array $data Данные для валидации
     * @return array Массив сообщений об ошибках (пустой, если данные корректны)
     */
    public function validate(array $data): array;
}
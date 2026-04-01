<?php
require_once 'classes/confessionStorage.php';
require_once 'classes/confessionValidator.php';
require_once 'classes/formHandler.php';

session_start();

$storage = new ConfessionStorage(__DIR__ . '/data.txt');
$validator = new ConfessionValidator();
$handler = new FormHandler($storage, $validator);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $handler->handle($_POST);
    if ($result['success']) {
        header('Location: display_oop.php?success=1');
        exit;
    } else {
        // Сохраняем ошибки и старые данные в сессию для возврата на форму
        $_SESSION['form_errors'] = $result['errors'];
        $_SESSION['old_input'] = $_POST;
        header('Location: form_oop.php');
        exit;
    }
}
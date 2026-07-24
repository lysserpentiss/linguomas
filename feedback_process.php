<?php
session_start();
require_once 'db_connect.php';

// Получаем данные из формы
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$rating = (int)($_POST['rating'] ?? 0); // 0 — если не выбрано

// Проверка на пустые поля
if (empty($name)) {
    header("Location: feedback.php?error=Заполните все обязательные поля!");
    exit;
}

// Если рейтинг 0 — сохраняем NULL
$ratingValue = $rating > 0 ? $rating : NULL;
// Если email или сообщение пустые — сохраняем как NULL или пустую строку
$email = !empty($email) ? $email : NULL;
$message = !empty($message) ? $message : NULL;

// Сохраняем в БД
$sql = "INSERT INTO feedback (name, email, message, rating) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $message, $ratingValue);

if (mysqli_stmt_execute($stmt)) {
    header("Location: feedback.php?success=Спасибо за твой отзыв! ❤️");
    exit;
} else {
    header("Location: feedback.php?error=Ошибка сохранения. Попробуй ещё раз.");
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>
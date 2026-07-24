<?php
session_start();
require_once 'db_connect.php';

// Устанавливаем заголовок JSON
header('Content-Type: application/json');

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$word_id = (int)$_POST['word_id'];

if (!$word_id) {
    echo json_encode(['success' => false, 'error' => 'Не передан ID слова']);
    exit;
}

// Проверяем, есть ли уже такое слово в избранном
$check_sql = "SELECT id FROM favorites WHERE user_id = ? AND word_id = ?";
$check_stmt = mysqli_prepare($link, $check_sql);
mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $word_id);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if (mysqli_stmt_num_rows($check_stmt) > 0) {
    // Если уже есть — УДАЛЯЕМ
    $delete_sql = "DELETE FROM favorites WHERE user_id = ? AND word_id = ?";
    $delete_stmt = mysqli_prepare($link, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "ii", $user_id, $word_id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        echo json_encode(['success' => true, 'action' => 'removed']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Ошибка удаления: ' . mysqli_error($link)]);
    }
    mysqli_stmt_close($delete_stmt);
} else {
    // Если нет — ДОБАВЛЯЕМ
    $insert_sql = "INSERT INTO favorites (user_id, word_id) VALUES (?, ?)";
    $insert_stmt = mysqli_prepare($link, $insert_sql);
    mysqli_stmt_bind_param($insert_stmt, "ii", $user_id, $word_id);
    
    if (mysqli_stmt_execute($insert_stmt)) {
        echo json_encode(['success' => true, 'action' => 'added']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Ошибка добавления: ' . mysqli_error($link)]);
    }
    mysqli_stmt_close($insert_stmt);
}
mysqli_stmt_close($check_stmt);
// ===== ОБНОВЛЕНИЕ ПРОГРЕССА ПОСЛЕ ДОБАВЛЕНИЯ/УДАЛЕНИЯ =====
// Пересчитываем общее количество слов в избранном
$count_sql = "SELECT COUNT(*) as total FROM favorites WHERE user_id = ?";
$count_stmt = mysqli_prepare($link, $count_sql);
mysqli_stmt_bind_param($count_stmt, "i", $user_id);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$count_row = mysqli_fetch_assoc($count_result);
$total_words = $count_row['total'] ?? 0;
mysqli_stmt_close($count_stmt);

// Обновляем поле words_learned в таблице progress
$update_sql = "UPDATE progress SET words_learned = ? WHERE user_id = ?";
$update_stmt = mysqli_prepare($link, $update_sql);
mysqli_stmt_bind_param($update_stmt, "ii", $total_words, $user_id);
mysqli_stmt_execute($update_stmt);
mysqli_stmt_close($update_stmt);
mysqli_close($link);
?>
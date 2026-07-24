<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

// Проверяем авторизацию 
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Не авторизован']);
    exit;
}

$id = (int)$_POST['id'];

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Не передан ID']);
    exit;
}

$sql = "DELETE FROM feedback WHERE id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($link)]);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>
<?php
session_start();
header('Content-Type: application/json');

$response = ['logged_in' => false];
if (isset($_SESSION['user_id'])) {
    $response['logged_in'] = true;
    $response['user_id'] = (int)$_SESSION['user_id'];
    $response['username'] = $_SESSION['username'] ?? 'Пользователь';
}

echo json_encode($response);
exit;
?>
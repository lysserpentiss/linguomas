<?php
session_start();
require_once 'db_connect.php';

$username = trim($_POST['username']);
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    header("Location: login.html?error=Заполните все поля!");
    exit;
}

// Ищем пользователя
$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    // Проверяем пароль
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: profile.php");
        exit;
    }
}

// Если логин или пароль неверные
header("Location: login.html?error=Неверный логин или пароль!");
exit;

header("Location: profile.php");
exit;

mysqli_stmt_close($stmt);
mysqli_close($link);
?>
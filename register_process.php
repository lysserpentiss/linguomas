<?php
session_start();
require_once 'db_connect.php';

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];

// Проверка на пустые поля
if (empty($username) || empty($email) || empty($password)) {
    header("Location: login.html?error=Заполните все поля!");
    exit;
}

// Хешируем пароль
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Проверяем, не занят ли логин или email
$check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
$check_stmt = mysqli_prepare($link, $check_sql);
mysqli_stmt_bind_param($check_stmt, "ss", $username, $email);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if (mysqli_stmt_num_rows($check_stmt) > 0) {
    header("Location: login.html?error=Пользователь с таким логином или email уже существует!");
    exit;
}
mysqli_stmt_close($check_stmt);

// Вставляем нового пользователя
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

if (mysqli_stmt_execute($stmt)) {
    // Автоматически входим после регистрации
    $user_id = mysqli_insert_id($link);
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
	
	// Создаём запись о прогрессе для пользователя
    $progress_sql = "INSERT INTO progress (user_id, language, words_learned, days_streak) VALUES (?, 'es', 0, 0)";
    $progress_stmt = mysqli_prepare($link, $progress_sql);
    mysqli_stmt_bind_param($progress_stmt, "i", $user_id);
    mysqli_stmt_execute($progress_stmt);
    mysqli_stmt_close($progress_stmt);
    
    header("Location: profile.php");
    exit;
} else {
    header("Location: login.html?error=Ошибка регистрации. Попробуйте снова.");
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>

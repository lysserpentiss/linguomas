<?php
$link = mysqli_connect("localhost", "root", "", "linguomas");
if (!$link) {
    die("Ошибка подключения к БД: " . mysqli_connect_error());
}
mysqli_set_charset($link, "utf8mb4");
?>
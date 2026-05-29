<?php
// Файл: logout.php
// Назначение: Выход пользователя из системы

session_start();

$_SESSION = [];

session_destroy();

header('Location: login.php');
exit();

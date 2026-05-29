<?php
// Файл: login_handler.php
// Назначение: Обработка данных формы авторизации

session_start();

require_once 'php/user_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($login) || empty($password)) {
        $_SESSION['login_error'] = 'Заполните все поля формы';
        header('Location: login.php');
        exit();
    }

    $user = findUserByLogin($login);

    if (!$user) {
        $_SESSION['login_error'] = 'Неверный логин или пароль';
        header('Location: login.php');
        exit();
    }

    $passwordValid = password_verify($password, $user['password']);
    if (!$passwordValid && hash_equals((string) $user['password'], $password)) {
        $passwordValid = true;
    }

    if ($passwordValid) {
        if ($login === 'Admin' && $password !== 'KorokNET') {
            $_SESSION['login_error'] = 'Неверный пароль для администратора';
            header('Location: login.php');
            exit();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['user_full_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'login' => $user['login'],
            'full_name' => $user['full_name'],
            'role' => $user['role'],
        ];

        unset($_SESSION['login_error']);

        if ($user['role'] === 'admin') {
            header('Location: admin/dashboard.php');
            exit();
        }

        header('Location: my_applications.php');
        exit();
    }

    $_SESSION['login_error'] = 'Неверный логин или пароль';
    header('Location: login.php');
    exit();
}

header('Location: login.php');
exit();

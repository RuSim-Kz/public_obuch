<?php
// Файл: register_handler.php
// Назначение: Обработка данных формы регистрации

session_start();

require_once 'php/user_functions.php';

function validateRegistrationData($login, $password, $confirm_password, $full_name, $phone, $email)
{
    $errors = [];

    if (strlen($login) < 6) {
        $errors[] = 'Логин должен содержать не менее 6 символов';
    }
    if (!preg_match('/^[a-zA-Z0-9]+$/', $login)) {
        $errors[] = 'Логин может содержать только латинские буквы и цифры';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Пароль должен содержать не менее 8 символов';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Пароли не совпадают';
    }

    if (!preg_match('/^[а-яА-ЯёЁ\s]+$/u', $full_name)) {
        $errors[] = 'ФИО может содержать только буквы кириллицы и пробелы';
    }

    if (!preg_match('/^8\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $phone)) {
        $errors[] = 'Телефон должен быть в формате 8(XXX)XXX-XX-XX';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Введите корректный email адрес';
    }

    if (userExists($login)) {
        $errors[] = 'Пользователь с таким логином уже существует';
    }

    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login            = $_POST['login'] ?? '';
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name        = $_POST['full_name'] ?? '';
    $phone            = $_POST['phone'] ?? '';
    $email            = $_POST['email'] ?? '';

    $errors = validateRegistrationData($login, $password, $confirm_password, $full_name, $phone, $email);

    if (!empty($errors)) {
        $_SESSION['registration_errors'] = $errors;
        $_SESSION['old_data'] = [
            'login'     => $login,
            'full_name' => $full_name,
            'phone'     => $phone,
            'email'     => $email,
        ];
        header('Location: register.php');
        exit();
    }

    $result = registerUser($login, $password, $full_name, $phone, $email);

    if ($result === true) {
        $_SESSION['registration_success'] = 'Регистрация прошла успешно! Теперь вы можете войти в систему.';
        header('Location: login.php');
        exit();
    }

    $_SESSION['registration_errors'] = [$result['error']];
    $_SESSION['old_data'] = [
        'login'     => $login,
        'full_name' => $full_name,
        'phone'     => $phone,
        'email'     => $email,
    ];
    header('Location: register.php');
    exit();
}

header('Location: register.php');
exit();

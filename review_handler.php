<?php
// Файл: review_handler.php
// Назначение: Обработка добавления отзыва к заявке

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'php/application_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = (int) ($_POST['application_id'] ?? 0);
    $review = trim($_POST['review'] ?? '');
    $user_id = $_SESSION['user_id'];

    if ($review === '') {
        $_SESSION['application_error'] = 'Отзыв не может быть пустым';
        header('Location: my_applications.php');
        exit();
    }

    if (strlen($review) > 500) {
        $_SESSION['application_error'] = 'Отзыв не может быть длиннее 500 символов';
        header('Location: my_applications.php');
        exit();
    }

    if (!canAddReview($application_id, $user_id)) {
        $_SESSION['application_error'] = 'Вы не можете оставить отзыв к этой заявке';
        header('Location: my_applications.php');
        exit();
    }

    $result = addReview($application_id, $review);

    if ($result === true) {
        $_SESSION['application_success'] = 'Спасибо! Ваш отзыв сохранен.';
    } else {
        $_SESSION['application_error'] = $result['error'];
    }

    header('Location: my_applications.php');
    exit();
}

header('Location: my_applications.php');
exit();

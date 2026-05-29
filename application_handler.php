<?php
// Файл: application_handler.php
// Назначение: Обработка данных формы создания заявки

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'php/application_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = trim($_POST['course_name'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $user_id = $_SESSION['user_id'];

    $_SESSION['old_application_data'] = [
        'course_name' => $course_name,
        'start_date' => $start_date,
        'payment_method' => $payment_method,
    ];

    $errors = [];

    if ($course_name === '') {
        $errors[] = 'Укажите наименование курса';
    }
    if ($start_date === '') {
        $errors[] = 'Укажите желаемую дату начала';
    } elseif ($start_date < date('Y-m-d')) {
        $errors[] = 'Дата начала не может быть в прошлом';
    }
    if ($payment_method === '') {
        $errors[] = 'Выберите способ оплаты';
    }

    if (!empty($errors)) {
        $_SESSION['application_error'] = implode('<br>', $errors);
        header('Location: new_application.php');
        exit();
    }

    $result = createApplication($user_id, $course_name, $start_date, (int) $payment_method);

    if ($result === true) {
        unset($_SESSION['old_application_data']);
        $_SESSION['application_success'] = 'Заявка успешно создана!';
        header('Location: my_applications.php');
        exit();
    }

    $_SESSION['application_error'] = $result['error'];
    header('Location: new_application.php');
    exit();
}

header('Location: new_application.php');
exit();

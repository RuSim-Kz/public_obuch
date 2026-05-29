<?php
// Файл: admin/update_status.php
// Назначение: Обработчик изменения статуса заявки

session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}

require_once '../php/application_functions.php';

function buildDashboardRedirect(): string
{
    $params = [];

    $status = $_POST['redirect_status'] ?? $_GET['status'] ?? '';
    $search = $_POST['redirect_search'] ?? $_GET['search'] ?? '';
    $page = $_POST['redirect_page'] ?? $_GET['page'] ?? '';

    if ($status !== '' && $status !== 'all') {
        $params[] = 'status=' . urlencode($status);
    }
    if ($search !== '') {
        $params[] = 'search=' . urlencode($search);
    }
    if ($page !== '' && (int) $page > 0) {
        $params[] = 'page=' . (int) $page;
    }

    $redirect = 'dashboard.php';
    if (!empty($params)) {
        $redirect .= '?' . implode('&', $params);
    }

    return $redirect;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = (int) ($_POST['application_id'] ?? 0);
    $new_status = $_POST['status'] ?? '';

    if (!$application_id || $new_status === '') {
        $_SESSION['admin_error'] = 'Не указан ID заявки или статус';
        header('Location: ' . buildDashboardRedirect());
        exit();
    }

    $result = updateApplicationStatus($application_id, $new_status);

    if ($result === true) {
        $_SESSION['admin_success'] = "Статус заявки #$application_id успешно изменен на '$new_status'";
    } else {
        $_SESSION['admin_error'] = $result['error'];
    }

    header('Location: ' . buildDashboardRedirect());
    exit();
}

header('Location: dashboard.php');
exit();

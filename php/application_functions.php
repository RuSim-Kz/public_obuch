<?php
// Файл: php/application_functions.php
// Назначение: Функции для работы с заявками

require_once 'config.php';

/**
 * @return true|array
 */
function createApplication($user_id, $course_name, $start_date, $payment_method_id)
{
    global $db_conn;

    if (!$db_conn) {
        return ['error' => 'Ошибка подключения к базе данных'];
    }

    $user_id = (int) $user_id;
    $payment_method_id = (int) $payment_method_id;
    $course_name = pg_escape_string($db_conn, $course_name);
    $start_date = pg_escape_string($db_conn, $start_date);
    $status = 'Новая';

    $query = "INSERT INTO applications (user_id, course_name, desired_start_date, payment_method_id, status)
              VALUES ($user_id, '$course_name', '$start_date', $payment_method_id, '$status')";

    $result = pg_query($db_conn, $query);

    if ($result) {
        return true;
    }

    $error = pg_last_error($db_conn);
    return ['error' => 'Ошибка при создании заявки: ' . $error];
}

function getUserApplications($user_id)
{
    global $db_conn;

    $applications = [];

    if (!$db_conn) {
        return $applications;
    }

    $user_id = (int) $user_id;

    $query = "SELECT
                a.id,
                a.course_name,
                a.desired_start_date,
                a.status,
                a.created_at,
                a.review,
                pm.name as payment_method_name
              FROM applications a
              JOIN payment_methods pm ON a.payment_method_id = pm.id
              WHERE a.user_id = $user_id
              ORDER BY a.created_at DESC";

    $result = pg_query($db_conn, $query);

    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $applications[] = $row;
        }
        pg_free_result($result);
    }

    return $applications;
}

function getAllApplications()
{
    global $db_conn;

    $applications = [];

    if (!$db_conn) {
        return $applications;
    }

    $query = "SELECT
                a.id,
                a.course_name,
                a.desired_start_date,
                a.status,
                a.created_at,
                a.review,
                u.id as user_id,
                u.full_name as user_name,
                u.login as user_login,
                u.email as user_email,
                pm.name as payment_method_name
              FROM applications a
              JOIN users u ON a.user_id = u.id
              JOIN payment_methods pm ON a.payment_method_id = pm.id
              ORDER BY a.created_at DESC";

    $result = pg_query($db_conn, $query);

    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $applications[] = $row;
        }
        pg_free_result($result);
    }

    return $applications;
}

/**
 * Заявки с фильтрацией и пагинацией (pg_query_params).
 */
function getAllApplicationsFiltered($status_filter = '', $search = '', $page = 1, $items_per_page = 10)
{
    global $db_conn;

    $applications = [];

    if (!$db_conn) {
        return $applications;
    }

    $page = max(1, (int) $page);
    $items_per_page = max(1, (int) $items_per_page);
    $offset = ($page - 1) * $items_per_page;

    $query = "SELECT
                a.id,
                a.course_name,
                a.desired_start_date,
                a.status,
                a.created_at,
                a.review,
                u.id AS user_id,
                u.full_name AS user_name,
                u.login AS user_login,
                u.email AS user_email,
                pm.name AS payment_method_name
              FROM applications a
              JOIN users u ON a.user_id = u.id
              JOIN payment_methods pm ON a.payment_method_id = pm.id";

    $conditions = [];
    $params = [];
    $param_count = 1;

    if ($status_filter !== '' && in_array($status_filter, ['Новая', 'Идет обучение', 'Обучение завершено'], true)) {
        $conditions[] = 'a.status = $' . $param_count;
        $params[] = $status_filter;
        $param_count++;
    }

    if ($search !== '') {
        $conditions[] = '(u.full_name ILIKE $' . $param_count . ' OR a.course_name ILIKE $' . $param_count . ')';
        $params[] = '%' . $search . '%';
        $param_count++;
    }

    if (!empty($conditions)) {
        $query .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $query .= ' ORDER BY a.created_at DESC';
    $query .= ' LIMIT $' . $param_count . ' OFFSET $' . ($param_count + 1);
    $params[] = $items_per_page;
    $params[] = $offset;

    $result = pg_query_params($db_conn, $query, $params);

    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $applications[] = $row;
        }
        pg_free_result($result);
    }

    return $applications;
}

/**
 * Общее количество заявок с учётом фильтров.
 */
function getTotalApplicationsCount($status_filter = '', $search = '')
{
    global $db_conn;

    if (!$db_conn) {
        return 0;
    }

    $query = "SELECT COUNT(*) AS count
              FROM applications a
              JOIN users u ON a.user_id = u.id";

    $conditions = [];
    $params = [];
    $param_count = 1;

    if ($status_filter !== '' && in_array($status_filter, ['Новая', 'Идет обучение', 'Обучение завершено'], true)) {
        $conditions[] = 'a.status = $' . $param_count;
        $params[] = $status_filter;
        $param_count++;
    }

    if ($search !== '') {
        $conditions[] = '(u.full_name ILIKE $' . $param_count . ' OR a.course_name ILIKE $' . $param_count . ')';
        $params[] = '%' . $search . '%';
        $param_count++;
    }

    if (!empty($conditions)) {
        $query .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $result = pg_query_params($db_conn, $query, $params);

    if ($result) {
        $row = pg_fetch_assoc($result);
        pg_free_result($result);

        return (int) ($row['count'] ?? 0);
    }

    return 0;
}

/**
 * @return true|array
 */
function updateApplicationStatus($application_id, $new_status)
{
    global $db_conn;

    if (!$db_conn) {
        return ['error' => 'Ошибка подключения к базе данных'];
    }

    $application_id = (int) $application_id;
    $new_status = pg_escape_string($db_conn, $new_status);

    $allowed = ['Новая', 'Идет обучение', 'Обучение завершено'];
    if (!in_array($new_status, $allowed, true)) {
        return ['error' => 'Недопустимый статус'];
    }

    $query = "UPDATE applications SET status = '$new_status' WHERE id = $application_id";
    $result = pg_query($db_conn, $query);

    if ($result) {
        return true;
    }

    $error = pg_last_error($db_conn);
    return ['error' => 'Ошибка при обновлении статуса: ' . $error];
}

/**
 * @return true|array
 */
function addReview($application_id, $review)
{
    global $db_conn;

    if (!$db_conn) {
        return ['error' => 'Ошибка подключения к базе данных'];
    }

    $application_id = (int) $application_id;
    $review = pg_escape_string($db_conn, $review);

    $query = "UPDATE applications SET review = '$review' WHERE id = $application_id";
    $result = pg_query($db_conn, $query);

    if ($result) {
        return true;
    }

    $error = pg_last_error($db_conn);
    return ['error' => 'Ошибка при добавлении отзыва: ' . $error];
}

function getPaymentMethods()
{
    global $db_conn;

    $methods = [];

    if (!$db_conn) {
        return $methods;
    }

    $query = 'SELECT * FROM payment_methods ORDER BY id';
    $result = pg_query($db_conn, $query);

    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $methods[] = $row;
        }
        pg_free_result($result);
    }

    return $methods;
}

function canAddReview($application_id, $user_id)
{
    global $db_conn;

    if (!$db_conn) {
        return false;
    }

    $application_id = (int) $application_id;
    $user_id = (int) $user_id;

    $query = "SELECT id FROM applications
              WHERE id = $application_id
                AND user_id = $user_id
                AND status = 'Обучение завершено'
                AND (review IS NULL OR review = '')";

    $result = pg_query($db_conn, $query);

    return ($result && pg_num_rows($result) > 0);
}

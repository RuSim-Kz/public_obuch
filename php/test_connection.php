<?php
// Файл: test_connection.php
// Назначение: Проверка подключения к БД и вывод данных из таблицы users

require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест подключения к БД</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        h1 {
            color: #333;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Тест подключения к базе данных «Корочки.есть»</h1>

<?php
if (!$db_conn) {
    echo '<div class="error"><strong>Ошибка:</strong> Подключение к базе данных отсутствует.</div>';
} else {
    echo '<div class="success"><strong>Успех:</strong> Подключение к базе данных установлено!</div>';

    $query = 'SELECT id, login, full_name, phone, email, role, created_at FROM users ORDER BY id';
    $result = pg_query($db_conn, $query);

    if (!$result) {
        echo '<div class="error"><strong>Ошибка запроса:</strong> ' . htmlspecialchars(pg_last_error($db_conn)) . '</div>';
    } else {
        $rows_count = pg_num_rows($result);

        if ($rows_count > 0) {
            echo '<table>';
            echo '<thead><tr>';
            echo '<th>ID</th><th>Логин</th><th>ФИО</th><th>Телефон</th><th>Email</th><th>Роль</th><th>Дата регистрации</th>';
            echo '</tr></thead><tbody>';

            while ($row = pg_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['login']) . '</td>';
                echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . htmlspecialchars($row['role']) . '</td>';
                echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<div class="error">В таблице users нет данных.</div>';
        }

        pg_free_result($result);
    }

    echo '<h2>Проверка связанных таблиц</h2>';

    $query_pm = 'SELECT * FROM payment_methods';
    $result_pm = pg_query($db_conn, $query_pm);

    if ($result_pm && pg_num_rows($result_pm) > 0) {
        echo '<h3>Способы оплаты:</h3>';
        echo '<ul>';

        while ($row = pg_fetch_assoc($result_pm)) {
            echo '<li>' . htmlspecialchars($row['id']) . ' — ' . htmlspecialchars($row['name']) . '</li>';
        }

        echo '</ul>';
        pg_free_result($result_pm);
    }

    $query_app = 'SELECT COUNT(*) as count FROM applications';
    $result_app = pg_query($db_conn, $query_app);
    $app_count = pg_fetch_assoc($result_app)['count'];
    echo '<p>Количество заявок в базе: <strong>' . htmlspecialchars($app_count) . '</strong></p>';
    pg_free_result($result_app);

    pg_close($db_conn);
}
?>
    <hr>
    <p><strong>Информация о подключении:</strong></p>
    <ul>
        <li>Хост: <?php echo htmlspecialchars($host); ?></li>
        <li>Порт: <?php echo htmlspecialchars($port); ?></li>
        <li>База данных: <?php echo htmlspecialchars($dbname); ?></li>
        <li>Пользователь: <?php echo htmlspecialchars($user); ?></li>
    </ul>
</body>
</html>

<?php
// Скопируйте в config.php и укажите свои данные PostgreSQL

$host     = 'localhost';
$port     = '5432';
$dbname   = 'korochki_portal';
$user     = 'postgres';
$password = 'ВАШ_ПАРОЛЬ';

$connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

$db_conn = pg_connect($connection_string);

if (!$db_conn) {
    die('Ошибка подключения к базе данных: ' . pg_last_error());
}

pg_set_client_encoding($db_conn, 'UTF8');

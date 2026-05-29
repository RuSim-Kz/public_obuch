-- Задание 3.2. Создание таблиц для базы данных korochki_portal
-- Выполнить в Query Tool pgAdmin, подключившись к базе korochki_portal

-- Скрипт 1: Создание таблицы users
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    login VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Скрипт 2: Создание таблицы payment_methods
CREATE TABLE payment_methods (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- Скрипт 3: Создание таблицы applications
CREATE TABLE applications (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    course_name VARCHAR(200) NOT NULL,
    desired_start_date DATE NOT NULL,
    payment_method_id INTEGER NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Новая',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    review TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE RESTRICT
);

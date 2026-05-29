-- Задание 3.4. Тестовые пользователи
INSERT INTO users (login, password, full_name, phone, email, role) VALUES
('Admin', 'KorokNET', 'Администратор Системы', '8(999)111-22-33', 'admin@korochki.ru', 'admin');

INSERT INTO users (login, password, full_name, phone, email, role) VALUES
('ivanov', 'password123', 'Иванов Иван Иванович', '8(999)222-33-44', 'ivanov@mail.ru', 'user');

INSERT INTO users (login, password, full_name, phone, email, role) VALUES
('petrova', 'qwerty123', 'Петрова Анна Сергеевна', '8(999)333-44-55', 'petrova@yandex.ru', 'user');

-- Заявки
INSERT INTO applications (user_id, course_name, desired_start_date, payment_method_id, status) VALUES
(2, 'Основы программирования на Python', '2024-10-01', 1, 'Новая');

INSERT INTO applications (user_id, course_name, desired_start_date, payment_method_id, status) VALUES
(2, 'Веб-разработка для начинающих', '2024-09-15', 2, 'Идет обучение');

INSERT INTO applications (user_id, course_name, desired_start_date, payment_method_id, status) VALUES
(3, 'Английский для IT-специалистов', '2024-10-10', 1, 'Новая');

INSERT INTO applications (user_id, course_name, desired_start_date, payment_method_id, status, review) VALUES
(3, 'Excel для работы с данными', '2024-08-01', 2, 'Обучение завершено', 'Отличный курс, все понятно и доступно!');

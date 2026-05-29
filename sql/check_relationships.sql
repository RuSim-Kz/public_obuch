-- Шаг 3. Проверка связей
SELECT
    a.id,
    u.full_name AS user_name,
    a.course_name,
    a.desired_start_date,
    pm.name AS payment_method,
    a.status,
    a.review
FROM applications a
JOIN users u ON a.user_id = u.id
JOIN payment_methods pm ON a.payment_method_id = pm.id;

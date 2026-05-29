<?php
// Файл: includes/header.php
// Назначение: Общий шаблон шапки сайта

if (!isset($root)) {
    $root = '';
}
if (!isset($page_title)) {
    $page_title = 'Корочки.есть';
}

$current_page = basename($_SERVER['PHP_SELF']);
$asset_base = '/korochki_project/';
$is_logged_in = isset($_SESSION['user_id']);
$user_role = $_SESSION['user_role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $asset_base; ?>css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="<?php echo $root; ?>index.php" class="logo">
                    <i class="fas fa-graduation-cap"></i> Корочки.<span>есть</span>
                </a>
                <nav class="nav-menu">
                    <?php if ($is_logged_in): ?>
                    <a href="<?php echo $root; ?>index.php" class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i> Главная
                    </a>
                    <a href="<?php echo $root; ?>my_applications.php" class="<?php echo $current_page === 'my_applications.php' ? 'active' : ''; ?>">
                        <i class="fas fa-list"></i> Мои заявки
                    </a>
                    <a href="<?php echo $root; ?>new_application.php" class="<?php echo $current_page === 'new_application.php' ? 'active' : ''; ?>">
                        <i class="fas fa-plus-circle"></i> Новая заявка
                    </a>
                    <?php if ($user_role === 'admin'): ?>
                    <a href="<?php echo $root; ?>admin/dashboard.php" class="<?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i> Админ панель
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo $root; ?>logout.php">
                        <i class="fas fa-sign-out-alt"></i> Выход
                    </a>
                    <?php else: ?>
                    <a href="<?php echo $root; ?>index.php" class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i> Главная
                    </a>
                    <a href="<?php echo $root; ?>login.php" class="<?php echo $current_page === 'login.php' ? 'active' : ''; ?>">
                        <i class="fas fa-sign-in-alt"></i> Вход
                    </a>
                    <a href="<?php echo $root; ?>register.php" class="<?php echo $current_page === 'register.php' ? 'active' : ''; ?>">
                        <i class="fas fa-user-plus"></i> Регистрация
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">

<?php
session_start();

$page_title = 'Вход - Корочки.есть';
$success = $_SESSION['registration_success'] ?? '';
$error = $_SESSION['login_error'] ?? '';
$old_login = $_SESSION['old_login'] ?? '';

unset($_SESSION['registration_success']);
unset($_SESSION['login_error']);
unset($_SESSION['old_login']);

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <h1 class="text-center mb-4">Вход в систему</h1>

            <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="login_handler.php" method="POST">
                <div class="form-group">
                    <label for="login">Логин</label>
                    <input type="text" class="form-control" id="login" name="login" required
                           placeholder="Введите ваш логин"
                           value="<?php echo htmlspecialchars($old_login); ?>">
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required
                           placeholder="Введите ваш пароль">
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-sign-in-alt"></i> Войти
                </button>

                <p class="text-center mb-0">Еще не зарегистрированы? <a href="register.php">Создать аккаунт</a></p>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

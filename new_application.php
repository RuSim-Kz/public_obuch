<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'php/application_functions.php';

$page_title = 'Новая заявка - Корочки.есть';
$payment_methods = getPaymentMethods();
$error = $_SESSION['application_error'] ?? '';
$old_data = $_SESSION['old_application_data'] ?? [];

unset($_SESSION['application_error']);
unset($_SESSION['old_application_data']);

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <h1 class="text-center mb-4">Создание новой заявки на обучение</h1>

            <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="application_handler.php" method="POST">
                <div class="form-group">
                    <label for="course_name">Наименование курса</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" required
                           placeholder="Введите название курса"
                           value="<?php echo htmlspecialchars($old_data['course_name'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="start_date">Желаемая дата начала обучения</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required
                           value="<?php echo htmlspecialchars($old_data['start_date'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Способ оплаты</label>
                    <div class="d-flex flex-wrap gap-3 mt-2">
                        <?php foreach ($payment_methods as $method): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                   id="payment_<?php echo (int) $method['id']; ?>"
                                   name="payment_method" value="<?php echo (int) $method['id']; ?>"
                                   <?php echo ((string) ($old_data['payment_method'] ?? '') === (string) $method['id']) ? 'checked' : ''; ?>
                                   required>
                            <label class="form-check-label" for="payment_<?php echo (int) $method['id']; ?>">
                                <?php echo htmlspecialchars($method['name']); ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mb-3">
                    <i class="fas fa-paper-plane"></i> Отправить заявку
                </button>

                <p class="text-center mb-0">
                    <a href="my_applications.php"><i class="fas fa-arrow-left"></i> Вернуться к списку моих заявок</a>
                </p>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

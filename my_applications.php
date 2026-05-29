<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'php/application_functions.php';
require_once 'php/user_functions.php';

$page_title = 'Мои заявки - Корочки.есть';
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'] ?? 'user';
$applications = getUserApplications($user_id);

$success = $_SESSION['application_success'] ?? '';
$error = $_SESSION['application_error'] ?? '';

unset($_SESSION['application_success']);
unset($_SESSION['application_error']);

$extra_footer_script = <<<'HTML'
<script>
function showReviewForm(id) {
    document.getElementById('review-form-' + id).style.display = 'block';
}
function hideReviewForm(id) {
    document.getElementById('review-form-' + id).style.display = 'none';
}
</script>
HTML;

include 'includes/header.php';
?>

<?php if ($success): ?>
<div data-success-message="<?php echo htmlspecialchars($success); ?>" style="display:none;"></div>
<?php endif; ?>

<?php if ($error): ?>
<div data-error-message="<?php echo htmlspecialchars($error); ?>" style="display:none;"></div>
<?php endif; ?>

<?php if ($user_role === 'admin'): ?>
<div class="mb-3">
    <a href="admin/dashboard.php" class="btn btn-danger">
        <i class="fas fa-cog"></i> Перейти в панель администратора
    </a>
</div>
<?php endif; ?>

<h1 class="mb-4">Мои заявки на обучение</h1>

<?php if (empty($applications)): ?>
<div class="card text-center">
    <p class="mb-3">У вас пока нет ни одной заявки на обучение</p>
    <a href="new_application.php" class="btn btn-success">Создать первую заявку</a>
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Курс</th>
                <th>Дата начала</th>
                <th>Способ оплаты</th>
                <th>Статус</th>
                <th>Дата подачи</th>
                <th>Отзыв</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $app): ?>
            <?php
            $status_class = 'status';
            switch ($app['status']) {
                case 'Новая':
                    $status_class = 'status-new';
                    break;
                case 'Идет обучение':
                    $status_class = 'status-in-progress';
                    break;
                case 'Обучение завершено':
                    $status_class = 'status-completed';
                    break;
            }
            ?>
            <tr>
                <td>#<?php echo (int) $app['id']; ?></td>
                <td><?php echo htmlspecialchars($app['course_name']); ?></td>
                <td><?php echo date('d.m.Y', strtotime($app['desired_start_date'])); ?></td>
                <td><?php echo htmlspecialchars($app['payment_method_name']); ?></td>
                <td><span class="status <?php echo $status_class; ?>"><?php echo htmlspecialchars($app['status']); ?></span></td>
                <td><?php echo date('d.m.Y H:i', strtotime($app['created_at'])); ?></td>
                <td class="review-text"><?php echo !empty($app['review']) ? htmlspecialchars($app['review']) : '—'; ?></td>
                <td>
                    <?php if ($app['status'] === 'Обучение завершено' && empty($app['review'])): ?>
                    <button type="button" class="btn btn-success btn-sm" onclick="showReviewForm(<?php echo (int) $app['id']; ?>)">Оставить отзыв</button>
                    <div id="review-form-<?php echo (int) $app['id']; ?>" style="display:none;margin-top:10px;">
                        <form action="review_handler.php" method="POST">
                            <input type="hidden" name="application_id" value="<?php echo (int) $app['id']; ?>">
                            <input type="text" name="review" class="form-control mb-2" placeholder="Ваш отзыв..." required maxlength="500">
                            <button type="submit" class="btn btn-success btn-sm">Отправить</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hideReviewForm(<?php echo (int) $app['id']; ?>)">Отмена</button>
                        </form>
                    </div>
                    <?php else: ?>
                    —
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

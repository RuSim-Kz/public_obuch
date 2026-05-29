<?php
session_start();

$page_title = 'Главная - Корочки.есть';

include 'includes/header.php';
?>

<div id="main-slider" class="slider-container">
    <div class="slider">
        <div class="slide">
            <img src="images/slide1.jpg" alt="Курсы программирования" onerror="this.src='images/slide1.jpg'">
            <div class="slide-content">
                <h3>Программирование с нуля</h3>
                <p>Освойте востребованные языки программирования</p>
            </div>
        </div>
        <div class="slide">
            <img src="images/slide2.jpg" alt="Дизайн и верстка" onerror="this.src='images/slide2.jpg'">
            <div class="slide-content">
                <h3>Веб-дизайн и разработка</h3>
                <p>Создавайте современные сайты и приложения</p>
            </div>
        </div>
        <div class="slide">
            <img src="images/slide3.jpg" alt="Английский для IT" onerror="this.src='images/slide3.jpg'">
            <div class="slide-content">
                <h3>Английский для IT</h3>
                <p>Специализированные курсы английского языка</p>
            </div>
        </div>
        <div class="slide">
            <img src="images/slide4.jpg" alt="Бизнес-аналитика" onerror="this.src='images/slide4.jpg'">
            <div class="slide-content">
                <h3>Бизнес-аналитика</h3>
                <p>Анализ данных и принятие решений</p>
            </div>
        </div>
    </div>
    <button type="button" class="slider-btn prev" aria-label="Предыдущий слайд">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button type="button" class="slider-btn next" aria-label="Следующий слайд">
        <i class="fas fa-chevron-right"></i>
    </button>
    <div class="slider-dots"></div>
</div>

<div class="row mt-5">
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-laptop-code fa-3x mb-3" style="color: #667eea;"></i>
                <h5 class="card-title">Актуальные курсы</h5>
                <p class="card-text">Программы разработаны с учетом требований современного рынка труда</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-clock fa-3x mb-3" style="color: #667eea;"></i>
                <h5 class="card-title">Гибкий график</h5>
                <p class="card-text">Обучайтесь в удобное время, совмещая с работой или учебой</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-certificate fa-3x mb-3" style="color: #667eea;"></i>
                <h5 class="card-title">Сертификаты</h5>
                <p class="card-text">Получите официальный сертификат о прохождении курса</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">О платформе «Корочки.есть»</h3>
                <p class="card-text">
                    Добро пожаловать на образовательную платформу дополнительного профессионального
                    образования «Корочки.есть»! Мы предлагаем широкий выбор курсов для повышения
                    квалификации и освоения новых профессий.
                </p>
                <div class="text-center mt-4">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn btn-primary me-2">
                        <i class="fas fa-user-plus"></i> Начать обучение
                    </a>
                    <a href="login.php" class="btn btn-success">
                        <i class="fas fa-sign-in-alt"></i> Войти в систему
                    </a>
                    <?php else: ?>
                    <a href="new_application.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Подать заявку на курс
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/korochki_project/js/slider.js"></script>
<?php include 'includes/footer.php'; ?>

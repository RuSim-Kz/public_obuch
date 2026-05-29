        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Портал «Корочки.есть». Все права защищены.</p>
            <p>Образовательная платформа для профессионального развития</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/korochki_project/js/notifications.js"></script>
    <?php if (!empty($extra_footer_script)): ?>
    <?php echo $extra_footer_script; ?>
    <?php endif; ?>
</body>
</html>

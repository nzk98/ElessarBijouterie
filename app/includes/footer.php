<footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Elessar Bijouterie</h3>
                <p>Créations artisanales uniques</p>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: contact@elessar-bijouterie.fr</p>
            </div>
            <div class="footer-section">
                <h3>Suivez-nous</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Elessar Bijouterie. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Scripts JavaScript -->
    <script src="assets/js/main.js?v=<?= filemtime('assets/js/main.js') ?>"></script>
    <?php if (isset($jsFile)): ?>
        <?php if (is_array($jsFile)): ?>
            <?php foreach ($jsFile as $js): ?>
                <script src="assets/js/<?= strip_tags($js) ?>?v=<?= filemtime('assets/js/' . $js) ?>"></script>
            <?php endforeach; ?>
        <?php else: ?>
            <script src="assets/js/<?= strip_tags($jsFile) ?>?v=<?= filemtime('assets/js/' . $jsFile) ?>"></script>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html> 
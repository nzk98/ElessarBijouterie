<footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Elessar Bijouterie</h3>
                <p>Créations artisanales uniques</p>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: <a href="mailto:contact@elessar-bijouterie.fr">contact@elessar-bijouterie.fr</a></p>
            </div>
            <div class="footer-section">
                <h3>Suivez-nous</h3>
                <div class="social-links">
                    <a href="https://www.instagram.com/elessar_bijouterie/"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Elessar Bijouterie. Tous droits réservés. | <a href="index.php?page=MentionsLegales">Mentions légales</a> | <a href="index.php?page=CGV">CGV</a></p>
        </div>
    </footer>

    <div id="cookie-consent-popin" class="cookie-consent-popin">
        <p>Pour garder votre panier entre vos visites, nous pouvons utiliser un cookie. Acceptez-vous le cookie panier&nbsp;?</p>
        <button id="accept-cart-cookie" class="accept-cart-cookie">Accepter</button>
        <button id="refuse-cart-cookie">Refuser</button>
    </div>

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
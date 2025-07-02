<main class="contact-container">
    <h1>Contactez-moi</h1>
    <div class="contact-content-row">
        <section class="contact-info-section">
            <h2>Contact</h2>
            <div class="contact-methods">
                <div class="contact-method">
                    <i class="fas fa-envelope"></i>
                    <h3>Email</h3>
                    <p><a href="mailto:contact@elessar-bijouterie.fr">contact@elessar-bijouterie.fr</a></p>
                </div>
                <div class="contact-method">
                    <h3>Réseaux Sociaux</h3>
                    <div class="social-links">
                        <a href="https://www.instagram.com/elessar_bijouterie/" aria-label="Instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-form-section">
            <h2>Envoyez-nous un message</h2>
            
            <!-- Message de réponse -->
            <div id="contact-response" class="contact-response"></div>
            
            <form id="contact-form" class="contact-form" action="contact/sendMessage" method="POST">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Nom..." required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Prénom..." required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="exemple@gmail.com" required>
                </div>
                <div class="form-group">
                    <label for="sujet">Sujet</label>
                    <select id="sujet" name="sujet" required>
                        <option value="">Choisir un sujet...</option>
                        <option value="information">Demande d'information</option>
                        <option value="commande">Question sur une commande</option>
                        <option value="personnalisation">Demande de personnalisation</option>
                        <option value="devis">Demande de devis</option>
                        <option value="création sur mesure">Demande de création sur mesure</option>
                        <option value="reparation">Demande de reparation</option>
                        <option value="SAV">Demande de SAV</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="btn-primary" id="submit-btn">Envoyer le message</button>
            </form>
        </section>
    </div>
</main>
<main class="contact-container">
        <section class="contact-info-section">
            <h2>Contactez-nous</h2>
            <div class="contact-methods">
                <div class="contact-method">
                    <i class="fas fa-envelope"></i>
                    <h3>Email</h3>
                    <p>contact@elessar-bijouterie.fr</p>
                </div>
                <div class="contact-method">
                    <i class="fas fa-clock"></i>
                    <h3>Horaires</h3>
                    <p>Lundi - Vendredi : 9h - 18h</p>
                    <p>Samedi : 10h - 16h</p>
                </div>
                <div class="contact-method">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Adresse</h3>
                    <p>123 Rue de la Bijouterie</p>
                    <p>75000 Paris, France</p>
                </div>
            </div>
        </section>

        <section class="contact-form-section">
            <h2>Envoyez-nous un message</h2>
            <form id="contact-form" class="contact-form">
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
                <button type="submit" class="btn-primary">Envoyer le message</button>
            </form>
        </section>
    </main>
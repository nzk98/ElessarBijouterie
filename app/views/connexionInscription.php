<main class="auth-container" role="main">
        <!-- Section de connexion -->
        <section class="auth-section" id="login-section" aria-labelledby="login-title">
            <h2 id="login-title">Connexion</h2>
            <form method="post" id="login-form" class="auth-form" aria-label="Formulaire de connexion">
                <div class="form-group">
                    <label for="login-email" id="login-email-label">Email</label>
                    <input type="email" id="login-email" name="email" required 
                           aria-required="true" aria-labelledby="login-email-label"
                           placeholder="Entrez votre adresse email">
                </div>
                <div class="form-group">
                    <label for="login-password" id="login-password-label">Mot de passe</label>
                    <input type="password" id="login-password" name="password" required 
                           aria-required="true" aria-labelledby="login-password-label"
                           placeholder="Entrez votre mot de passe">
                </div>
                <button type="submit" class="btn-primary" aria-label="Se connecter">Se connecter</button>
                <p class="form-footer">
                    Pas encore de compte ? 
                    <a href="#" id="show-register" aria-label="Aller à la page d'inscription">Inscrivez-vous</a>
                </p>
            </form>
        </section>

        <!-- Section d'inscription -->
        <section class="auth-section" id="register-section" style="display: none;" aria-labelledby="register-title">
            <h2 id="register-title">Inscription</h2>
            <form method="post" id="register-form" class="auth-form" aria-label="Formulaire d'inscription">
                <div class="form-group">
                    <label for="register-civilite" id="register-civilite-label">Civilité</label>
                    <select id="register-civilite" name="civilite" required 
                            aria-required="true" aria-labelledby="register-civilite-label">
                        <option value="">Choisir...</option>
                        <option value="Mr">Mr</option>
                        <option value="Mme">Mme</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="register-nom" id="register-nom-label">Nom</label>
                    <input type="text" id="register-nom" name="nom" required 
                           aria-required="true" aria-labelledby="register-nom-label"
                           placeholder="Entrez votre nom">
                </div>
                <div class="form-group">
                    <label for="register-prenom" id="register-prenom-label">Prénom</label>
                    <input type="text" id="register-prenom" name="prenom" required 
                           aria-required="true" aria-labelledby="register-prenom-label"
                           placeholder="Entrez votre prénom">
                </div>
                <div class="form-group">
                    <label for="register-email" id="register-email-label">Email</label>
                    <input type="email" id="register-email" name="email" required 
                           aria-required="true" aria-labelledby="register-email-label"
                           placeholder="Entrez votre adresse email">
                </div>
                <div class="form-group">
                    <label for="register-password" id="register-password-label">Mot de passe</label>
                    <input type="password" id="register-password" name="password" required 
                           aria-required="true" aria-labelledby="register-password-label"
                           placeholder="Créez votre mot de passe"
                           aria-describedby="password-requirements">
                </div>
                <div class="form-group">
                    <label for="register-password-confirm" id="register-password-confirm-label">Confirmer le mot de passe</label>
                    <input type="password" id="register-password-confirm" name="password_confirm" required 
                           aria-required="true" aria-labelledby="register-password-confirm-label"
                           placeholder="Confirmez votre mot de passe">
                </div>
                <div class="form-group">
                    <label for="register-tel" id="register-tel-label">Téléphone <small>(facultatif)</small></label>
                    <input type="tel" id="register-tel" name="telephone" 
                           aria-labelledby="register-tel-label"
                           placeholder="Entrez votre numéro de téléphone">
                </div>
                <button type="submit" class="btn-primary" aria-label="Créer un compte">S'inscrire</button>
                <p class="form-footer">
                    Déjà inscrit ? 
                    <a href="#" id="show-login" aria-label="Aller à la page de connexion">Connectez-vous</a>
                </p>
            </form>
        </section>
    </main>

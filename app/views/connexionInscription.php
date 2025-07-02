<main class="auth-container" role="main">
    <!-- Messages de succès et d'erreur -->
    <?php if (isset($this) && $this->hasSuccess()): ?>
        <div class="alert alert-success">
            <?php foreach ($this->getSuccess() as $message): ?>
                <p><?= htmlspecialchars($message) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php
    $errors = isset($this) ? $this->getErrors() : [];
    if (isset($errors['general']) || isset($errors['login_general'])): ?>
        <div class="alert alert-danger">
            <?php if (isset($errors['general'])): ?>
                <p><?= htmlspecialchars($errors['general']) ?></p>
            <?php endif; ?>
            <?php if (isset($errors['login_general'])): ?>
                <p><?= htmlspecialchars($errors['login_general']) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Section de connexion -->
    <section class="auth-section <?= (isset($showRegisterVar) && $showRegisterVar) ? 'hidden' : 'visible' ?>" id="login-section" aria-labelledby="login-title">
        <h2 id="login-title">Connexion</h2>
        <form method="post" id="login-form" class="auth-form" aria-label="Formulaire de connexion">
            <?php 
            // Ajout du jeton CSRF pour le formulaire de connexion
            require_once __DIR__ . '/../models/CSRF.php';
            echo CSRF::getHiddenField();
            ?>
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['login_email'])) ? 'has-error' : '' ?>">
                <label for="login-email" id="login-email-label">Email</label>
                <input type="email" id="login-email" name="email" required 
                       aria-required="true" aria-labelledby="login-email-label"
                       placeholder="Entrez votre adresse email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <?php if (isset($this) && isset($this->getErrors()['login_email'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['login_email']) ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['login_password'])) ? 'has-error' : '' ?>">
                <label for="login-password" id="login-password-label">Mot de passe</label>
                <input type="password" id="login-password" name="password" required 
                       aria-required="true" aria-labelledby="login-password-label"
                       placeholder="Entrez votre mot de passe">
                <?php if (isset($this) && isset($this->getErrors()['login_password'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['login_password']) ?></span>
                <?php endif; ?>
            </div>
            <p class="form-footer">
                <a href="index.php?page=Mdpoublie" class="forgot-password-link">Mot de passe oublié&nbsp;?</a>
            </p>
            <button type="submit" class="btn-primary" aria-label="Se connecter">Se connecter</button>
            <p class="form-footer">
                Pas encore de compte ? 
                <a href="#" id="show-register" aria-label="Aller à la page d'inscription">Inscrivez-vous</a>
            </p>
        </form>
    </section>

    <!-- Section d'inscription -->
    <section class="auth-section <?= (isset($showRegisterVar) && $showRegisterVar) ? 'visible' : 'hidden' ?>" id="register-section" aria-labelledby="register-title">
        <h2 id="register-title">Inscription</h2>
        <form method="post" id="register-form" class="auth-form" aria-label="Formulaire d'inscription">
            <?php 
            // Ajout du jeton CSRF pour le formulaire d'inscription
            echo CSRF::getHiddenField();
            ?>
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['civilite'])) ? 'has-error' : '' ?>">
                <label for="register-civilite" id="register-civilite-label">Civilité</label>
                <select id="register-civilite" name="civilite" required 
                        aria-required="true" aria-labelledby="register-civilite-label">
                    <option value="">Choisir...</option>
                    <option value="Mr" <?= (isset($_POST['civilite']) && $_POST['civilite'] === 'Mr') ? 'selected' : '' ?>>Mr</option>
                    <option value="Mme" <?= (isset($_POST['civilite']) && $_POST['civilite'] === 'Mme') ? 'selected' : '' ?>>Mme</option>
                    <option value="Autre" <?= (isset($_POST['civilite']) && $_POST['civilite'] === 'Autre') ? 'selected' : '' ?>>Autre</option>
                </select>
                <?php if (isset($this) && isset($this->getErrors()['civilite'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['civilite']) ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['nom'])) ? 'has-error' : '' ?>">
                <label for="register-nom" id="register-nom-label">Nom</label>
                <input type="text" id="register-nom" name="nom" required 
                       aria-required="true" aria-labelledby="register-nom-label"
                       placeholder="Entrez votre nom"
                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                <?php if (isset($this) && isset($this->getErrors()['nom'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['nom']) ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['prenom'])) ? 'has-error' : '' ?>">
                <label for="register-prenom" id="register-prenom-label">Prénom</label>
                <input type="text" id="register-prenom" name="prenom" required 
                       aria-required="true" aria-labelledby="register-prenom-label"
                       placeholder="Entrez votre prénom"
                       value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
                <?php if (isset($this) && isset($this->getErrors()['prenom'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['prenom']) ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['email'])) ? 'has-error' : '' ?>">
                <label for="register-email" id="register-email-label">Email</label>
                <input type="email" id="register-email" name="email" required 
                       aria-required="true" aria-labelledby="register-email-label"
                       placeholder="Entrez votre adresse email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <?php if (isset($this) && isset($this->getErrors()['email'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['email']) ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['password'])) ? 'has-error' : '' ?>">
                <label for="register-password" id="register-password-label">Mot de passe</label>
                <input type="password" id="register-password" name="password" required 
                       aria-required="true" aria-labelledby="register-password-label"
                       placeholder="Créez votre mot de passe"
                       aria-describedby="password-requirements">
                <small id="password-requirements" class="form-help">
                    Le mot de passe doit contenir au moins 8 caractères, 2 majuscules, 1 chiffre et 1 caractère spécial.
                </small>
                <div id="password-requirements-indicators" class="password-requirements">
                    <div id="length-check" class="requirement">❌ Au moins 8 caractères</div>
                    <div id="uppercase-check" class="requirement">❌ Au moins 2 majuscules</div>
                    <div id="number-check" class="requirement">❌ Au moins 1 chiffre</div>
                    <div id="special-check" class="requirement">❌ Au moins 1 caractère spécial</div>
                </div>
                <?php if (isset($this) && isset($this->getErrors()['password'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['password']) ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['password_confirm'])) ? 'has-error' : '' ?>">
                <label for="register-password-confirm" id="register-password-confirm-label">Confirmer le mot de passe</label>
                <input type="password" id="register-password-confirm" name="password_confirm" required 
                       aria-required="true" aria-labelledby="register-password-confirm-label"
                       placeholder="Confirmez votre mot de passe">
                <div id="password-match" class="password-match">❌ Les mots de passe ne correspondent pas</div>
                <?php if (isset($this) && isset($this->getErrors()['password_confirm'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['password_confirm']) ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['telephone'])) ? 'has-error' : '' ?>">
                <label for="register-tel" id="register-tel-label">Téléphone <small>(facultatif)</small></label>
                <input type="tel" id="register-tel" name="telephone" 
                       aria-labelledby="register-tel-label"
                       placeholder="06 12 34 56 78"
                       value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                <?php if (isset($this) && isset($this->getErrors()['telephone'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['telephone']) ?></span>
                <?php endif; ?>
                
            </div>
            <button type="submit" class="btn-primary" aria-label="Créer un compte">S'inscrire</button>
            <p class="form-footer">
                Déjà inscrit ? 
                <a href="#" id="show-login" aria-label="Aller à la page de connexion">Connectez-vous</a>
            </p>
        </form>
    </section>
</main>

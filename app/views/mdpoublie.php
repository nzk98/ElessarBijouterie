<main class="auth-container" role="main">
    <h2>Mot de passe oublié</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $message): ?>
                <p><?= htmlspecialchars($message) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success) && empty($success['reset_done'])): ?>
        <div class="alert alert-success">
            <?php foreach ($success as $key => $message): ?>
                <?php if ($key !== 'reset_done'): ?>
                    <p><?= htmlspecialchars($message) ?></p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php
    // Affichage du formulaire de demande d'email
    if (empty($showResetForm) && empty($this->success['reset_done'])): ?>
        <form method="post" class="auth-form" aria-label="Formulaire de demande de réinitialisation">
            <div class="form-group">
                <label for="reset-email">Votre adresse email</label>
                <input type="email" id="reset-email" name="email" required placeholder="Entrez votre email">
            </div>
            <button type="submit" class="btn-primary">Recevoir le lien de réinitialisation</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($showResetForm)): ?>
        <form method="post" class="auth-form" aria-label="Formulaire de nouveau mot de passe">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? $_POST['token'] ?? '') ?>">
            <div class="form-group">
                <label for="reset-password">Nouveau mot de passe</label>
                <input type="password" id="reset-password" name="password" required placeholder="Nouveau mot de passe">
                <small>Au moins 8 caractères, 2 majuscules, 1 chiffre, 1 caractère spécial.</small>
            </div>
            <div class="form-group">
                <label for="reset-password-confirm">Confirmer le mot de passe</label>
                <input type="password" id="reset-password-confirm" name="password_confirm" required placeholder="Confirmez le mot de passe">
            </div>
            <button type="submit" class="btn-primary">Réinitialiser le mot de passe</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($success['reset_done'])): ?>
        <div class="succes-reset-container">
            <div class="succes-reset-message">
                <?= htmlspecialchars($success['reset_done']) ?>
            </div>
            <a href="index.php?page=Connexion" class="succes-reset-btn">Retour à la connexion</a>
        </div>
    <?php endif; ?>
</main>

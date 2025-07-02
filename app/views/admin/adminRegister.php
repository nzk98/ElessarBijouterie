<?php
$errors = isset($this) ? $this->getErrors() : [];
$success = isset($this) ? $this->getSuccess() : [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Admin - Elessar Bijouterie</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-login-box">
            <h1>Inscription Admin</h1>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <?php foreach ($success as $message): ?>
                        <?= htmlspecialchars($message) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($errors['general']) ?>
                </div>
            <?php endif; ?>
            
            <form action="index.php?page=AdminRegister-espace-2547" method="POST" class="admin-form" id="admin-register-form">
                
                <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <?php if (isset($errors['email'])): ?><span class="error-message"><?= $errors['email'] ?></span><?php endif; ?>
                </div>
                
                <div class="form-group <?= isset($errors['password']) ? 'has-error' : '' ?>">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                    <small>Doit contenir au moins 8 caractères, 2 majuscules, 1 chiffre et 1 caractère spécial.</small>
                    <div id="password-requirements" class="password-requirements">
                        <div id="length-check" class="requirement">❌ Au moins 8 caractères</div>
                        <div id="uppercase-check" class="requirement">❌ Au moins 2 majuscules</div>
                        <div id="number-check" class="requirement">❌ Au moins 1 chiffre</div>
                        <div id="special-check" class="requirement">❌ Au moins 1 caractère spécial</div>
                    </div>
                    <?php if (isset($errors['password'])): ?><span class="error-message"><?= $errors['password'] ?></span><?php endif; ?>
                </div>
                
                <div class="form-group <?= isset($errors['password_confirm']) ? 'has-error' : '' ?>">
                    <label for="password_confirm">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                    <div id="password-match" class="password-match">❌ Les mots de passe ne correspondent pas</div>
                    <?php if (isset($errors['password_confirm'])): ?><span class="error-message"><?= $errors['password_confirm'] ?></span><?php endif; ?>
                </div>
                
                <button type="submit" class="admin-submit">S'inscrire</button>
            </form>
            <div class="admin-footer">
                <p>Zone réservée aux administrateurs</p>
            </div>
        </div>
    </div>
</body>
</html> 
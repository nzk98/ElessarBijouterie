<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Elessar Bijouterie</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-login-box">
            <h1>Administration</h1>
           
            <?php if (!empty(
                $error
            )): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form action="#" method="POST" class="admin-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="admin-submit">Se connecter</button>
            </form>
            <div class="admin-footer">
                <p>Zone réservée aux administrateurs</p>
            </div>
        </div>
    </div>
</body>
</html>

<div class="container">
    <h2>Mon Profil</h2>

    <!-- Messages de succès et d'erreur -->
    <?php if (isset($this) && $this->hasSuccess()): ?>
        <div class="alert alert-success">
            <?php foreach ($this->getSuccess() as $message): ?>
                <p><?= htmlspecialchars($message) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($this) && $this->hasErrors()): ?>
        <div class="alert alert-danger">
            <?php foreach ($this->getErrors() as $field => $message): ?>
                <?php if ($field === 'general'): ?>
                    <p><?= htmlspecialchars($message) ?></p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="profile-info">
        <form action="index.php?page=ProfilUtilisateur" method="POST" class="profile-form">
            <div class="form-group">
                <label for="civilite">Civilité</label>
                <select class="form-control" id="civilite" name="civilite" required>
                    <option value="Mr" <?= ((isset($_POST['civilite']) && $_POST['civilite'] === 'Mr') || (isset($_SESSION['user']['civilite']) && $_SESSION['user']['civilite'] === 'Mr')) ? 'selected' : '' ?>>Mr</option>
                    <option value="Mme" <?= ((isset($_POST['civilite']) && $_POST['civilite'] === 'Mme') || (isset($_SESSION['user']['civilite']) && $_SESSION['user']['civilite'] === 'Mme')) ? 'selected' : '' ?>>Mme</option>
                    <option value="Autre" <?= ((isset($_POST['civilite']) && $_POST['civilite'] === 'Autre') || (isset($_SESSION['user']['civilite']) && $_SESSION['user']['civilite'] === 'Autre')) ? 'selected' : '' ?>>Autre</option>
                </select>
            </div>

            <div class="form-group <?= (isset($this) && isset($this->getErrors()['nom'])) ? 'has-error' : '' ?>">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? $_SESSION['user']['nom'] ?? '') ?>" >
                <?php if (isset($this) && isset($this->getErrors()['nom'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['nom']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['prenom'])) ? 'has-error' : '' ?>">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? $_SESSION['user']['prenom'] ?? '') ?>" >
                <?php if (isset($this) && isset($this->getErrors()['prenom'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['prenom']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['email'])) ? 'has-error' : '' ?>">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $_SESSION['user']['email'] ?? '') ?>" >
                <?php if (isset($this) && isset($this->getErrors()['email'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['email']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['password'])) ? 'has-error' : '' ?>">
                <label for="password">Nouveau mot de passe</label><small>Laissez vide pour ne pas modifier</small>
                <input type="password" class="form-control" id="password" name="password" placeholder="Laissez vide pour ne pas modifier">
                <small class="form-help">
                    Au moins 8 caractères, 2 majuscules, 1 chiffre, 1 caractère spécial.
                </small>
                <div id="password-requirements" class="password-requirements">
                    <div id="length-check" class="requirement">❌ Au moins 8 caractères</div>
                    <div id="uppercase-check" class="requirement">❌ Au moins 2 majuscules</div>
                    <div id="number-check" class="requirement">❌ Au moins 1 chiffre</div>
                    <div id="special-check" class="requirement">❌ Au moins 1 caractère spécial</div>
                </div>
                 <?php if (isset($this) && isset($this->getErrors()['password'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['password']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group <?= (isset($this) && isset($this->getErrors()['telephone'])) ? 'has-error' : '' ?>">
                <label for="telephone">Téléphone</label>
                <input type="tel" class="form-control" id="telephone" name="telephone" 
                       placeholder="06 12 34 56 78"
                       value="<?= htmlspecialchars($_POST['telephone'] ?? $_SESSION['user']['telephone'] ?? '') ?>">
                 <?php if (isset($this) && isset($this->getErrors()['telephone'])): ?>
                    <span class="error-message"><?= htmlspecialchars($this->getErrors()['telephone']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" class="form-control" id="adresse" name="adresse" value="<?= htmlspecialchars($_POST['adresse'] ?? $_SESSION['user']['Adresse'] ?? '') ?>" >
            </div>

            <div class="form-group">
                <label for="code_postal">Code Postal</label>
                <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?= htmlspecialchars($_POST['code_postal'] ?? $_SESSION['user']['CodePostal'] ?? '') ?>" >
            </div>

            <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" class="form-control" id="ville" name="ville" value="<?= htmlspecialchars($_POST['ville'] ?? $_SESSION['user']['Ville'] ?? '') ?>" >
            </div>
            
            <button type="submit" class="btn btn-primary">Mettre à jour mon profil</button>
        </form>
    </div>

    <div class="actions mt-4">
        <a href="index.php?page=Dashboard" class="btn btn-secondary">Retour au tableau de bord</a>
    </div>
</div> 
<div class="container">
    <h2>Mon Profil</h2>
    
    <div class="profile-info">
        <form action="index.php?page=ProfilUtilisateur" method="POST" class="profile-form">
            <div class="form-group">
                <label for="civilite">Civilité</label>
                <select class="form-control" id="civilite" name="civilite" required>
                    <option value="Mr" <?php echo (isset($_SESSION['user']['civilite']) && $_SESSION['user']['civilite'] === 'Mr') ? 'selected' : ''; ?>>Mr</option>
                    <option value="Mme" <?php echo (isset($_SESSION['user']['civilite']) && $_SESSION['user']['civilite'] === 'Mme') ? 'selected' : ''; ?>>Mme</option>
                    <option value="Autre" <?php echo (isset($_SESSION['user']['civilite']) && $_SESSION['user']['civilite'] === 'Autre') ? 'selected' : ''; ?>>Autre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $_SESSION['user']['nom'] ?? ''; ?>" >
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $_SESSION['user']['prenom'] ?? ''; ?>" >
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['user']['email'] ?? ''; ?>" >
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label><small>Laissez vide pour ne pas modifier</small>
                <input type="password" class="form-control" id="password" name="password" placeholder="Laissez vide pour ne pas modifier">
            </div>
            
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo $_SESSION['user']['telephone'] ?? ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $_SESSION['user']['Adresse'] ?? ''; ?>" >
            </div>

            <div class="form-group">
                <label for="code_postal">Code Postal</label>
                <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?php echo $_SESSION['user']['CodePostal'] ?? ''; ?>" >
            </div>

            <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" class="form-control" id="ville" name="ville" value="<?php echo $_SESSION['user']['Ville'] ?? ''; ?>" >
            </div>
            
            <button type="submit" class="btn btn-primary">Mettre à jour mon profil</button>
        </form>
    </div>

    <div class="actions mt-4">
        <a href="index.php?page=Dashboard" class="btn btn-secondary">Retour au tableau de bord</a>
    </div>
</div> 
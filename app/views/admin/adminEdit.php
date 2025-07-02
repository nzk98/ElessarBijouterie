<div class="admin-main">
    <div class="admin-header">
        <div class="header-left">
            <h1>Modifier un administrateur</h1>
        </div>
    </div>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_SESSION['error']); ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['success']); ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <div class="admin-form-box">
        <form action="index.php?page=AdminManage" method="POST" class="admin-form" id="edit-admin-form">
            <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin['ID_Admin']); ?>">
            <div class="form-section">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($admin['Email_Admin']); ?>" required>
                </div>
            </div>
            <div class="form-section">
                <div class="form-group">
                    <button type="button" id="show-password-fields" class="btn btn-mdp-change"><i class="fas fa-lock"></i> Voulez-vous changer votre MDP ?</button>
                </div>
                <div class="form-group password-fields" id="password-fields">
                    <label for="current_password">Mot de passe actuel</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Entrez votre mot de passe actuel">
                    <div id="current-password-error" class="current-password-error">
                        Le mot de passe actuel est incorrect.
                    </div>
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Entrez le nouveau mot de passe">
                    <small>Doit contenir au moins 8 caractères, 2 majuscules, 1 chiffre et 1 caractère spécial.</small>
                    <div id="password-requirements" class="password-requirements">
                        <div id="length-check" class="requirement">❌ Au moins 8 caractères</div>
                        <div id="uppercase-check" class="requirement">❌ Au moins 2 majuscules</div>
                        <div id="number-check" class="requirement">❌ Au moins 1 chiffre</div>
                        <div id="special-check" class="requirement">❌ Au moins 1 caractère spécial</div>
                    </div>
                    <input type="hidden" name="change_mdp" id="change_mdp" value="0">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="admin-submit" name="edit_admin">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
                <a href="index.php?page=AdminManage" class="admin-reset">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div> 
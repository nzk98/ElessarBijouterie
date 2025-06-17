<div class="admin-main">
    <div class="admin-header">
        <div class="header-left">
            <h1>Modifier un administrateur</h1>
        </div>
    </div>
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
                <div class="form-group" id="password-fields" style="display:none;">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password">
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
<script>
document.getElementById('show-password-fields').addEventListener('click', function() {
    var fields = document.getElementById('password-fields');
    var changeMdp = document.getElementById('change_mdp');
    if (fields.style.display === 'none') {
        fields.style.display = 'block';
        changeMdp.value = '1';
    } else {
        fields.style.display = 'none';
        changeMdp.value = '0';
    }
});
</script>
<style>
.btn-mdp-change:hover {
    background: #217dbb !important;
    color: #fff !important;
    transform: scale(1.05);
}
</style> 
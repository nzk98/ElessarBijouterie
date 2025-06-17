<div class="admin-main">
    <div class="admin-header">
        <div class="header-left">
            <h1>Gérer les utilisateurs</h1>
        </div>
    </div>
    <div class="admin-form-box">
        <h2>Liste des utilisateurs</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['ID_Utilisateur']); ?></td>
                        <td><?php echo htmlspecialchars($user['Nom_Utilisateur']); ?></td>
                        <td><?php echo htmlspecialchars($user['Prenom_Utilisateur']); ?></td>
                        <td><?php echo htmlspecialchars($user['Email_Utilisateur']); ?></td>
                        <td>
                            <a href="index.php?page=AdminUsers&action=delete&id=<?php echo $user['ID_Utilisateur']; ?>" class="btn btn-danger btn-sm btn-admin-delete" onclick="return confirm('Supprimer cet utilisateur ?');"><i class="fas fa-trash"></i> Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> 
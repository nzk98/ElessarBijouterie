<div class="admin-main">
    <div class="admin-header">
        <div class="header-left">
            <h1>Gérer les administrateurs</h1>
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
        <h2>Liste des administrateurs</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($admin['ID_Admin']); ?></td>
                        <td><?php echo htmlspecialchars($admin['Email_Admin']); ?></td>
                        <td>
                            <a href="index.php?page=AdminManage&action=edit&id=<?php echo $admin['ID_Admin']; ?>" class="btn btn-primary btn-sm btn-admin-edit"><i class="fas fa-pen"></i> Modifier</a>
                            <a href="index.php?page=AdminManage&action=delete&id=<?php echo $admin['ID_Admin']; ?>" class="btn btn-danger btn-sm btn-admin-delete" onclick="return confirm('Supprimer cet admin ?');"><i class="fas fa-trash"></i> Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> 
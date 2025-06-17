<div class="admin-main">
    <div class="admin-header">
        <div class="header-left">
            <h1>Gérer les créations</h1>
        </div>
    </div>
    <div class="admin-form-box">
        <h2>Liste des créations</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Photo</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Catégorie</th>
                    <th>Matière</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($creations as $creation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($creation['Nom_Creation']); ?></td>
                        <td>
                            <?php 
                            $images = $creation['images'] ? explode(',', $creation['images']) : [];
                            if (count($images) > 0): ?>
                                <img src="<?php echo htmlspecialchars($images[0]); ?>" alt="photo" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars(mb_strimwidth($creation['Description_Creation'], 0, 40, '...')); ?></td>
                        <td><?php echo htmlspecialchars($creation['Prix_Creation']); ?> €</td>
                        <td><?php echo htmlspecialchars($creation['Stock_Creation']); ?></td>
                        <td><?php echo htmlspecialchars($creation['Nom_Categorie']); ?></td>
                        <td><?php echo htmlspecialchars($creation['Nom_Matiere']); ?></td>
                        <td>
                            <a href="index.php?page=AdminFormCreation&id=<?php echo $creation['ID_Creation']; ?>" class="btn btn-primary btn-sm btn-admin-edit"><i class="fas fa-pen"></i> Modifier</a>
                            <a href="index.php?page=AdminCreations&action=delete&id=<?php echo $creation['ID_Creation']; ?>" class="btn btn-danger btn-sm btn-admin-delete" onclick="return confirm('Supprimer cette création ?');"><i class="fas fa-trash"></i> Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div> 
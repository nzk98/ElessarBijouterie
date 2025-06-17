<div class="admin-main">
    <div class="admin-header">
        <div class="header-left">
            <h1>Gérer les articles</h1>
        </div>
    </div>
    <div class="admin-form-box">
        <h2>Liste des articles</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Image</th>
                    <th>Contenu</th>
                    <th>Date</th>
                    <th>Catégorie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['Titre_Article']); ?></td>
                        <td>
                            <?php 
                            $images = $article['images'] ? explode(',', $article['images']) : [];
                            if (count($images) > 0): ?>
                                <img src="<?php echo htmlspecialchars($images[0]); ?>" alt="photo" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars(mb_strimwidth($article['Contenue_Article'], 0, 40, '...')); ?></td>
                        <td><?php echo htmlspecialchars($article['DatePublication_Article']); ?></td>
                        <td><?php echo htmlspecialchars($article['Nom_Categorie']); ?></td>
                        <td>
                            <a href="index.php?page=AdminFormArticle&id=<?php echo $article['ID_Article']; ?>" class="btn btn-primary btn-sm btn-admin-edit"><i class="fas fa-pen"></i> Modifier</a>
                            <a href="index.php?page=AdminArticles&action=delete&id=<?php echo $article['ID_Article']; ?>" class="btn btn-danger btn-sm btn-admin-delete" onclick="return confirm('Supprimer cet article ?');"><i class="fas fa-trash"></i> Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

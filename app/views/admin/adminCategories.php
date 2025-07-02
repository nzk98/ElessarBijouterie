<div class="admin-main">
    <div class="admin-header">
        <h1>Gérer les catégories</h1>
    </div>
    <div class="admin-form-box">
        <div class="admin-categories-row">
            <div class="admin-categories-col">
                <h2>Catégories Articles</h2>
                <form method="POST" action="index.php?page=AdminCategories">
                    <input type="text" name="nom_categorie_article" placeholder="Nom catégorie article" required>
                    <button type="submit" name="add_categorie_article">Ajouter</button>
                </form>
                <ul>
                    <?php foreach ($categories_article as $cat): ?>
                        <li>
                            <?= htmlspecialchars($cat->getNom()) ?>
                            <a class="delete-link" href="index.php?page=AdminCategories&delete_article=<?= $cat->getId() ?>" onclick="return confirm('Supprimer ?')">🗑️</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="admin-categories-col">
                <h2>Catégories Créations</h2>
                <form method="POST" action="index.php?page=AdminCategories">
                    <input type="text" name="nom_categorie_creation" placeholder="Nom catégorie création" required>
                    <button type="submit" name="add_categorie_creation">Ajouter</button>
                </form>
                <ul>
                    <?php foreach ($categories_creation as $cat): ?>
                        <li>
                            <?= htmlspecialchars($cat->getNom()) ?>
                            <a class="delete-link" href="index.php?page=AdminCategories&delete_creation=<?= $cat->getId() ?>" onclick="return confirm('Supprimer ?')">🗑️</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div> 
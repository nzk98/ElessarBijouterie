<?php
$categories = isset($categories) ? $categories : [];

// Trie les catégories par ID décroissant
usort($categories, function($a, $b) {
    return $b->getId() - $a->getId();
});
?>
<main class="products-container">
    <h1 class="products-main-title">Boutique</h1>
    
    <!-- Filtres -->
    <aside class="filters-sidebar">
        <h2>Filtres</h2>
        <form action="index.php" method="GET">
            <input type="hidden" name="page" value="Boutique">
            <div class="filter-section">
                <h3>Catégories</h3>
                <div class="filter-options">
                    <?php foreach ($categories as $categorie): ?>
                    <label class="filter-option">
                        <input type="checkbox" name="categories[]" 
                               value="<?php echo htmlspecialchars($categorie->getId()); ?>"
                               <?php echo in_array($categorie->getId(), $selectedCategories) ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($categorie->getNom()); ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-section">
                <h3>Matières</h3>
                <div class="filter-options">
                    <?php foreach ($matieres as $matiere): ?>
                    <label class="filter-option">
                        <input type="checkbox" name="matieres[]"
                               value="<?php echo htmlspecialchars($matiere->id); ?>"
                               <?php echo (isset($selectedMatieres) && in_array($matiere->id, $selectedMatieres)) ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($matiere->nom); ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">Filtrer</button>
                <a href="index.php?page=Boutique" class="btn-reset">Réinitialiser</a>
            </div>
        </form>
    </aside>

    <!-- Liste des produits -->
    <section class="products-grid">
        <div class="products-header">
            <h2>Nos Créations</h2>
            <div class="sort-container">
                <form action="index.php" method="GET" class="sort-form">
                    <input type="hidden" name="page" value="Boutique">
                    <?php foreach ($selectedCategories as $cat): ?>
                        <input type="hidden" name="categories[]" value="<?php echo htmlspecialchars($cat); ?>">
                    <?php endforeach; ?>
                    <div class="sort-options">
                        <label for="sort">Trier par :</label>
                        <select id="sort" name="sort" class="filter-select" onchange="this.form.submit()">
                            <option value="id-desc" <?php echo $sort === 'id-desc' ? 'selected' : ''; ?>>Plus récents</option>
                            <option value="price-asc" <?php echo $sort === 'price-asc' ? 'selected' : ''; ?>>Prix croissant</option>
                            <option value="price-desc" <?php echo $sort === 'price-desc' ? 'selected' : ''; ?>>Prix décroissant</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="products-list" id="products-list">
            <?php foreach ($creations as $creation): ?>
                <article class="product-card <?php echo $creation->getStock() <= 0 ? 'out-of-stock' : ''; ?>">
                    <div class="product-image">
                        <img src="<?php echo !empty($creation->getImages()) ? $creation->getImages()[0] : 'assets/images/default-product.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($creation->getNom()); ?>">
                        <div class="product-overlay">
                            <a href="index.php?page=Produits&id=<?php echo $creation->getId(); ?>" class="btn-view">
                                Voir détails
                            </a>
                            <form method="POST" action="index.php?page=Panier">
                                <input type="hidden" name="add_to_cart" value="1">
                                <input type="hidden" name="id" value="<?php echo $creation->getId(); ?>">
                                <input type="hidden" name="nom" value="<?php echo htmlspecialchars($creation->getNom()); ?>">
                                <input type="hidden" name="prix" value="<?php echo number_format($creation->getPrix(), 2, '.', ''); ?>">
                                <input type="hidden" name="image" value="<?php echo !empty($creation->getImages()) ? $creation->getImages()[0] : 'assets/images/default-product.jpg'; ?>">
                                <input type="hidden" name="quantite" value="1">
                                <?php if ($creation->getStock() > 0): ?>
                                    <button type="submit" class="btn-add-cart">Ajouter au panier</button>
                                <?php else: ?>
                                    <button type="button" class="btn-add-cart" disabled>Rupture de stock</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($creation->getNom()); ?></h3>
                        <p class="product-price"><?php echo number_format($creation->getPrix(), 2, ',', ' '); ?> €</p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>


<?php
$categories = isset($categories) ? $categories : [];
?>
<main class="products-container">
    <h2 class="products-main-title">Boutique</h2>
    
    <!-- Filtres -->
    <aside class="filters-sidebar">
        <h2>Filtres</h2>
        <form action="index.php" method="GET">
            <input type="hidden" name="page" value="Boutique">
            <div class="filter-section">
                <h3>Collections</h3>
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
                <article class="product-card">
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
                                <input type="hidden" name="prix" value="<?php echo $creation->getPrix(); ?>">
                                <input type="hidden" name="image" value="<?php echo !empty($creation->getImages()) ? $creation->getImages()[0] : 'assets/images/default-product.jpg'; ?>">
                                <input type="hidden" name="quantite" value="1">
                                <button type="submit" class="btn-add-cart">Ajouter au panier</button>
                            </form>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($creation->getNom()); ?></h3>
                        <p class="product-price"><?php echo number_format($creation->getPrix(), 2); ?> €</p>
                        <p class="product-status">
                            <?php echo $creation->getStock() > 0 ? 'En stock' : 'Rupture de stock'; ?>
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-form');
    const sortSelect = document.getElementById('sort');
    const productsList = document.getElementById('products-list');
    const products = document.querySelectorAll('.product-card');

    function filterProducts() {
        const selectedCategories = Array.from(document.querySelectorAll('input[name="collection"]:checked')).map(input => input.value);
        const sortValue = sortSelect.value;

        let filteredProducts = Array.from(products);

        // Appliquer les filtres
        if (selectedCategories.length > 0) {
            filteredProducts = filteredProducts.filter(product => 
                selectedCategories.includes(product.dataset.category)
            );
        }

        // Trier les produits
        filteredProducts.sort((a, b) => {
            switch(sortValue) {
                case 'price-asc':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price-desc':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'id-desc':
                default:
                    return parseInt(b.dataset.id) - parseInt(a.dataset.id);
            }
        });

        // Masquer tous les produits
        products.forEach(product => product.style.display = 'none');

        // Afficher les produits filtrés
        filteredProducts.forEach(product => product.style.display = '');

        // Afficher un message si aucun produit
        if (filteredProducts.length === 0) {
            if (!document.querySelector('.no-products')) {
                const noProducts = document.createElement('p');
                noProducts.className = 'no-products';
                noProducts.textContent = 'Aucun produit ne correspond à vos critères';
                productsList.appendChild(noProducts);
            }
        } else {
            const noProducts = document.querySelector('.no-products');
            if (noProducts) {
                noProducts.remove();
            }
        }
    }

    // Écouter les changements des filtres
    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', filterProducts);
    });

    // Écouter les changements de tri
    sortSelect.addEventListener('change', filterProducts);
});
</script>


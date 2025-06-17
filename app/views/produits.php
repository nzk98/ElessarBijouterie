<main class="product-details-container">
<?php if ($creation): ?>
    <div class="product-details">
        <!-- Image du produit -->
        <div class="product-image-container">
            <img src="<?php echo !empty($creation->getImages()) ? $creation->getImages()[0] : 'assets/images/default-product.jpg'; ?>" alt="<?php echo htmlspecialchars($creation->getNom()); ?>" id="product-image" class="product-main-image">
            <div class="product-thumbnails">
                <?php foreach ($creation->getImages() as $index => $img): ?>
                    <div class="product-thumbnail">
                        <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($creation->getNom()); ?> - Vue <?php echo $index+1; ?>" onclick="document.getElementById('product-image').src='<?php echo $img; ?>'">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Informations du produit -->
        <div class="product-info">
            <h1 class="product-title"><?php echo htmlspecialchars($creation->getNom()); ?></h1>
            <div class="product-price-container">
                <span class="product-price"><?php echo number_format($creation->getPrix(), 2); ?> €</span>
                <span class="product-status"><?php echo $creation->getStock() > 0 ? 'En stock' : 'Rupture de stock'; ?></span>
                <small class="product-price-small">TVA non applicable article 293 b du cgi</small>
            </div>
            <div class="product-description">
                <h2>Description</h2>
                <p><?php echo nl2br(htmlspecialchars($creation->getDescription())); ?></p>
            </div>
            <!-- Sélecteur de quantité -->
            <div class="quantity-selector">
                <label>Quantité :</label>
                <div class="quantity-controls">
                    <button type="button" class="quantity-btn minus">-</button>
                    <span id="quantity-display">1</span>
                    <button type="button" class="quantity-btn plus">+</button>
                </div>
            </div>
            <!-- Bouton d'ajout au panier -->
            <form method="POST" action="index.php?page=Panier" id="add-to-cart-form">
                <input type="hidden" name="add_to_cart" value="1">
                <input type="hidden" name="id" value="<?php echo $creation->getId(); ?>">
                <input type="hidden" name="nom" value="<?php echo htmlspecialchars($creation->getNom()); ?>">
                <input type="hidden" name="prix" value="<?php echo $creation->getPrix(); ?>">
                <input type="hidden" name="image" value="<?php echo !empty($creation->getImages()) ? $creation->getImages()[0] : 'assets/images/default-product.jpg'; ?>">
                <input type="hidden" name="quantite" id="quantite-input" value="1">
                <button type="submit" class="btn-add-cart">
                    <i class="fas fa-shopping-cart"></i>
                    Ajouter au panier
                </button>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="product-details">
        <p>Produit introuvable.</p>
    </div>
<?php endif; ?>
</main>

<!-- Template pour les miniatures -->
<template id="thumbnail-template">
    <div class="product-thumbnail">
        <img src="" alt="">
    </div>
</template>

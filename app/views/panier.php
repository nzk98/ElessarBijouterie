<main class="cart-container">
    <h2>Votre Panier</h2>
    
    <div class="cart-content">
        <!-- Liste des produits -->
        <div class="cart-items">
            <div id="cart-list">
                <?php if (empty($panier)): ?>
                    <div class="cart-empty">
                        <p>Votre panier est vide</p>
                        <a href="index.php?page=Boutique" class="btn-primary">Découvrir nos produits</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($panier as $item): ?>
                        <div class="cart-item">
                            <div class="item-image">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['nom']); ?>">
                            </div>
                            <div class="item-details">
                                <h3 class="item-title"><?php echo htmlspecialchars($item['nom']); ?></h3>
                                <div class="item-quantity">
                                    <form method="POST" action="index.php?page=Panier" style="display:inline-flex;align-items:center;">
                                        <input type="hidden" name="update_quantity" value="1">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button class="quantity-btn minus" type="submit" name="quantite" value="<?php echo max(1, $item['quantite']-1); ?>">-</button>
                                        <span class="item-quantity-value"><?php echo $item['quantite']; ?></span>
                                        <button class="quantity-btn plus" type="submit" name="quantite" value="<?php echo $item['quantite']+1; ?>">+</button>
                                    </form>
                                </div>
                            </div>
                            <div class="item-price">
                                <p class="price"><?php echo number_format($item['prix'] * $item['quantite'], 2); ?> €</p>
                                <form method="POST" action="index.php?page=Panier">
                                    <input type="hidden" name="remove_from_cart" value="1">
                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                    <button class="remove-item"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Résumé de la commande -->
        <div class="cart-summary" id="cart-summary">
            <h3>Résumé de la commande</h3>
            <div class="summary-details">
                <div class="summary-line">
                    <span>Sous-total</span>
                    <span id="subtotal">
                        <?php 
                        $total = 0;
                        foreach ($panier as $item) $total += $item['prix'] * $item['quantite'];
                        echo number_format($total, 2) . ' €';
                        ?>
                    </span>
                </div>
                
                <div class="summary-line total">
                    <span>Total</span>
                    <span id="total"><?php echo number_format($total, 2); ?> €</span>
                </div>
            </div>
            
            <!-- PayPal Buttons -->
            <div id="paypal-button-container"></div>

            <!-- Bouton Passer au Paiement -->
            <button id="checkout-button" class="btn-primary">Passer au Paiement</button>
            
        </div>
    </div>
</main>

<!-- Template pour les articles du panier -->
<template id="cart-item-template">
    <div class="cart-item">
        <div class="item-image">
            <img src="" alt="">
        </div>
        <div class="item-details">
            <h3 class="item-title"></h3>
            <p class="item-collection"></p>
            <div class="item-quantity">
                <button class="quantity-btn minus">-</button>
                <input type="number" min="1" value="1">
                <button class="quantity-btn plus">+</button>
            </div>
        </div>
        <div class="item-price">
            <p class="price"></p>
            <button class="remove-item"><i class="fas fa-trash"></i></button>
        </div>
    </div>
</template>
class CartManager {
    constructor() {
        this.cart = JSON.parse(localStorage.getItem('cart')) || [];
    }
    
    init() {
        this.renderCart();
        this.initPayPal();
    }
    
    renderCart() {
        const container = document.getElementById('cart-list');
        const emptyCart = document.querySelector('.cart-empty');
        const template = document.getElementById('cart-item-template');
        
        // Vider le conteneur
        container.innerHTML = '';
        
        if (this.cart.length === 0) {
            container.style.display = 'none';
            emptyCart.style.display = 'block';
            return;
        }
        
        container.style.display = 'block';
        emptyCart.style.display = 'none';
        
        // Afficher les produits
        this.cart.forEach((item, index) => {
            const itemElement = template.content.cloneNode(true);
            
            // Ajout pour débogage : Inspecter l'élément généré
            console.log('Generated cart item element:', itemElement);

            // Remplir les informations du produit
            const img = itemElement.querySelector('img');
            img.src = item.image;
            img.alt = item.title;
            
            itemElement.querySelector('.item-title').textContent = item.title;
            itemElement.querySelector('.item-collection').textContent = `Collection: ${item.collection}`;
            itemElement.querySelector('.price').textContent = `${(item.price * item.quantity).toFixed(2)} €`;
            
            // Gestion de la quantité
            const quantityInput = itemElement.querySelector('input[type="number"]');
            quantityInput.value = item.quantity;
            
            // Événements
            itemElement.querySelector('.minus').addEventListener('click', () => {
                this.updateQuantity(index, item.quantity - 1);
            });
            
            itemElement.querySelector('.plus').addEventListener('click', () => {
                this.updateQuantity(index, item.quantity + 1);
            });
            
            quantityInput.addEventListener('change', (e) => {
                this.updateQuantity(index, parseInt(e.target.value));
            });
            
            itemElement.querySelector('.remove-item').addEventListener('click', () => {
                this.removeItem(index);
            });
            
            container.appendChild(itemElement);
        });
        
        this.updateTotals();
    }
    
    updateQuantity(index, newQuantity) {
        if (newQuantity === 0) {
            this.removeItem(index);
        } else if (newQuantity > 0) {
            this.cart[index].quantity = newQuantity;
            this.saveCart();
            this.renderCart();
        }
        // Si newQuantity est < 0, on ne fait rien (normalement pas possible avec input type="number" min="1")
    }
    
    removeItem(index) {
        this.cart.splice(index, 1);
        this.saveCart();
        this.renderCart();
    }
    
    updateTotals() {
        const subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const total = subtotal;
        
        document.getElementById('subtotal').textContent = `${subtotal.toFixed(2)} €`;
        document.getElementById('total').textContent = `${total.toFixed(2)} €`;
    }
    
    saveCart() {
        localStorage.setItem('cart', JSON.stringify(this.cart));
    }
    
    initPayPal() {
        if (!this.cart.length) return;
        
        paypal.Buttons({
            createOrder: (data, actions) => {
                const total = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: total.toFixed(2),
                            currency_code: 'EUR'
                        }
                    }]
                });
            },
            onApprove: (data, actions) => {
                return actions.order.capture().then((details) => {
                    // Vider le panier après paiement réussi
                    this.cart = [];
                    this.saveCart();
                    this.renderCart();
                    
                    alert('Paiement effectué avec succès ! Merci de votre achat.');
                });
            },
            onError: (err) => {
                console.error('Erreur lors du paiement:', err);
                alert('Une erreur est survenue lors du paiement. Veuillez réessayer.');
            }
        }).render('#paypal-button-container');
    }
    
    // Méthode pour vider le panier et le stockage local
    // clearCart() {
    //     this.cart = [];
    //     this.saveCart();
    //     this.renderCart();
    //     this.initPayPal(); // Réinitialiser PayPal si le panier est vidé
    // }
    
    // Méthode pour ajouter un produit au panier
    addToCart(product) {
        const existingItem = this.cart.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.cart.push({
                ...product,
                quantity: 1
            });
        }
        
        this.saveCart();
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    console.log('cart.js DOMContentLoaded'); // Ajout pour débogage
    
    // Toujours initialiser CartManager quand le DOM est prêt
    window.cartManager = new CartManager();
    console.log('CartManager instance created.', window.cartManager); // Ajout pour débogage

    // Initialiser la vue complète du panier uniquement sur la page panier
    const cartListElement = document.getElementById('cart-list');
    const cartSummaryElement = document.getElementById('cart-summary');
    
    console.log('Cart list element found:', cartListElement); // Ajout pour débogage
    console.log('Cart summary element found:', cartSummaryElement); // Ajout pour débogage

    if (cartListElement && cartSummaryElement) {
        console.log('On the cart page, initializing full view.'); // Ajout pour débogage
        window.cartManager.init(); // init() contient renderCart(), initPayPal(), etc.
    }

    // Attacher l'événement au bouton Passer au Paiement
    const checkoutButton = document.getElementById('checkout-button');
    if (checkoutButton) {
        checkoutButton.addEventListener('click', () => {
            console.log('Bouton Passer au Paiement cliqué');
            // Rediriger vers la page de paiement
            window.location.href = 'index.php?page=Paiement'; 
        });
    }
}); 
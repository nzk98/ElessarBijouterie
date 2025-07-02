document.addEventListener('DOMContentLoaded', function() {
    // Gestion des boutons de quantité
    const quantityButtons = document.querySelectorAll('.quantity-btn');
    
    quantityButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });

    // Gestion des boutons de suppression
    const removeButtons = document.querySelectorAll('.remove-item');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet article du panier ?')) {
                e.preventDefault();
            }
        });
    });

    // Animation des boutons
    const addToCartButtons = document.querySelectorAll('.btn-add-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.classList.add('added');
            setTimeout(() => {
                this.classList.remove('added');
            }, 1000);
        });
    });

    // Mise à jour du compteur du panier
    function updateCartCounter() {
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            // Le compteur est mis à jour côté serveur, pas besoin de JavaScript ici
            // Cette fonction peut être utilisée pour des mises à jour AJAX futures
        }
    }

    // Initialisation
    updateCartCounter();
}); 
// Script pour gérer uniquement l'affichage du compteur du panier dans le header

// Suppression de toute mise à jour JS du compteur du panier

document.addEventListener('DOMContentLoaded', () => {
    // Fonction pour lire le panier depuis localStorage et mettre à jour le compteur
    function updateHeaderCartCount() {
        console.log('Executing updateHeaderCartCount...'); // Debugging
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const count = cart.reduce((sum, item) => sum + item.quantity, 0);
        const cartCountElement = document.querySelector('.cart-count'); // Sélectionnez l'élément du compteur

        console.log('Cart data from localStorage:', cart); // Debugging
        console.log('Calculated count:', count); // Debugging
        console.log('Cart count element found:', cartCountElement); // Debugging

        if (cartCountElement) {
            // Ajouter un petit délai pour voir si un autre script écrase la valeur
            setTimeout(() => {
                console.log('Updating counter text with calculated count:', count); // Debugging
                cartCountElement.textContent = count;
                // Rendre le compteur visible s'il y a des articles
                // Utiliser le display de base si count > 0, sinon none
                cartCountElement.style.display = (count > 0) ? '' : 'none';
            }, 50); // Délai de 50ms
       
        } else {
            console.error('Cart count element (.cart-count) not found in header.');
        }
    }

    // Exécuter la mise à jour du compteur au chargement de chaque page
    updateHeaderCartCount();

    // Rendre la fonction accessible globalement pour être appelée par d'autres scripts (comme cart.js)
    window.updateHeaderCartCount = updateHeaderCartCount;
}); 
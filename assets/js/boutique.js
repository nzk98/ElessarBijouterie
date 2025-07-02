// Gestion des filtres et du tri
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
    if (sortSelect) {
        sortSelect.addEventListener('change', filterProducts);
    }

    // Animation des boutons d'ajout au panier
    const addToCartButtons = document.querySelectorAll('.btn-add-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            button.classList.add('added');
            setTimeout(() => {
                button.classList.remove('added');
            }, 1000);
        });
    });

    // Gestion des filtres (soumission automatique)
    const filterInputs = document.querySelectorAll('.filter-option input');
    filterInputs.forEach(input => {
        input.addEventListener('change', () => {
            document.querySelector('form').submit();
        });
    });
}); 
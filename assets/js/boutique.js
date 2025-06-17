// Gestion des filtres et du tri
document.addEventListener('DOMContentLoaded', () => {
    // Gestion des filtres
    const filterInputs = document.querySelectorAll('.filter-option input');
    filterInputs.forEach(input => {
        input.addEventListener('change', () => {
            document.querySelector('form').submit();
        });
    });

    // Gestion du tri
    const sortSelect = document.getElementById('sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            document.querySelector('form').submit();
        });
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
}); 
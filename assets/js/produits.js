document.addEventListener('DOMContentLoaded', function() {
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');
    const quantityDisplay = document.getElementById('quantity-display');
    const quantiteInput = document.getElementById('quantite-input');

    let quantite = 1;

    if (minusBtn && plusBtn && quantityDisplay && quantiteInput) {
        minusBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (quantite > 1) quantite--;
            quantityDisplay.textContent = quantite;
            quantiteInput.value = quantite;
        });
        plusBtn.addEventListener('click', function(e) {
            e.preventDefault();
            quantite++;
            quantityDisplay.textContent = quantite;
            quantiteInput.value = quantite;
        });
    }

    // Gestion des miniatures d'images
    const thumbnails = document.querySelectorAll('.thumbnail-img');
    const mainImage = document.getElementById('product-image');

    if (thumbnails.length > 0 && mainImage) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                mainImage.src = this.src;
            });
        });
    }
});

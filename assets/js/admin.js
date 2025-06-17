function deleteCreationImage(creationId, imagePath, button) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        fetch('index.php?page=AdminFormCreation&action=deleteImage', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                creation_id: creationId,
                image_path: imagePath
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer l'élément image-item du DOM
                button.closest('.image-item').remove();
            } else {
                alert('Erreur lors de la suppression de l\'image');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression de l\'image');
        });
    }
}

// Fonction pour supprimer une image d'article
function deleteArticleImage(articleId, imagePath, button) {
    if (confirm('Voulez-vous vraiment supprimer cette image ?')) {
        fetch('index.php?page=AdminFormArticle&action=deleteImage', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                article_id: articleId,
                image_path: imagePath
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.closest('.image-item').remove();
            } else {
                alert('Erreur lors de la suppression de l\'image');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression de l\'image');
        });
    }
} 
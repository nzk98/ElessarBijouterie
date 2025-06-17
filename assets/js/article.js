document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const articleId = urlParams.get('id');

    const articleTitle = document.getElementById('article-title');
    const articleDate = document.getElementById('article-date');
    const articleCategory = document.getElementById('article-category');
    const articleImage = document.getElementById('article-image');
    const articleContent = document.getElementById('article-content');

    // Attendre que window.blogArticles soit disponible
    const loadArticle = () => {
        if (window.blogArticles) {
            const article = window.blogArticles.find(a => a.id === parseInt(articleId));

            if (!article) {
                // console.error('Article non trouvé'); // Afficher une erreur si l'article n'existe pas
                // Afficher un message d'erreur ou rediriger
                return;
            }

            articleTitle.textContent = article.title;
            articleDate.textContent = article.date;
            articleCategory.textContent = article.category.charAt(0).toUpperCase() + article.category.slice(1);
            articleImage.src = article.image;
            articleImage.alt = article.title;
            articleContent.innerHTML = article.content; // Utiliser innerHTML pour le contenu HTML
        } else {
            // Si window.blogArticles n'est pas encore défini, réessayer après un court délai
            setTimeout(loadArticle, 50);
        }
    };

    loadArticle();
});

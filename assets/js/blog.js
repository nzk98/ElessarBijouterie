// Données des articles (à remplacer par une vraie API)
const articles = [
    {
        id: 1,
        title: "L'Art de la Création de Bijoux",
        category: "inspirations",
        excerpt: "Découvrez les secrets de la création de bijoux artisanaux et l'inspiration derrière nos dernières collections...",
        image: "assets/images/article1.jpg",
        date: "15 Mars 2024",
        year: "2024",
        content: "<p>Contenu détaillé de l'article sur l'Art de la Création de Bijoux...</p>"
    },
    {
        id: 2,
        title: "Les Tendances 2024",
        category: "actualites",
        excerpt: "Les nouvelles tendances en matière de bijoux pour cette année 2024...",
        image: "assets/images/article2.jpg",
        date: "10 Mars 2024",
        year: "2024",
        content: "<p>Contenu détaillé de l'article sur les Tendances 2024...</p>"
    },
    {
        id: 3,
        title: "Nouvelle Collection Printemps",
        category: "collections",
        excerpt: "Découvrez notre nouvelle collection de bijoux pour le printemps 2024...",
        image: "assets/images/article3.jpg",
        date: "5 Mars 2024",
        year: "2024",
        content: "<p>Contenu détaillé de l'article sur la Nouvelle Collection Printemps...</p>"
    }
];

// Exporter les articles pour qu'ils soient accessibles globalement
window.blogArticles = articles;

class BlogManager {
    constructor() {
        this.articles = articles;
        this.filteredArticles = [...articles];
        this.filters = {
            categories: new Set(),
            archives: new Set()
        };
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.renderArticles();
    }
    
    bindEvents() {
        // Gestion des filtres
        document.querySelectorAll('.filter-option input').forEach(input => {
            input.addEventListener('change', () => this.handleFilterChange(input));
        });
        
        // Gestion du tri
        document.getElementById('sort').addEventListener('change', (e) => this.handleSort(e.target.value));
    }
    
    handleFilterChange(input) {
        const filterType = input.name === 'category' ? 'categories' : 'archives';
        
        if (input.checked) {
            this.filters[filterType].add(input.value);
        } else {
            this.filters[filterType].delete(input.value);
        }
        
        this.applyFilters();
    }
    
    applyFilters() {
        this.filteredArticles = this.articles.filter(article => {
            const categoryMatch = this.filters.categories.size === 0 || 
                                this.filters.categories.has(article.category);
            const archiveMatch = this.filters.archives.size === 0 || 
                               this.filters.archives.has(article.year);
            
            return categoryMatch && archiveMatch;
        });
        
        this.renderArticles();
    }
    
    handleSort(sortValue) {
        switch(sortValue) {
            case 'recent':
                this.filteredArticles.sort((a, b) => new Date(b.date) - new Date(a.date));
                break;
            case 'popular':
                this.filteredArticles.sort((a, b) => b.id - a.id);
                break;
        }
        
        this.renderArticles();
    }
    
    renderArticles() {
        const container = document.getElementById('articles-grid');
        const template = document.getElementById('article-template');
        
        // Vider le conteneur
        container.innerHTML = '';
        
        // Afficher les articles filtrés
        this.filteredArticles.forEach(article => {
            const articleElement = template.content.cloneNode(true);
            
            // Remplir les informations de l'article
            const img = articleElement.querySelector('img');
            img.src = article.image;
            img.alt = article.title;
            
            articleElement.querySelector('.article-category').textContent = 
                article.category.charAt(0).toUpperCase() + article.category.slice(1);
            articleElement.querySelector('.article-title').textContent = article.title;
            articleElement.querySelector('.article-excerpt').textContent = article.excerpt;
            articleElement.querySelector('.article-date').textContent = article.date;
            
            // Lien vers l'article
            const readMoreBtn = articleElement.querySelector('.btn-read-more');
            readMoreBtn.href = `index.php?page=Article&id=${article.id}`;
            
            container.appendChild(articleElement);
        });
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    // Initialiser BlogManager uniquement sur la page blog
    if (document.getElementById('articles-grid')) {
        new BlogManager();
    }
}); 
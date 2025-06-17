// Attendre que le DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des variables globales
    const burgerMenu = document.querySelector('.burger-menu');
    const navLinks = document.querySelector('.nav-links');
    
    // Vérification que les éléments existent
    if (!burgerMenu || !navLinks) {
        console.error('Éléments du menu non trouvés');
        return;
    }
    
    // Gestion du menu burger
    burgerMenu.addEventListener('click', function() {
        console.log('Menu burger cliqué');
        this.classList.toggle('active');
        navLinks.classList.toggle('active');
    });

    // Fermer le menu mobile lors du clic sur un lien
    const links = navLinks.querySelectorAll('a');
    links.forEach(function(link) {
        link.addEventListener('click', function() {
            burgerMenu.classList.remove('active');
            navLinks.classList.remove('active');
        });
    });

    // Gestion du scroll pour la navbar
    const nav = document.querySelector('.main-nav');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });

    // Fonction pour charger les articles du blog
    const loadBlogPosts = async () => {
        try {
            // Vérifier si nous sommes sur la page d'accueil et si les articles n'ont pas déjà été chargés
            const postsGrid = document.querySelector('.posts-grid');
            if (!postsGrid || postsGrid.children.length > 0) return;

            // Utiliser les articles du blog
            const articles = [
                {
                    id: 1,
                    title: "L'Art de la Création de Bijoux",
                    category: "inspirations",
                    excerpt: "Découvrez les secrets de la création de bijoux artisanaux et l'inspiration derrière nos dernières collections...",
                    image: "assets/images/article1.jpg",
                    date: "15 Mars 2024"
                },
                {
                    id: 2,
                    title: "Les Tendances 2024",
                    category: "actualites",
                    excerpt: "Les nouvelles tendances en matière de bijoux pour cette année 2024...",
                    image: "assets/images/article2.jpg",
                    date: "10 Mars 2024"
                },
                {
                    id: 3,
                    title: "Nouvelle Collection Printemps",
                    category: "collections",
                    excerpt: "Découvrez notre nouvelle collection de bijoux pour le printemps 2024...",
                    image: "assets/images/article3.jpg",
                    date: "5 Mars 2024"
                }
            ];
            
            // Afficher les 3 derniers articles
            articles.slice(0, 3).forEach(post => {
                const postElement = createPostElement(post);
                postsGrid.appendChild(postElement);
            });
        } catch (error) {
            console.error('Erreur lors du chargement des articles:', error);
        }
    };

    // Fonction pour créer un élément article
    const createPostElement = (post) => {
        const article = document.createElement('article');
        article.className = 'post-card';
        
        // Limiter la longueur du texte
        const excerpt = post.excerpt.length > 100 ? post.excerpt.substring(0, 100) + '...' : post.excerpt;
        
        article.innerHTML = `
            <img src="${post.image}" alt="${post.title}">
            <div class="post-content">
                <span class="post-category">${post.category}</span>
                <h3>${post.title}</h3>
                <p>${excerpt}</p>
                <div class="post-meta">
                    <span class="post-date">${post.date}</span>
                </div>
                <a href="index.php?page=Article&id=${post.id}" class="read-more">Lire la suite</a>
            </div>
        `;
        
        return article;
    };

    // Gestion du panier
    const cart = {
        items: [],
        total: 0,
        
        // Ajouter un produit au panier
        addItem(product) {
            this.items.push(product);
            this.calculateTotal();
            this.updateCartUI();
        },
        
        // Retirer un produit du panier
        removeItem(productId) {
            this.items = this.items.filter(item => item.id !== productId);
            this.calculateTotal();
            this.updateCartUI();
        },
        
        // Calculer le total
        calculateTotal() {
            this.total = this.items.reduce((sum, item) => sum + item.price, 0);
        },
        
        // Mettre à jour l'interface du panier
        updateCartUI() {
            // const cartCount = document.querySelector('.cart-count');
            // if (cartCount) {
            //     cartCount.textContent = this.items.length;
            // }
        }
    };

    // Initialisation du site
    const init = () => {
        loadBlogPosts();
        // cart.updateCartUI();
    };

    // Lancer l'initialisation
    init();
}); 
// Fonction d'initialisation du menu burger
function initBurgerMenu() {
    console.log('Initialisation du menu burger');
    
    // Initialisation des variables globales
    const burgerMenu = document.querySelector('.burger-menu');
    const navLinks = document.querySelector('.nav-links');
    
    console.log('Éléments trouvés:', {
        burgerMenu: burgerMenu,
        navLinks: navLinks
    });
    
    // Vérification que les éléments existent
    if (!burgerMenu || !navLinks) {
        console.error('Éléments du menu non trouvés');
        return;
    }
    
    // Supprimer les anciens event listeners pour éviter les doublons
    const newBurgerMenu = burgerMenu.cloneNode(true);
    burgerMenu.parentNode.replaceChild(newBurgerMenu, burgerMenu);
    
    const newNavLinks = navLinks.cloneNode(true);
    navLinks.parentNode.replaceChild(newNavLinks, navLinks);
    
    // Récupérer les nouveaux éléments
    const freshBurgerMenu = document.querySelector('.burger-menu');
    const freshNavLinks = document.querySelector('.nav-links');
    
    // Gestion du menu burger
    freshBurgerMenu.addEventListener('click', function(e) {
        console.log('Menu burger cliqué');
        e.preventDefault();
        e.stopPropagation();
        
        this.classList.toggle('active');
        freshNavLinks.classList.toggle('active');
        
        console.log('Classes après clic:', {
            burgerActive: this.classList.contains('active'),
            navActive: freshNavLinks.classList.contains('active')
        });
    });

    // Fermer le menu mobile lors du clic sur un lien
    const links = freshNavLinks.querySelectorAll('a');
    links.forEach(function(link) {
        link.addEventListener('click', function() {
            console.log('Lien cliqué, fermeture du menu');
            freshBurgerMenu.classList.remove('active');
            freshNavLinks.classList.remove('active');
        });
    });

    // Fermer le menu lors du clic en dehors
    document.addEventListener('click', function(e) {
        if (!freshBurgerMenu.contains(e.target) && !freshNavLinks.contains(e.target)) {
            freshBurgerMenu.classList.remove('active');
            freshNavLinks.classList.remove('active');
        }
    });
}

// Attendre que le DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', function() {
    console.log('main.js chargé - DOM ready');
    
    // Initialiser le menu burger
    initBurgerMenu();
    
    // Réinitialiser le menu burger après un délai pour s'assurer qu'il fonctionne
    // même si d'autres scripts sont chargés après
    setTimeout(function() {
        console.log('Réinitialisation du menu burger après délai');
        initBurgerMenu();
    }, 1000);

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

    // Initialisation du site
    const init = () => {
        loadBlogPosts();
    };

    // Lancer l'initialisation
    init();

    // Affiche la pop-in si l'utilisateur n'a pas encore choisi
    showCartCookiePopin();

    // Initialisation du carrousel fluide
    initCarousel();
});

// Fonction d'initialisation du carrousel
function initCarousel() {
    console.log('Initialisation du carrousel...');
    
    const carousel = document.getElementById('main-carousel');
    if (!carousel) {
        console.error('Carrousel non trouvé: #main-carousel');
        return;
    }
    console.log('Carrousel trouvé:', carousel);

    const items = carousel.querySelectorAll('.carousel-item');
    const indicators = carousel.querySelectorAll('.carousel-indicator');
    const prevBtn = carousel.querySelector('.carousel-control.prev');
    const nextBtn = carousel.querySelector('.carousel-control.next');
    
    console.log('Éléments trouvés:', {
        items: items.length,
        indicators: indicators.length,
        prevBtn: !!prevBtn,
        nextBtn: !!nextBtn
    });
    
    let currentSlide = 0;
    const totalSlides = items.length;
    
    if (totalSlides === 0) {
        console.error('Aucune slide trouvée dans le carrousel');
        return;
    }

    // Trouver la slide active initiale
    const activeItem = carousel.querySelector('.carousel-item.active');
    if (activeItem) {
        currentSlide = parseInt(activeItem.dataset.slide) || 0;
        console.log('Slide active initiale:', currentSlide);
    }

    // Fonction pour changer de slide
    function goToSlide(index) {
        console.log('Changement vers la slide:', index);
        
        // Masquer la slide actuelle
        items[currentSlide].classList.remove('active');
        if (indicators[currentSlide]) {
            indicators[currentSlide].classList.remove('active');
        }
        
        // Mettre à jour l'index
        currentSlide = index;
        
        // Afficher la nouvelle slide
        items[currentSlide].classList.add('active');
        if (indicators[currentSlide]) {
            indicators[currentSlide].classList.add('active');
        }
        
        // Mettre à jour l'URL sans recharger la page
        const url = new URL(window.location);
        url.searchParams.set('slide', currentSlide);
        window.history.replaceState({}, '', url);
    }

    // Fonction pour aller à la slide suivante
    function nextSlide() {
        const nextIndex = (currentSlide + 1) % totalSlides;
        goToSlide(nextIndex);
    }

    // Fonction pour aller à la slide précédente
    function prevSlide() {
        const prevIndex = (currentSlide - 1 + totalSlides) % totalSlides;
        goToSlide(prevIndex);
    }

    // Événements pour les boutons de navigation
    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            prevSlide();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            nextSlide();
        });
    }

    // Événements pour les indicateurs
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', function(e) {
            e.preventDefault();
            goToSlide(index);
        });
    });

    // Navigation au clavier
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            prevSlide();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            nextSlide();
        }
    });

    console.log('Carrousel initialisé avec succès');
}

function getCookie(name) {
    const value = "; " + document.cookie;
    const parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
    return null;
}

function showCartCookiePopin() {
    const consent = getCookie('cart_cookie_consent');
    const consentDate = getCookie('cart_cookie_consent_date');
    const now = Date.now();
    const sixMonths = 6 * 30 * 24 * 60 * 60 * 1000; // 6 mois en ms (~180 jours)
    if (!consent || !consentDate || (now - (parseInt(consentDate) * 1000) > sixMonths)) {
        document.getElementById('cookie-consent-popin').classList.add('show');
    }
}

document.getElementById('accept-cart-cookie').onclick = function() {
    document.getElementById('cookie-consent-popin').classList.remove('show');
    fetch('index.php?page=Panier&accept_cart_cookie=1', {method: 'POST'});
};

document.getElementById('refuse-cart-cookie').onclick = function() {
    document.getElementById('cookie-consent-popin').classList.remove('show');
    fetch('index.php?page=Panier&refuse_cart_cookie=1', {method: 'POST'});
}; 
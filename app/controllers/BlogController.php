<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class BlogController {
	public function index() {
		$pageStyle = "blog.css";
		// $jsFile = ["cart-counter.js","blog.js"];
		$jsFile = ["blog.js"];

		// Récupérer les catégories
		require_once __DIR__ . '/../models/CategorieArticle.php';
		$categories = CategorieArticle::getAll();

		// Récupérer les articles avec filtres si nécessaire
		require_once __DIR__ . '/../models/Article.php';
		
		// Récupération sécurisée des filtres
		$selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];
		$selectedYears = isset($_GET['years']) ? $_GET['years'] : [];
		$sort = isset($_GET['sort']) ? $_GET['sort'] : 'recent';

		// S'assurer que les tableaux sont bien des tableaux
		if (!is_array($selectedCategories)) {
			$selectedCategories = [];
		}
		if (!is_array($selectedYears)) {
			$selectedYears = [];
		}

		// Récupérer les années disponibles
		$years = Article::getAvailableYears();
		
		// Récupérer les articles filtrés
		$articles = Article::getFilteredArticles($selectedCategories, $selectedYears, $sort);

		// Inclusion du header commun
		$pageTitle = "Blog - Conseils et actualités de la bijouterie artisanale";
		$metaDesc = "Découvrez mes articles de blog sur l'univers de la bijouterie, mes conseils, inspirations et actualités Elessard.";
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
        include_once __DIR__ . '/../views/blog.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}
}



?>
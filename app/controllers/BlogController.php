<?php


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
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
        include_once __DIR__ . '/../views/blog.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}
}



?>
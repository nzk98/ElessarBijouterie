<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ArticleController {
	public function index() {
		$pageStyle = "article.css";
		$jsFile = ["article.js"];

		// Récupérer l'ID de l'article depuis l'URL
		$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

		if ($articleId <= 0) {
			header('Location: index.php?page=Accueil');
			exit();
		}

		// Charger l'article
		require_once __DIR__ . '/../models/Article.php';
		$article = Article::getArticleById($articleId);

		if (!$article) {
			header('Location: index.php?page=Accueil');
			exit();
		}

		// Inclusion du header commun
		$pageTitle = isset($article['Titre_Article']) ? $article['Titre_Article'] . " - Blog Elessard Bijouterie" : "Article de blog - Elessard Bijouterie";
		$metaDesc = isset($article['description']) 
			? mb_substr(strip_tags($article['description']), 0, 157) . (mb_strlen(strip_tags($article['description'])) > 157 ? '…' : '')
			: "Détail d'un article de blog sur la bijouterie artisanale. Découvrez mes conseils, inspirations et mon univers créatif.";
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
		include_once __DIR__ . '/../views/article.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}
}   



?>
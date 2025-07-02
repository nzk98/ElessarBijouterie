<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

class ProduitsController {
	public function index() {
		$pageStyle = "produits.css";
		$jsFile = [ "boutique.js", "produits.js"];

		// Récupérer l'ID du produit depuis l'URL
		$creation = null;
		if (isset($_GET['id'])) {
			require_once __DIR__ . '/../models/Creation.php';
			$creation = Creation::getCreationById($_GET['id']);
		}

		// Inclusion du header commun
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
		include_once __DIR__ . '/../views/produits.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';

		$metaDesc = $creation && isset($creation['description']) ? $creation['description'] : "Découvrez mes créations de bijoux uniques, faits main dans mon atelier Elessard.";

		$pageTitle = $creation && isset($creation['nom']) ? $creation['nom'] . " - Bijou unique Elessard" : "Création artisanale - Elessard Bijouterie";
	}
}



?>
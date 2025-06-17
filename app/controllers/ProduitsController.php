<?php

class ProduitsController {
	public function index() {
		$pageStyle = "produits.css";
		// $jsFile = ["cart-counter.js", "cart.js", "boutique.js", "produits.js"];
		$jsFile = ["cart.js", "boutique.js", "produits.js"];

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
	}
}



?>
<?php

class PanierController {
	public function index() {
		require_once __DIR__ . '/../models/Panier.php';
		$pageStyle = "panier.css";
		$jsFile = [];

		// Gestion des actions du panier
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['add_to_cart'])) {
				Panier::addToCart(
					$_POST['id'],
					$_POST['nom'],
					$_POST['prix'],
					$_POST['image'],
					$_POST['quantite'] ?? 1
				);
			} elseif (isset($_POST['remove_from_cart'])) {
				Panier::removeFromCart($_POST['id']);
			} elseif (isset($_POST['update_quantity'])) {
				Panier::updateQuantity($_POST['id'], $_POST['quantite'] ?? 1);
			}
		}

		$panier = Panier::getPanier();

		// Inclusion du header commun
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
		include_once __DIR__ . '/../views/panier.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}
}

?>
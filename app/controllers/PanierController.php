<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

class PanierController {
	public function index() {
		require_once __DIR__ . '/../models/Panier.php';
		$pageStyle = "panier.css";
		$jsFile = [];

		// Consentement accepté
		if (isset($_GET['accept_cart_cookie'])) {
			setcookie('cart_cookie_consent', 'accepted', time() + 30 * 24 * 3600, "/"); // 30 jours
			setcookie('cart_cookie_consent_date', time(), time() + 30 * 24 * 3600, "/");
			// Crée le cookie panier si besoin
			if (isset($_SESSION['panier'])) {
				setcookie('panier', json_encode($_SESSION['panier']), time() + 30*24*3600, "/");
			}
			exit;
		}

		// Consentement refusé
		if (isset($_GET['refuse_cart_cookie'])) {
			setcookie('cart_cookie_consent', 'refused', time() + 30 * 24 * 3600, "/");
			setcookie('cart_cookie_consent_date', time(), time() + 30 * 24 * 3600, "/");
			setcookie('panier', '', time() - 3600, "/");
			$_SESSION['panier'] = [];
			exit;
		}

		// À l'ouverture de la page, si le cookie existe et que la session panier est vide, on restaure
		if (empty($_SESSION['panier']) && isset($_COOKIE['panier'])) {
			$_SESSION['panier'] = json_decode($_COOKIE['panier'], true);
		}

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
			// Met à jour le cookie panier après modification
			if (isset($_COOKIE['cart_cookie_consent']) && $_COOKIE['cart_cookie_consent'] === 'accepted') {
				setcookie('panier', json_encode($_SESSION['panier']), time() + 30*24*3600, "/");
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
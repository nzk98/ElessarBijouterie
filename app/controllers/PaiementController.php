<?php


class PaiementController {
	public function index() {
		$pageStyle = "paiement.css";
		// $jsFile = ["cart-counter.js","paiement.js"];
		$jsFile = ["paiement.js"];

		// Traitement du paiement (simulation ici)
		if (isset($_POST['valider_paiement'])) {
			require_once __DIR__ . '/../models/Creation.php';
			session_start();
			if (isset($_SESSION['panier'])) {
				foreach ($_SESSION['panier'] as $item) {
					$creation = Creation::getCreationById($item['id']);
					if ($creation) {
						$creation->decrementerStock($item['quantite']);
					}
				}
				// Vider le panier après paiement
				$_SESSION['panier'] = [];
				$message = "Merci pour votre commande ! Votre paiement a bien été pris en compte.";
			}
		}

		// Inclusion du header commun
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
		include_once __DIR__ . '/../views/paiement.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}
}



?>
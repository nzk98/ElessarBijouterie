<?php


class ConnexionController {
	public function index() {
		$pageStyle = "auth.css";
		$jsFile = ["auth.js","main.js"];

		// Traitement du formulaire d'inscription
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['password'], $_POST['civilite'])) {
			require_once __DIR__ . '/../models/Utilisateur.php';

			$utilisateur = new Utilisateur([
				'nom_utilisateur' => $_POST['nom'],
				'prenom_utilisateur' => $_POST['prenom'],
				'email_utilisateur' => $_POST['email'],
				'mot_de_passe' => $_POST['password'],
				'civilite' => $_POST['civilite'],
				'telephone' => $_POST['telephone'] ?? ''
			]);
			$utilisateur->setMotDePasse($_POST['password']); // pour le hash

			if ($utilisateur->inscription()) {
				$inscriptionMessage = "<div style='color:green'>Inscription réussie !</div>";
			} else {
				$inscriptionMessage = "<div style='color:red'>Erreur lors de l'inscription.</div>";
			}
		}

		// Traitement du formulaire de connexion
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password']) && !isset($_POST['nom'])) {
			require_once __DIR__ . '/../models/Utilisateur.php';
			if (Utilisateur::se_connecter($_POST['email'], $_POST['password'])) {
				// Redirection vers le dashboard
				header('Location: index.php?page=Dashboard');
				exit();
			} else {
				$connexionMessage = "<div style='color:red'>Identifiants incorrects.</div>";
			}
		}

		// Inclusion du header commun
		include_once __DIR__ . '/../includes/header.php';

		// Affichage du message d'inscription si présent
		if (isset($inscriptionMessage)) {
			echo $inscriptionMessage;
		}

		// Affichage du message de connexion si présent
		if (isset($connexionMessage)) {
			echo $connexionMessage;
		}

		// Inclusion de la vue principale
		include_once __DIR__ . '/../views/connexionInscription.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}
}



?>
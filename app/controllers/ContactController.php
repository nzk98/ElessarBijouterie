<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class ContactController {
	private array $errors = [];

	public function index() {
		$pageStyle = "contact.css";
		$jsFile = ["contact.js"];

		// Inclusion du header commun
		$pageTitle = "Contact - Elessard Bijouterie, créatrice artisanale";
		$metaDesc = "Contactez-moi pour toute question ou demande de création. Je vous répondrai dans les plus brefs délais.";
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
		include_once __DIR__ . '/../views/contact.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}

	/**
	 * Traite l'envoi du formulaire de contact et renvoie une réponse JSON.
	 */
	public function processForm() {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405); // Method Not Allowed
			echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
			return;
		}

		// Récupération des données
		$nom = trim($_POST['nom'] ?? '');
		$prenom = trim($_POST['prenom'] ?? '');
		$email = trim($_POST['email'] ?? '');
		$sujet = trim($_POST['sujet'] ?? '');
		$message = trim($_POST['message'] ?? '');

		// Validation
		if (empty($nom)) $this->errors['nom'] = 'Le nom est obligatoire.';
		if (empty($prenom)) $this->errors['prenom'] = 'Le prénom est obligatoire.';
		if (empty($message)) $this->errors['message'] = 'Le message ne peut pas être vide.';
		if (empty($sujet)) $this->errors['sujet'] = 'Veuillez sélectionner un sujet.';

		if (empty($email)) {
			$this->errors['email'] = 'L\'adresse email est obligatoire.';
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->errors['email'] = 'L\'adresse email n\'est pas valide.';
		}

		// Si des erreurs sont trouvées
		if ($this->hasErrors()) {
			http_response_code(422); // Unprocessable Entity
			echo json_encode(['status' => 'error', 'errors' => $this->getErrors()]);
			return;
		}

		require_once __DIR__ . '/../models/Contact.php';
		
		// Envoi de l'email via le modèle
		$contactModel = new Contact();
		$isSent = $contactModel->sendContactEmail($nom, $prenom, $email, $sujet, $message);

		if ($isSent) {
			http_response_code(200);
			echo json_encode(['status' => 'success', 'message' => 'Votre message a bien été envoyé. Nous vous répondrons bientôt.']);
		} else {
			http_response_code(500); // Internal Server Error
			echo json_encode(['status' => 'error', 'message' => 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.']);
		}
	}

	public function getErrors(): array { return $this->errors; }
	public function hasErrors(): bool { return !empty($this->errors); }
}




?>
<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

class ConnexionController {
	private $errors = [];
	private $success = [];

	public function index() {
		$pageStyle = "auth.css";
		$jsFile = ["auth.js","main.js"];

		$showRegister = false;

		// Traitement du formulaire d'inscription
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['password'], $_POST['civilite'])) {
			$this->processInscription();
			if (!empty($this->errors)) {
				$showRegister = true;
			}
		}

		// Traitement du formulaire de connexion
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password']) && !isset($_POST['nom'])) {
			$this->processConnexion();
		}

		// Inclusion du header commun
		$pageTitle = "Connexion ou inscription - Espace client Elessard";
		$metaDesc = "Connectez-vous ou créez un compte sur ma bijouterie Elessard pour accéder à votre espace personnel et suivre vos commandes.";
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
		$showRegisterVar = $showRegister; // pour compatibilité avec l'inclusion
		include __DIR__ . '/../views/connexionInscription.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}

	/**
	 * Formate un numéro de téléphone au format "06 06 06 06 06"
	 */
	private function formatPhoneNumber($phone): string {
		if (empty($phone)) {
			return '';
		}
		
		// Supprimer tous les caractères non numériques
		$numbers = preg_replace('/[^0-9]/', '', $phone);
		
		// Vérifier si c'est un numéro français valide (10 chiffres)
		if (strlen($numbers) === 10) {
			// Formater au format "06 06 06 06 06"
			return substr($numbers, 0, 2) . ' ' . 
				   substr($numbers, 2, 2) . ' ' . 
				   substr($numbers, 4, 2) . ' ' . 
				   substr($numbers, 6, 2) . ' ' . 
				   substr($numbers, 8, 2);
		}
		
		// Si ce n'est pas un numéro français valide, retourner tel quel
		return $phone;
	}

	/**
	 * Valide la complexité du mot de passe
	 * Doit contenir au moins : 2 majuscules, 1 chiffre, 1 caractère spécial
	 */
	private function validatePasswordComplexity($password): bool {
		// Compter les majuscules
		$uppercaseCount = preg_match_all('/[A-Z]/', $password);
		
		// Compter les chiffres
		$digitCount = preg_match_all('/[0-9]/', $password);
		
		// Compter les caractères spéciaux
		$specialCount = preg_match_all('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password);
		
		// Vérifier les conditions
		return $uppercaseCount >= 2 && $digitCount >= 1 && $specialCount >= 1;
	}

	private function processInscription() {
		// Vérification CSRF
		require_once __DIR__ . '/../models/CSRF.php';
		if (!CSRF::checkPostToken()) {
			$this->errors['general'] = "Erreur de sécurité. Veuillez réessayer.";
			return;
		}

		// Récupération des données
		$nom = trim($_POST['nom'] ?? '');
		$prenom = trim($_POST['prenom'] ?? '');
		$email = trim($_POST['email'] ?? '');
		$password = $_POST['password'] ?? '';
		$password_confirm = $_POST['password_confirm'] ?? '';
		$civilite = $_POST['civilite'] ?? '';
		$telephone = trim($_POST['telephone'] ?? '');

		// Validation simple
		if (empty($nom)) {
			$this->errors['nom'] = "Le nom est obligatoire.";
		} elseif (strlen($nom) < 2) {
			$this->errors['nom'] = "Le nom doit contenir au moins 2 caractères.";
		}

		if (empty($prenom)) {
			$this->errors['prenom'] = "Le prénom est obligatoire.";
		} elseif (strlen($prenom) < 2) {
			$this->errors['prenom'] = "Le prénom doit contenir au moins 2 caractères.";
		}

		if (empty($email)) {
			$this->errors['email'] = "L'email est obligatoire.";
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->errors['email'] = "L'email n'est pas valide.";
		} else {
			// Vérifier si l'email existe déjà
			require_once __DIR__ . '/../models/Utilisateur.php';
			if ($this->emailExists($email)) {
				$this->errors['email'] = "Cette adresse email est déjà utilisée.";
			}
		}

		if (empty($password)) {
			$this->errors['password'] = "Le mot de passe est obligatoire.";
		} elseif (strlen($password) < 8) {
			$this->errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
		} elseif (!$this->validatePasswordComplexity($password)) {
			$this->errors['password'] = "Le mot de passe doit contenir au moins 2 majuscules, 1 chiffre et 1 caractère spécial.";
		}

		if (empty($password_confirm)) {
			$this->errors['password_confirm'] = "La confirmation du mot de passe est obligatoire.";
		} elseif ($password !== $password_confirm) {
			$this->errors['password_confirm'] = "Les mots de passe ne correspondent pas.";
		}

		if (empty($civilite)) {
			$this->errors['civilite'] = "La civilité est obligatoire.";
		}

		// Validation du téléphone (optionnel)
		if (!empty($telephone)) {
			$numbers = preg_replace('/[^0-9]/', '', $telephone);
			if (strlen($numbers) !== 10) {
				$this->errors['telephone'] = "Le numéro de téléphone doit contenir 10 chiffres.";
			}
		}

		// Si aucune erreur, procéder à l'inscription
		if (empty($this->errors)) {
			require_once __DIR__ . '/../models/Utilisateur.php';

			// Formater le numéro de téléphone
			$telephoneFormatted = $this->formatPhoneNumber($telephone);

			$utilisateur = new Utilisateur([
				'nom_utilisateur' => $nom,
				'prenom_utilisateur' => $prenom,
				'email_utilisateur' => $email,
				'mot_de_passe' => $password,
				'civilite' => $civilite,
				'telephone' => $telephoneFormatted
			]);
			$utilisateur->setMotDePasse($password);

			if ($utilisateur->inscription()) {
				$this->success['inscription'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
				$_POST = []; // Réinitialiser les données du formulaire
			} else {
				$this->errors['general'] = "Erreur lors de l'inscription. Veuillez réessayer.";
			}
		}
	}

	private function processConnexion() {
		// Vérification CSRF
		require_once __DIR__ . '/../models/CSRF.php';
		if (!CSRF::checkPostToken()) {
			$this->errors['login_general'] = "Erreur de sécurité. Veuillez réessayer.";
			return;
		}

		$email = trim($_POST['email'] ?? '');
		$password = $_POST['password'] ?? '';

		// Validation simple
		if (empty($email)) {
			$this->errors['login_email'] = "L'email est obligatoire.";
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->errors['login_email'] = "L'email n'est pas valide.";
		}

		if (empty($password)) {
			$this->errors['login_password'] = "Le mot de passe est obligatoire.";
		}

		// Si aucune erreur de validation, tenter la connexion
		if (empty($this->errors)) {
			require_once __DIR__ . '/../models/Utilisateur.php';
			if (Utilisateur::se_connecter($email, $password)) {
				header('Location: index.php?page=Dashboard');
				exit();
			} else {
				$this->errors['login_general'] = "Email ou mot de passe incorrect.";
			}
		}
	}

	private function emailExists($email): bool {
		try {
			require_once __DIR__ . '/../models/Database.php';
			$db = Database::getInstance();
			$stmt = $db->prepare("SELECT COUNT(*) FROM utilisateurs WHERE Email_Utilisateur = :email");
			$stmt->execute([':email' => $email]);
			return $stmt->fetchColumn() > 0;
		} catch (PDOException $e) {
			return false;
		}
	}

	public function getErrors(): array {
		return $this->errors;
	}

	public function getSuccess(): array {
		return $this->success;
	}

	public function hasErrors(): bool {
		return !empty($this->errors);
	}

	public function hasSuccess(): bool {
		return !empty($this->success);
	}
}


?>
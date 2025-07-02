<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Utilisateur.php'; // Assurez-vous que le modèle Utilisateur est inclus

class ProfilUtilisateurController {
    private array $errors = [];
    private array $success = [];

    public function index() {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            header('Location: index.php?page=Connexion');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processProfileUpdate();
        }

        // Si le formulaire a été soumis et qu'il n'y a pas d'erreurs (succès)
        // on ne recharge pas les données de la BDD pour ne pas écraser les messages de succès
        if (!($_SERVER['REQUEST_METHOD'] === 'POST' && empty($this->errors))) {
            $this->loadUserData();
        }

        $pageStyle = "profilUtilisateur.css";
        $jsFile = "profilUtilisateur.js";

        // Inclusion du header commun
        include_once __DIR__ . '/../includes/header.php';

        // Inclusion de la vue principale
        include_once __DIR__  . '/../views/profilUtilisateur.php';

        // Inclusion du footer commun
        include_once __DIR__ . '/../includes/footer.php';
    }

    private function processProfileUpdate() {
        $userId = $_SESSION['user']['id'];
        
        // --- Validation des données ---
        $this->validateProfileData($_POST);

        if (!empty($this->errors)) {
            // S'il y a des erreurs, on les affiche, on ne continue pas.
            return;
        }

        // --- Préparation des données ---
        $formData = [
            'id_utilisateur' => $userId,
            'civilite' => $_POST['civilite'] ?? '',
            'nom_utilisateur' => trim($_POST['nom'] ?? ''),
            'prenom_utilisateur' => trim($_POST['prenom'] ?? ''),
            'email_utilisateur' => trim($_POST['email'] ?? ''),
            'telephone' => $this->formatPhoneNumber($_POST['telephone'] ?? ''),
            'adresse' => trim($_POST['adresse'] ?? ''),
            'code_postal' => trim($_POST['code_postal'] ?? ''),
            'ville' => trim($_POST['ville'] ?? '')
        ];
        if (isset($_SESSION['user']['ID_Adresse'])) {
            $formData['id_adresse'] = $_SESSION['user']['ID_Adresse'];
        }
        
        $utilisateurToUpdate = new Utilisateur($formData);

        if (!empty($_POST['password'])) {
            $utilisateurToUpdate->setMotDePasse($_POST['password']);
        }

        // --- Mise à jour ---
        if ($utilisateurToUpdate->gerer_profil()) {
            $this->success['update'] = "Votre profil a été mis à jour avec succès !";
            // Recharger les données pour mettre à jour la session
            $this->loadUserData();
        } else {
            $this->errors['general'] = "Une erreur est survenue lors de la mise à jour de votre profil. Veuillez réessayer.";
        }
    }

    private function validateProfileData(array $data) {
        // Nom & Prénom
        if (empty(trim($data['nom']))) $this->errors['nom'] = "Le nom est obligatoire.";
        if (empty(trim($data['prenom']))) $this->errors['prenom'] = "Le prénom est obligatoire.";

        // Email
        if (empty(trim($data['email']))) {
            $this->errors['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "L'email n'est pas valide.";
        }

        // Mot de passe (si renseigné)
        if (!empty($data['password'])) {
            if (strlen($data['password']) < 8) {
                $this->errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
            } elseif (!$this->validatePasswordComplexity($data['password'])) {
                $this->errors['password'] = "Le mot de passe doit contenir au moins 2 majuscules, 1 chiffre et 1 caractère spécial.";
            }
        }

        // Téléphone (si renseigné)
        if (!empty($data['telephone'])) {
            $numbers = preg_replace('/[^0-9]/', '', $data['telephone']);
            if (strlen($numbers) !== 10) {
                $this->errors['telephone'] = "Le numéro de téléphone doit contenir 10 chiffres.";
            }
        }
    }
    
    private function loadUserData() {
        $userId = $_SESSION['user']['id'];
        $userData = Utilisateur::getDetailedUtilisateurById($userId);

        if ($userData) {
            $_SESSION['user'] = array_merge($_SESSION['user'], $userData);
            if (isset($userData['ID_Adresse'])) {
                $_SESSION['user']['ID_Adresse'] = $userData['ID_Adresse'];
            }
        }
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

    public function getErrors(): array { return $this->errors; }
    public function getSuccess(): array { return $this->success; }
    public function hasErrors(): bool { return !empty($this->errors); }
    public function hasSuccess(): bool { return !empty($this->success); }
} 
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminRegisterController {
    private array $errors = [];
    private array $success = [];

    public function index() {
        // Seul un admin connecté peut accéder à cette page
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegistration();
        }

        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];

        // Inclusion du header commun
        include_once __DIR__ . '/../../includes/header.php';

        // Afficher la sidebar seulement si l'admin est connecté
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../../includes/admin_sidebar.php';
        }

        // Inclusion de la vue principale
        include __DIR__ . '/../../views/admin/adminRegister.php';

        // Inclusion du footer commun
        include_once __DIR__ . '/../../includes/footer.php';
    }

    private function handleRegistration() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        // Validation
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "L'email est invalide.";
        }
        
        // Validation du mot de passe selon le pattern demandé
        if (empty($password)) {
            $this->errors['password'] = "Le mot de passe est requis.";
        } elseif (strlen($password) < 8) {
            $this->errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
        } elseif (preg_match_all('/[A-Z]/', $password) < 2) {
            $this->errors['password'] = "Le mot de passe doit contenir au moins 2 majuscules.";
        } elseif (!preg_match('/[0-9]/', $password)) {
            $this->errors['password'] = "Le mot de passe doit contenir au moins 1 chiffre.";
        } elseif (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            $this->errors['password'] = "Le mot de passe doit contenir au moins 1 caractère spécial.";
        }
        
        if ($password !== $password_confirm) {
            $this->errors['password_confirm'] = "Les mots de passe ne correspondent pas.";
        }

        if (empty($this->errors)) {
            require_once __DIR__ . '/../../models/admin/Admin.php';
            $admin = new Admin();
            $admin->setEmail($email);
            $admin->setPassword($password);
            if ($admin->inscription()) {
                $this->success['register'] = "Inscription réussie ! Le nouvel administrateur peut maintenant se connecter.";
            } else {
                $this->errors['general'] = "Erreur lors de l'inscription. L'email est peut-être déjà utilisé.";
            }
        }
    }

    // Méthodes pour la vue
    public function getErrors(): array { return $this->errors; }
    public function getSuccess(): array { return $this->success; }
} 
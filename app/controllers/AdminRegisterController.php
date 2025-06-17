<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminRegisterController {
    public function index() {
        // Seul un admin connecté peut accéder à cette page
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }

        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];
        $errors = [];
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            // Validation
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email est invalide.";
            }
            if (empty($password) || strlen($password) < 8) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
            }
            if ($password !== $password_confirm) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }

            if (empty($errors)) {
                require_once __DIR__ . '/../models/Admin.php';
                $admin = new Admin();
                $admin->setEmail($email);
                $admin->setPassword($password);
                if ($admin->inscription()) {
                    $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                } else {
                    $errors[] = "Erreur lors de l'inscription. L'email est peut-être déjà utilisé.";
                }
            }
        }

        // Inclusion du header commun
        include_once __DIR__ . '/../includes/header.php';

        // Afficher la sidebar seulement si l'admin est connecté
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../includes/admin_sidebar.php';
        }

        // Inclusion de la vue principale
        include __DIR__ . '/../views/adminRegister.php';

        // Inclusion du footer commun
        include_once __DIR__ . '/../includes/footer.php';
    }
} 
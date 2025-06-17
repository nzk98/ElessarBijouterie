<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminController {
    public function index() {
        if (isset($_GET['section']) && $_GET['section'] === 'logout') {
            require_once __DIR__ . '/../models/Admin.php';
            Admin::logout();
        }
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }
        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];

        // Récupération des stats dynamiques
        require_once __DIR__ . '/../models/Creation.php';
        require_once __DIR__ . '/../models/Commande.php';
        require_once __DIR__ . '/../models/Utilisateur.php';
        $nbCreations = Creation::countAll();
        $nbCommandes = Commande::countAll();
        $nbUtilisateurs = Utilisateur::countAll();

       // Inclusion du header commun
		include_once __DIR__ . '/../includes/header.php';

        // Afficher la sidebar seulement si l'admin est connecté
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../includes/admin_sidebar.php';
        }

		// Inclusion de la vue principale
		include_once __DIR__ . '/../views/admin.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
    }
}

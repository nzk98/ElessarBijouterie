<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Commande.php';

class DashboardController {
    public function index() {
        // Sécurité : vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user']['id'])) {
            header('Location: index.php?page=Connexion');
            exit();
        }
        
        $userId = $_SESSION['user']['id'];
        
        // On récupère les données ici, mais la vue ne les utilisera pas encore.
        $utilisateur = Utilisateur::getDetailedUtilisateurById($userId);
        $derniereCommande = Commande::getLatestByUserId($userId);

        // Si l'utilisateur n'est pas trouvé en BDD (cas improbable), on déconnecte par sécurité
        if (!$utilisateur) {
            header('Location: index.php?page=Deconnexion');
            exit();
        }

        // Préparation des variables pour la vue
        $pageTitle = "Tableau de Bord";
        $pageStyle = "dashboard.css";
        $jsFile = []; // Pas de JS spécifique pour l'instant
        
        // Inclusion des vues
        include_once __DIR__ . '/../includes/header.php';
        include_once __DIR__ . '/../views/dashboard.php';
        include_once __DIR__ . '/../includes/footer.php';
    }
}



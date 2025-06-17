<?php

class DeconnexionController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Détruit toutes les variables de session
        $_SESSION = [];
        // Détruit la session
        session_destroy();
        // Redirige vers la page d'accueil ou de connexion
        header('Location: index.php?page=Accueil');
        exit();
    }
}

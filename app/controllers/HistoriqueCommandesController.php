<?php
session_start();

class HistoriqueCommandesController {
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=Connexion');
            exit();
        }
        $pageStyle = "historiqueCommandes.css";
		$jsFile = "historiqueCommande.js";
        // Vérification de la session utilisateur
        // session_start();
        // if (!isset($_SESSION['user_id'])) {
        //     header('Location: index.php?page=Connexion');
        //     exit();
        // }

       // Inclusion du header commun
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
        include_once __DIR__ .  '/../views/historiqueCommandes.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
    }
} 
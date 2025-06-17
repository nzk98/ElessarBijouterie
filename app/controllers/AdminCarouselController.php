<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AdminCarouselController {
    public function index() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }

        $pageTitle = "Gestion du Carrousel";
        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];

        // Récupération des créations avec leurs images
        require_once __DIR__ . '/../models/Creation.php';
        require_once __DIR__ . '/../models/ImageCreation.php';
        
        $creations = Creation::getAllCreations();
        $carouselImages = ImageCreation::getCarouselImages();

        // Inclusion de l'en-tête spécifique à l'administration
        include_once __DIR__ . '/../includes/header.php';

        // Afficher la sidebar seulement si l'admin est connecté
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../includes/admin_sidebar.php';
        }

        // Inclusion de la vue principale
        include_once __DIR__ . '/../views/adminCarrousel.php';

        // Inclusion du pied de page spécifique à l'administration
        include_once __DIR__ . '/../includes/footer.php';
    }

    public function toggleCarousel() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $creationId = filter_input(INPUT_POST, 'creation_id', FILTER_VALIDATE_INT);
            $imageUrl = filter_input(INPUT_POST, 'image_url', FILTER_SANITIZE_STRING);

            if ($creationId && $imageUrl) {
                require_once __DIR__ . '/../models/ImageCreation.php';
                if (ImageCreation::toggleCarousel($creationId, $imageUrl)) {
                    $_SESSION['success'] = "Le carrousel a été mis à jour avec succès";
                } else {
                    $_SESSION['error'] = "Une erreur est survenue lors de la mise à jour du carrousel";
                }
            } else {
                $_SESSION['error'] = "Données invalides";
            }
        }

        header('Location: index.php?page=AdminCarousel');
        exit();
    }
} 
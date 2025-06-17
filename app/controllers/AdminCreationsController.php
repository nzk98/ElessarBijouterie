<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminCreationsController {
    public function index() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }
        require_once __DIR__ . '/../models/Creation.php';
        // Suppression d'une création si demandé
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($id > 0) {
                Creation::deleteCreation($id);
                header('Location: index.php?page=AdminCreations');
                exit();
            }
        }
        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];
        $creations = Creation::getAllCreationsFull();
        include_once __DIR__ . '/../includes/header.php';
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../includes/admin_sidebar.php';
        }
        include_once __DIR__ . '/../views/adminCreations.php';
        include_once __DIR__ . '/../includes/footer.php';
    }
} 
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminUsersController {
    public function index() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }
        require_once __DIR__ . '/../models/Utilisateur.php';
        // Suppression d'un utilisateur si demandé
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($id > 0) {
                Utilisateur::deleteUtilisateur($id);
                header('Location: index.php?page=AdminUsers');
                exit();
            }
        }
        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];
        $users = Utilisateur::getAllUtilisateurs();
        include_once __DIR__ . '/../includes/header.php';
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../includes/admin_sidebar.php';
        }
        include_once __DIR__ . '/../views/adminUsers.php';
        include_once __DIR__ . '/../includes/footer.php';
    }
} 
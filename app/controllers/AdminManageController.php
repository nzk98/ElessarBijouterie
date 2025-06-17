<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminManageController {
    public function index() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }
        require_once __DIR__ . '/../models/Admin.php';
        // Suppression d'un admin si demandé
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($id > 0) {
                Admin::deleteAdmin($id);
                header('Location: index.php?page=AdminManage');
                exit();
            }
        }
        // Modification d'un admin : affichage du formulaire
        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $admin = Admin::getAdminById($id);
            $pageStyle = "admin.css";
            $jsFile = ["admin.js"];
            include_once __DIR__ . '/../includes/header.php';
            if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                include_once __DIR__ . '/../includes/admin_sidebar.php';
            }
            include_once __DIR__ . '/../views/adminEdit.php';
            include_once __DIR__ . '/../includes/footer.php';
            return;
        }
        // Traitement de la modification
        if (isset($_POST['edit_admin'])) {
            $id = intval($_POST['admin_id']);
            $email = $_POST['email'] ?? '';
            $change_mdp = isset($_POST['change_mdp']) && $_POST['change_mdp'] === '1';
            $password = $_POST['password'] ?? '';
            Admin::updateAdmin($id, $email, $change_mdp ? $password : null);
            header('Location: index.php?page=AdminManage');
            exit();
        }
        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];
        $admins = Admin::getAllAdmins();
        include_once __DIR__ . '/../includes/header.php';
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../includes/admin_sidebar.php';
        }
        include_once __DIR__ . '/../views/adminManage.php';
        include_once __DIR__ . '/../includes/footer.php';
    }
} 
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
        require_once __DIR__ . '/../../models/admin/Admin.php';
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
            include_once __DIR__ . '/../../includes/header.php';
            if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                include_once __DIR__ . '/../../includes/admin_sidebar.php';
            }
            include_once __DIR__ . '/../../views/admin/adminEdit.php';
            include_once __DIR__ . '/../../includes/footer.php';
            return;
        }
        // Traitement de la modification
        if (isset($_POST['edit_admin'])) {
            $id = intval($_POST['admin_id']);
            $email = $_POST['email'] ?? '';
            $change_mdp = isset($_POST['change_mdp']) && $_POST['change_mdp'] === '1';
            $password = $_POST['password'] ?? '';
            $current_password = $_POST['current_password'] ?? '';
            
            // Si on veut changer le mot de passe, vérifier le mot de passe actuel
            if ($change_mdp && !empty($password)) {
                if (empty($current_password)) {
                    $_SESSION['error'] = "Veuillez entrer votre mot de passe actuel.";
                    header('Location: index.php?page=AdminManage&action=edit&id=' . $id);
                    exit();
                }
                
                if (!Admin::verifyCurrentPassword($id, $current_password)) {
                    $_SESSION['error'] = "Le mot de passe actuel est incorrect.";
                    header('Location: index.php?page=AdminManage&action=edit&id=' . $id);
                    exit();
                }
                
                // Validation du pattern du nouveau mot de passe
                if (strlen($password) < 8) {
                    $_SESSION['error'] = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
                    header('Location: index.php?page=AdminManage&action=edit&id=' . $id);
                    exit();
                } elseif (preg_match_all('/[A-Z]/', $password) < 2) {
                    $_SESSION['error'] = "Le nouveau mot de passe doit contenir au moins 2 majuscules.";
                    header('Location: index.php?page=AdminManage&action=edit&id=' . $id);
                    exit();
                } elseif (!preg_match('/[0-9]/', $password)) {
                    $_SESSION['error'] = "Le nouveau mot de passe doit contenir au moins 1 chiffre.";
                    header('Location: index.php?page=AdminManage&action=edit&id=' . $id);
                    exit();
                } elseif (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
                    $_SESSION['error'] = "Le nouveau mot de passe doit contenir au moins 1 caractère spécial.";
                    header('Location: index.php?page=AdminManage&action=edit&id=' . $id);
                    exit();
                }
            }
            
            Admin::updateAdmin($id, $email, $change_mdp ? $password : null);
            $_SESSION['success'] = "Administrateur modifié avec succès.";
            header('Location: index.php?page=AdminManage');
            exit();
        }
        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];
        $admins = Admin::getAllAdmins();
        include_once __DIR__ . '/../../includes/header.php';
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../../includes/admin_sidebar.php';
        }
        include_once __DIR__ . '/../../views/admin/adminManage.php';
        include_once __DIR__ . '/../../includes/footer.php';
    }
} 
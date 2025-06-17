<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminLogController {
    public function index() {
        // On ne bloque pas l'accès à la page de connexion elle-même
        // if ($_SERVER['REQUEST_METHOD'] !== 'POST' && (isset($_SESSION['admin']) && $_SESSION['admin'] === true)) {
        //     header('Location: index.php?page=Accueil');
        //     exit();
        // }

        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            require_once __DIR__ . '/../models/Admin.php';
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT * FROM admin WHERE Email_Admin = :email");
            $stmt->execute([':email' => $email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['MotdePasse'])) {
                $_SESSION['admin'] = true;
                $_SESSION['admin_email'] = $admin['Email_Admin'];
                header('Location: index.php?page=admin');
                exit();
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        }

        // Inclusion du header commun
        include_once __DIR__ . '/../includes/header.php';

        

        // Inclusion de la vue principale
        include __DIR__ . '/../views/adminLog.php';

        // Inclusion du footer commun
        include_once __DIR__ . '/../includes/footer.php';
    }
}

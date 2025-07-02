<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminLogController {
    private array $errors = [];

    public function index() {
        // On ne bloque pas l'accès à la page de connexion elle-même
        // if ($_SERVER['REQUEST_METHOD'] !== 'POST' && (isset($_SESSION['admin']) && $_SESSION['admin'] === true)) {
        //     header('Location: index.php?page=Accueil');
        //     exit();
        // }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        }

        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];

        // Inclusion du header commun
        include_once __DIR__ . '/../../includes/header.php';

        // Inclusion de la vue principale
        // Nous passons $this pour que la vue puisse accéder aux méthodes comme getErrors()
        include __DIR__ . '/../../views/admin/adminLog.php';

        // Inclusion du footer commun
        include_once __DIR__ . '/../../includes/footer.php';
    }

    private function handleLogin() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->errors['credentials'] = "L'email et le mot de passe sont requis.";
            return;
        }

        require_once __DIR__ . '/../../models/admin/Admin.php';
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
            $this->errors['credentials'] = "Email ou mot de passe incorrect.";
        }
    }

    // Méthode pour la vue
    public function getErrors(): array {
        return $this->errors;
    }
}

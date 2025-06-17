<?php
require_once('Database.php');

class Admin {
    private ?int $id_admin;
    private string $email_admin;
    private string $password_admin;

    public function __construct(array $data = []) {
        $this->id_admin = $data['id_admin'] ?? null;
        $this->email_admin = $data['email_admin'] ?? '';
        $this->password_admin = $data['password_admin'] ?? '';
    }

    public function setEmail(string $email): void {
        $this->email_admin = $email;
    }

    public function setPassword(string $password): void {
        $this->password_admin = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getEmail(): string {
        return $this->email_admin;
    }

    public function getId(): ?int {
        return $this->id_admin;
    }

    // Méthode d'inscription admin
    public function inscription(): bool {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO admin (Email_Admin, MotdePasse) VALUES (:email, :password)");
            return $stmt->execute([
                ':email' => $this->email_admin,
                ':password' => $this->password_admin
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Vider toutes les variables de session
        $_SESSION = [];

        // Supprimer le cookie de session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Détruire la session
        session_destroy();
        // Forcer la fermeture de la session
        session_write_close();

        header('Location: index.php?page=Accueil');
        exit();
    }

    public static function getAllAdmins() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM admin");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteAdmin($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM admin WHERE ID_Admin = ?");
        return $stmt->execute([$id]);
    }

    public static function getAdminById($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM admin WHERE ID_Admin = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateAdmin($id, $email, $password = null) {
        $db = Database::getInstance();
        if ($password !== null && $password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE admin SET Email_Admin = ?, MotdePasse = ? WHERE ID_Admin = ?");
            return $stmt->execute([$email, $hash, $id]);
        } else {
            $stmt = $db->prepare("UPDATE admin SET Email_Admin = ? WHERE ID_Admin = ?");
            return $stmt->execute([$email, $id]);
        }
    }
} 
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Utilisateur.php';

class MdpoublieController {
    private array $errors = [];
    private array $success = [];
  

    public function index() {
        $pageStyle = "auth.css";
        $jsFile = ["auth.js", "main.js"];
        $showResetForm = false;
        $token = $_GET['token'] ?? null;
        $email = '';

        // 1. Demande de réinitialisation (formulaire email)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && !isset($_POST['token'])) {
            $email = trim($_POST['email']);
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = "Veuillez saisir une adresse email valide.";
            } else {
                $user = Utilisateur::getByEmail($email);
                if ($user) {
                    $tokenGen = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', time() + 1800); // 30 min
                    Utilisateur::setResetToken($email, $tokenGen, $expires);
                    Utilisateur::sendResetEmail($email, $tokenGen);
                }
                $this->success['reset'] = "Si un compte existe avec cet email, un lien de réinitialisation a été envoyé.";
            }
            // On ne définit PAS $showResetForm ici, donc seul le formulaire d'email reste affiché
        }

        // 2. Lien de réinitialisation avec token
        if ($token) {
            $user = Utilisateur::getByResetToken($token);
            if ($user && !empty($user['reset_token_expires']) && strtotime($user['reset_token_expires']) > time()) {
                $showResetForm = true;
                if (isset($user['email_utilisateur'])) {
                    $email = $user['email_utilisateur'];
                }
            } else {
                $this->errors['token'] = "Lien invalide ou expiré. Veuillez refaire une demande.";
                $showResetForm = false;
            }
        }

        // 3. Soumission du nouveau mot de passe
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'], $_POST['password'], $_POST['password_confirm'])) {
            $token = $_POST['token'];
            $user = Utilisateur::getByResetToken($token);
            if ($user && !empty($user['reset_token_expires']) && strtotime($user['reset_token_expires']) > time()) {
                $password = $_POST['password'];
                $password_confirm = $_POST['password_confirm'];
                if (empty($password) || strlen($password) < 8) {
                    $this->errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
                } elseif ($password !== $password_confirm) {
                    $this->errors['password_confirm'] = "Les mots de passe ne correspondent pas.";
                } elseif (!$this->validatePasswordComplexity($password)) {
                    $this->errors['password'] = "Le mot de passe doit contenir au moins 2 majuscules, 1 chiffre et 1 caractère spécial.";
                }
                if (empty($this->errors)) {
                    if (isset($user['ID_Utilisateur'])) {
                        Utilisateur::updatePassword($user['ID_Utilisateur'], $password);
                        Utilisateur::clearResetToken($user['ID_Utilisateur']);
                    }
                    $this->success['reset_done'] = "Votre mot de passe a été réinitialisé avec succès.";
                    $showResetForm = false;
                } else {
                    $showResetForm = true;
                    if (isset($user['email_utilisateur'])) {
                        $email = $user['email_utilisateur'];
                    }
                }
            } else {
                $this->errors['token'] = "Lien invalide ou expiré. Veuillez refaire une demande.";
                $showResetForm = false;
            }
        }

        // Inclusion du header commun
        $pageTitle = "Mot de passe oublié - Elessard Bijouterie";
        $metaDesc = "Réinitialisez votre mot de passe pour accéder à votre espace client.";
        include_once __DIR__ . '/../includes/header.php';

        // Passe les tableaux à la vue
        $errors = $this->errors;
        $success = $this->success;

        include_once __DIR__ . '/../views/mdpoublie.php';
        include_once __DIR__ . '/../includes/footer.php';
    }

    private function validatePasswordComplexity($password): bool {
        $uppercaseCount = preg_match_all('/[A-Z]/', $password);
        $digitCount = preg_match_all('/[0-9]/', $password);
        $specialCount = preg_match_all('/[!@#$%^&*()_+\-=\[\]{};\':\"\\|,.<>\/?]/', $password);
        return $uppercaseCount >= 2 && $digitCount >= 1 && $specialCount >= 1;
    }

    public function getErrors(): array { return $this->errors; }
    public function getSuccess(): array { return $this->success; }
    public function hasErrors(): bool { return !empty($this->errors); }
    public function hasSuccess(): bool { return !empty($this->success); }
}

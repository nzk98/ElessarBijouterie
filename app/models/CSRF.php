<?php

class CSRF {
    /**
     * Génère un jeton CSRF unique
     */
    public static function generateToken(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time']) || (time() - $_SESSION['csrf_token_time'] > 1800)) {
            $token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $token;
            $_SESSION['csrf_token_time'] = time();
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Vérifie si un jeton CSRF est valide
     */
    public static function verifyToken($token): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Debug: Afficher les informations de session (à retirer en production)
        error_log("CSRF Debug - Token reçu: " . ($token ?? 'NULL'));
        error_log("CSRF Debug - Token en session: " . ($_SESSION['csrf_token'] ?? 'NULL'));
        error_log("CSRF Debug - Session ID: " . session_id());
        
        // Vérifier si le jeton existe en session
        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
            error_log("CSRF Debug - Token manquant en session");
            return false;
        }
        
        // Vérifier si le jeton correspond
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            error_log("CSRF Debug - Token ne correspond pas");
            return false;
        }
        
        // Vérifier l'expiration (30 minutes)
        if (time() - $_SESSION['csrf_token_time'] > 1800) {
            error_log("CSRF Debug - Token expiré");
            self::clearToken();
            return false;
        }
        
        error_log("CSRF Debug - Token valide");
        return true;
    }
    
    /**
     * Nettoie le jeton CSRF de la session
     */
    public static function clearToken(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
    }
    
    /**
     * Génère un champ caché HTML avec le jeton CSRF
     */
    public static function getHiddenField(): string {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Vérifie le jeton CSRF depuis les données POST
     */
    public static function checkPostToken(): bool {
        if (!isset($_POST['csrf_token'])) {
            error_log("CSRF Debug - Aucun token dans POST");
            return false;
        }
        
        return self::verifyToken($_POST['csrf_token']);
    }
} 
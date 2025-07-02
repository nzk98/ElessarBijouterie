<?php

class Commande {
    public static function countAll() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT COUNT(*) FROM commande");
        return (int)$stmt->fetchColumn();
    }

    /**
     * Récupère la dernière commande d'un utilisateur.
     * @param int $userId L'ID de l'utilisateur.
     * @return array|null Les données de la commande ou null si aucune commande n'est trouvée.
     */
    public static function getLatestByUserId(int $userId): ?array {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("
                SELECT 
                    ID_Commande, 
                    Date_Commande, 
                    Status_Commande, 
                    Total_Commande
                FROM commande
                WHERE ID_Utilisateur = :userId
                ORDER BY Date_Commande DESC
                LIMIT 1
            ");
            $stmt->execute([':userId' => $userId]);
            $commande = $stmt->fetch(PDO::FETCH_ASSOC);

            return $commande ?: null;
        } catch (PDOException $e) {
            // Gérer l'erreur, par exemple en la loggant
            error_log("Erreur lors de la récupération de la dernière commande : " . $e->getMessage());
            return null;
        }
    }
}

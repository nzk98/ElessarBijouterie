<?php

class Commande {
    public static function countAll() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT COUNT(*) FROM commande");
        return (int)$stmt->fetchColumn();
    }
}

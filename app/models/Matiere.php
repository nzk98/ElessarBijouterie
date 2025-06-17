<?php

class Matiere {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public static function getAll() {
        $db = Database::getInstance();
        $query = "SELECT ID_Matiere as id, Nom_Matiere as nom FROM matiere ORDER BY Nom_Matiere";
        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function add($nom) {
        $stmt = $this->db->prepare("INSERT INTO matiere (Nom_Matiere) VALUES (?)");
        $result = $stmt->execute([$nom]);
        if (!$result) {
            var_dump($stmt->errorInfo());
            exit;
        }
        return $result;
    }

    public function delete($id) {
        // D'abord, supprimer les relations avec les créations
        $stmt = $this->db->prepare("DELETE FROM renfermer_creationmatiere WHERE ID_Matiere = ?");
        $stmt->execute([$id]);
        
        // Ensuite, supprimer la matière
        $stmt = $this->db->prepare("DELETE FROM matiere WHERE ID_Matiere = ?");
        return $stmt->execute([$id]);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT ID_Matiere as id, Nom_Matiere as nom FROM matiere WHERE ID_Matiere = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getCreationsForMatiere($id) {
        $query = "SELECT c.* FROM creation c 
                 INNER JOIN renfermer_creationmatiere rcm ON c.ID_Creation = rcm.ID_Creation 
                 WHERE rcm.ID_Matiere = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function assignToCreation($matiereId, $creationId) {
        $stmt = $this->db->prepare("INSERT INTO renfermer_creationmatiere (ID_Creation, ID_Matiere) VALUES (?, ?)");
        return $stmt->execute([$creationId, $matiereId]);
    }

    public function removeFromCreation($matiereId, $creationId) {
        $stmt = $this->db->prepare("DELETE FROM renfermer_creationmatiere WHERE ID_Creation = ? AND ID_Matiere = ?");
        return $stmt->execute([$creationId, $matiereId]);
    }
}

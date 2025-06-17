<?php
class CategorieCreation {
    private $id;
    private $nom;

    public function __construct($id = null, $nom = null) {
        $this->id = $id;
        $this->nom = $nom;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }

    public function getAllCategories() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT ID_Categorie as id, Nom_Categorie as nom FROM categorie_creation ORDER BY Nom_Categorie");
        $stmt->execute();
        
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categorie = new CategorieCreation(
                $row['id'],
                $row['nom']
            );
            $categories[] = $categorie;
        }
        
        return $categories;
    }

    public static function getAll() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT ID_Categorie as id, Nom_Categorie as nom FROM categorie_creation ORDER BY Nom_Categorie");
        $stmt->execute();
        
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categorie = new CategorieCreation(
                $row['id'],
                $row['nom']
            );
            $categories[] = $categorie;
        }
        
        return $categories;
    }

    public static function getById($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT ID_Categorie as id, Nom_Categorie as nom FROM categorie_creation WHERE ID_Categorie = ?");
        $stmt->execute([$id]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new CategorieCreation(
                $row['id'],
                $row['nom']
            );
        }
        return null;
    }

    public static function add($nom) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO categorie_creation (Nom_Categorie) VALUES (?)");
        return $stmt->execute([$nom]);
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM categorie_creation WHERE ID_Categorie = ?");
        return $stmt->execute([$id]);
    }
}

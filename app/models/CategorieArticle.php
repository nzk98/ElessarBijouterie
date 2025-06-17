<?php
class CategorieArticle {
    private $id;
    private $nom;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->nom = $data['nom'] ?? '';
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }

    public static function getAll() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM categorie_article");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $categories = [];
        foreach ($result as $row) {
            $categories[] = new self([
                'id' => $row['ID_Categorie'],
                'nom' => $row['Nom_Categorie']
            ]);
        }
        return $categories;
    }

    public static function add($nom) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO categorie_article (Nom_Categorie) VALUES (?)");
        $result = $stmt->execute([$nom]);
        if (!$result) {
            var_dump($stmt->errorInfo());
            exit; // Pour être sûr de voir l'erreur
        }
        return $result;
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM categorie_article WHERE ID_Categorie = ?");
        return $stmt->execute([$id]);
    }
}

<?php

require_once('Database.php');

class Creation {
    private ?int $id;
    private string $nom;
    private string $description;
    private int $stock;
    private float $prix;
    private int $id_categorie;
    private array $id_matieres;
    private array $images; // chemins des images
    private bool $keep_existing_images;
    
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->nom = $data['nom'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->stock = $data['stock'] ?? 0;
        $this->prix = $data['prix'] ?? 0.0;
        $this->id_categorie = $data['id_categorie'] ?? 0;
        $this->id_matieres = $data['id_matieres'] ?? [];
        $this->images = $data['images'] ?? [];
        $this->keep_existing_images = $data['keep_existing_images'] ?? true;
    }
    
    // --- GETTERS ---
    public function getId() {
         return $this->id; 
        }
    public function getNom() { 
        return $this->nom; 
    }
    public function getDescription() { 
        return $this->description; 
    }
    public function getStock() { 
        return $this->stock; }

    public function getPrix() {
         return $this->prix;
         }
    public function getIdCategorie() {
         return $this->id_categorie; 
        }
    public function getIdMatiere() { 
        return $this->id_matiere;
    }
    public function getImages() {
         return $this->images;
         }
    public function insererCreation(): bool {
        $db = Database::getInstance();
        try {
            // Insertion dans la table creation (sans ID_Matiere)
            $stmt = $db->prepare("INSERT INTO creation (Nom_Creation, Description_Creation, Stock_Creation, Prix_Creation, ID_Categorie) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $this->nom,
                $this->description,
                $this->stock,
                $this->prix,
                $this->id_categorie
            ]);
            $this->id = $db->lastInsertId();
            
            // Insertion des images dans image_creation
            foreach ($this->images as $chemin) {
                $stmtImg = $db->prepare("INSERT INTO image_creation (URL_Image, ID_Creation) VALUES (?, ?)");
                $stmtImg->execute([$chemin, $this->id]);
            }
            
            // Insertion des matières dans la table de liaison
            foreach ($this->id_matieres as $id_matiere) {
                $stmtMat = $db->prepare("INSERT INTO renfermer_creationmatiere (ID_Creation, ID_Matiere) VALUES (?, ?)");
                $stmtMat->execute([$this->id, $id_matiere]);
            }
            return true;
        } catch (PDOException $e) {
            // Pour debug : echo $e->getMessage();
            return false;
        }
    }
    
    public function modifierCreation(): bool {
        if (!$this->id) return false;
        $db = Database::getInstance();
        try {
            $stmt = $db->prepare("UPDATE creation SET Nom_Creation=?, Description_Creation=?, Stock_Creation=?, Prix_Creation=?, ID_Categorie=? WHERE ID_Creation=?");
            $stmt->execute([
                $this->nom,
                $this->description,
                $this->stock,
                $this->prix,
                $this->id_categorie,
                $this->id
            ]);

            // Gestion des images
            if (!$this->keep_existing_images) {
                // Supprimer les anciennes images physiques
                $query = "SELECT URL_Image FROM image_creation WHERE ID_Creation = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$this->id]);
                $old_images = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                foreach ($old_images as $old_image) {
                    if (file_exists($old_image)) {
                        unlink($old_image);
                    }
                }
                
                // Supprimer les anciennes images de la base de données
                $stmtDel = $db->prepare("DELETE FROM image_creation WHERE ID_Creation=?");
                $stmtDel->execute([$this->id]);
            }

            // Ajouter les nouvelles images si il y en a
            if (!empty($this->images)) {
                foreach ($this->images as $chemin) {
                    $stmtImg = $db->prepare("INSERT INTO image_creation (URL_Image, ID_Creation) VALUES (?, ?)");
                    $stmtImg->execute([$chemin, $this->id]);
                }
            }

            // Mise à jour des matières
            $stmtDelMat = $db->prepare("DELETE FROM renfermer_creationmatiere WHERE ID_Creation=?");
            $stmtDelMat->execute([$this->id]);
            foreach ($this->id_matieres as $id_matiere) {
                $stmtMat = $db->prepare("INSERT INTO renfermer_creationmatiere (ID_Creation, ID_Matiere) VALUES (?, ?)");
                $stmtMat->execute([$this->id, $id_matiere]);
            }

            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la modification de la création : " . $e->getMessage());
            return false;
        }
    }
    
    public function supprimerCreation(): bool {
        if (!$this->id) return false;
        $db = Database::getInstance();
        try {
            // Suppression des images
            $stmtDelImg = $db->prepare("DELETE FROM image_creation WHERE ID_Creation=?");
            $stmtDelImg->execute([$this->id]);
            // Suppression de la création
            $stmt = $db->prepare("DELETE FROM creation WHERE ID_Creation=?");
            $stmt->execute([$this->id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function getAllCreations(): array {
        $db = Database::getInstance();
        $creations = [];
        
        try {
            $query = "SELECT creation.*, GROUP_CONCAT(image_creation.URL_Image) as images 
                     FROM creation 
                     LEFT JOIN image_creation ON creation.ID_Creation = image_creation.ID_Creation 
                     GROUP BY creation.ID_Creation";
            
            $stmt = $db->query($query);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $row) {
                $images = $row['images'] ? explode(',', $row['images']) : [];
                $creations[] = new Creation([
                    'id' => $row['ID_Creation'],
                    'nom' => $row['Nom_Creation'],
                    'description' => $row['Description_Creation'],
                    'stock' => $row['Stock_Creation'],
                    'prix' => $row['Prix_Creation'],
                    'id_categorie' => $row['ID_Categorie'],
                    'images' => $images
                ]);
            }
            
            return $creations;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des créations : " . $e->getMessage());
            return [];
        }
    }
    
    public static function getCreationById($id): ?self {
        $db = Database::getInstance();
        try {
            $query = "SELECT creation.*, GROUP_CONCAT(image_creation.URL_Image) as images 
                     FROM creation 
                     LEFT JOIN image_creation ON creation.ID_Creation = image_creation.ID_Creation 
                     WHERE creation.ID_Creation = ? GROUP BY creation.ID_Creation";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $images = $row['images'] ? explode(',', $row['images']) : [];
                return new Creation([
                    'id' => $row['ID_Creation'],
                    'nom' => $row['Nom_Creation'],
                    'description' => $row['Description_Creation'],
                    'stock' => $row['Stock_Creation'],
                    'prix' => $row['Prix_Creation'],
                    'id_categorie' => $row['ID_Categorie'],
                    'images' => $images
                ]);
            }
            return null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la création : " . $e->getMessage());
            return null;
        }
    }

    public function decrementerStock($quantite) {
        $db = Database::getInstance();
        try {
            $stmt = $db->prepare("UPDATE creation SET Stock_Creation = Stock_Creation - ? WHERE ID_Creation = ? AND Stock_Creation >= ?");
            $stmt->execute([$quantite, $this->id, $quantite]);
            // Met à jour l'objet courant
            $this->stock -= $quantite;
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la décrémentation du stock : " . $e->getMessage());
            return false;
        }
    }

    public static function countAll() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT COUNT(*) FROM creation");
        return (int)$stmt->fetchColumn();
    }

    public static function getAllCreationsFull() {
        $db = Database::getInstance();
        $query = "SELECT c.*, cat.Nom_Categorie, mat.Nom_Matiere, GROUP_CONCAT(ic.URL_Image) as images
                  FROM creation c
                  LEFT JOIN categorie_creation cat ON c.ID_Categorie = cat.ID_Categorie
                  LEFT JOIN renfermer_creationmatiere rcm ON c.ID_Creation = rcm.ID_Creation
                  LEFT JOIN matiere mat ON rcm.ID_Matiere = mat.ID_Matiere
                  LEFT JOIN image_creation ic ON c.ID_Creation = ic.ID_Creation
                  GROUP BY c.ID_Creation";
        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteCreation($id) {
        $db = Database::getInstance();
        // Supprimer les images associées
        $db->prepare("DELETE FROM image_creation WHERE ID_Creation = ?")->execute([$id]);
        // Supprimer la liaison matière
        $db->prepare("DELETE FROM renfermer_creationmatiere WHERE ID_Creation = ?")->execute([$id]);
        // Supprimer la création
        $stmt = $db->prepare("DELETE FROM creation WHERE ID_Creation = ?");
        return $stmt->execute([$id]);
    }

    public static function getMatieresForCreation($id) {
        $db = Database::getInstance();
        try {
            $query = "SELECT ID_Matiere as id FROM renfermer_creationmatiere WHERE ID_Creation = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des matières : " . $e->getMessage());
            return [];
        }
    }

    public function deleteImage($imagePath) {
        $db = Database::getInstance();
        try {
            // Supprimer l'image de la base de données
            $stmt = $db->prepare("DELETE FROM image_creation WHERE URL_Image = ? AND ID_Creation = ?");
            $result = $stmt->execute([$imagePath, $this->id]);
            
            if ($result && $stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'image : " . $e->getMessage());
            return false;
        }
    }

    public function getCategorieId() {
        return $this->id_categorie;
    }

    public static function getFilteredCreations($categories = [], $sort = 'id-desc'): array {
        $db = Database::getInstance();
        try {
            $query = "SELECT creation.*, GROUP_CONCAT(image_creation.URL_Image) as images 
                     FROM creation 
                     LEFT JOIN image_creation ON creation.ID_Creation = image_creation.ID_Creation";

            $params = [];
            $conditions = [];

            if (!empty($categories)) {
                $placeholders = str_repeat('?,', count($categories) - 1) . '?';
                $conditions[] = "creation.ID_Categorie IN ($placeholders)";
                $params = array_merge($params, $categories);
            }

            if (!empty($conditions)) {
                $query .= " WHERE " . implode(' AND ', $conditions);
            }

            $query .= " GROUP BY creation.ID_Creation";

            switch ($sort) {
                case 'price-asc':
                    $query .= " ORDER BY creation.Prix_Creation ASC";
                    break;
                case 'price-desc':
                    $query .= " ORDER BY creation.Prix_Creation DESC";
                    break;
                case 'id-desc':
                default:
                    $query .= " ORDER BY creation.ID_Creation DESC";
                    break;
            }

            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $creations = [];
            foreach ($results as $row) {
                $images = $row['images'] ? explode(',', $row['images']) : [];
                $creations[] = new Creation([
                    'id' => $row['ID_Creation'],
                    'nom' => $row['Nom_Creation'],
                    'description' => $row['Description_Creation'],
                    'stock' => $row['Stock_Creation'],
                    'prix' => $row['Prix_Creation'],
                    'id_categorie' => $row['ID_Categorie'],
                    'images' => $images
                ]);
            }

            return $creations;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des créations filtrées : " . $e->getMessage());
            return [];
        }
    }

    public function getAllTypes() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT DISTINCT type_bijou FROM creation ORDER BY type_bijou");
        $stmt->execute();
        
        $types = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $type = new stdClass();
            $type->id = $row['type_bijou'];
            $type->nom = $row['type_bijou'];
            $types[] = $type;
        }
        
        return $types;
    }
}
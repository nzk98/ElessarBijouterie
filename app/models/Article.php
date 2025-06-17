<?php
class Article {
    private $titre;
    private $contenu;
    private $id_categorie;
    private $date_publication;
    private $images = [];
    private $id;
    private $keep_existing_images;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->titre = $data['titre'] ?? '';
        $this->contenu = $data['contenu'] ?? '';
        $this->id_categorie = $data['id_categorie'] ?? null;
        $this->date_publication = $data['date_publication'] ?? date('Y-m-d');
        $this->images = $data['images'] ?? [];
        $this->keep_existing_images = $data['keep_existing_images'] ?? true;
    }

    public function insererArticle(): bool {
        $db = Database::getInstance();
        try {
            $stmt = $db->prepare("INSERT INTO articles (Titre_Article, Contenue_Article, DatePublication_Article, ID_Categorie) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $this->titre,
                $this->contenu,
                $this->date_publication,
                $this->id_categorie
            ]);
            $idArticle = $db->lastInsertId();
            foreach ($this->images as $chemin) {
                $stmtImg = $db->prepare("INSERT INTO image_article (URL_Image, ID_Article) VALUES (?, ?)");
                $stmtImg->execute([$chemin, $idArticle]);
            }
            return true;
        } catch (PDOException $e) {
            // Pour debug : echo $e->getMessage();
            return false;
        }
    }

    public static function getAllArticlesFull() {
        $db = Database::getInstance();
        $query = "SELECT a.*, c.Nom_Categorie, GROUP_CONCAT(i.URL_Image) as images
                  FROM articles a
                  LEFT JOIN categorie_article c ON a.ID_Categorie = c.ID_Categorie
                  LEFT JOIN image_article i ON a.ID_Article = i.ID_Article
                  GROUP BY a.ID_Article";
        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getLatestArticles($limit = 3) {
        $db = Database::getInstance();
        $query = "SELECT a.*, c.Nom_Categorie, GROUP_CONCAT(i.URL_Image) as images
                  FROM articles a
                  LEFT JOIN categorie_article c ON a.ID_Categorie = c.ID_Categorie
                  LEFT JOIN image_article i ON a.ID_Article = i.ID_Article
                  GROUP BY a.ID_Article
                  ORDER BY a.DatePublication_Article DESC
                  LIMIT :limit";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getArticleById($id) {
        $db = Database::getInstance();
        $query = "SELECT a.*, c.Nom_Categorie, GROUP_CONCAT(i.URL_Image) as images
                  FROM articles a
                  LEFT JOIN categorie_article c ON a.ID_Categorie = c.ID_Categorie
                  LEFT JOIN image_article i ON a.ID_Article = i.ID_Article
                  WHERE a.ID_Article = :id
                  GROUP BY a.ID_Article";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function deleteArticle($id) {
        $db = Database::getInstance();
        $db->prepare("DELETE FROM image_article WHERE ID_Article = ?")->execute([$id]);
        $stmt = $db->prepare("DELETE FROM articles WHERE ID_Article = ?");
        return $stmt->execute([$id]);
    }

    public static function getAvailableYears() {
        $db = Database::getInstance();
        $query = "SELECT DISTINCT YEAR(DatePublication_Article) as year 
                  FROM articles 
                  ORDER BY year DESC";
        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getFilteredArticles($categories = [], $years = [], $sort = 'recent') {
        $db = Database::getInstance();
        
        $query = "SELECT a.*, c.Nom_Categorie, GROUP_CONCAT(i.URL_Image) as images
                  FROM articles a
                  LEFT JOIN categorie_article c ON a.ID_Categorie = c.ID_Categorie
                  LEFT JOIN image_article i ON a.ID_Article = i.ID_Article";

        $params = [];
        $conditions = [];

        // Filtre par catégories
        if (!empty($categories)) {
            $placeholders = str_repeat('?,', count($categories) - 1) . '?';
            $conditions[] = "c.ID_Categorie IN ($placeholders)";
            $params = array_merge($params, $categories);
        }

        // Filtre par années
        if (!empty($years)) {
            $yearPlaceholders = str_repeat('?,', count($years) - 1) . '?';
            $conditions[] = "YEAR(a.DatePublication_Article) IN ($yearPlaceholders)";
            $params = array_merge($params, $years);
        }

        // Ajouter les conditions à la requête
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " GROUP BY a.ID_Article";

        // Tri
        $query .= " ORDER BY ";
        switch ($sort) {
            case 'popular':
                $query .= "a.ID_Article DESC"; // À modifier si vous ajoutez un système de popularité
                break;
            case 'oldest':
                $query .= "a.DatePublication_Article ASC";
                break;
            case 'recent':
            default:
                $query .= "a.DatePublication_Article DESC";
                break;
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateArticle(): bool {
        $db = Database::getInstance();
        try {
            $db->beginTransaction();

            // Mise à jour des informations de base de l'article
            $stmt = $db->prepare("UPDATE articles SET Titre_Article = ?, Contenue_Article = ?, ID_Categorie = ? WHERE ID_Article = ?");
            $stmt->execute([
                $this->titre,
                $this->contenu,
                $this->id_categorie,
                $this->id
            ]);

            // Gestion des images
            if (!$this->keep_existing_images) {
                // Suppression des anciennes images
                $stmt = $db->prepare("DELETE FROM image_article WHERE ID_Article = ?");
                $stmt->execute([$this->id]);

                // Insertion des nouvelles images
                foreach ($this->images as $chemin) {
                    $stmtImg = $db->prepare("INSERT INTO image_article (URL_Image, ID_Article) VALUES (?, ?)");
                    $stmtImg->execute([$chemin, $this->id]);
                }
            } else if (!empty($this->images)) {
                // Si on garde les images existantes mais qu'il y a de nouvelles images
                foreach ($this->images as $chemin) {
                    $stmtImg = $db->prepare("INSERT INTO image_article (URL_Image, ID_Article) VALUES (?, ?)");
                    $stmtImg->execute([$chemin, $this->id]);
                }
            }

            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            // Pour debug : echo $e->getMessage();
            return false;
        }
    }

    public static function deleteImage($articleId, $imagePath): bool {
        $db = Database::getInstance();
        try {
            // Supprimer l'image de la base de données
            $stmt = $db->prepare("DELETE FROM image_article WHERE ID_Article = ? AND URL_Image = ?");
            $result = $stmt->execute([$articleId, $imagePath]);
            
            if ($result && $stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'image : " . $e->getMessage());
            return false;
        }
    }
}

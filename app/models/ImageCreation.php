<?php

require_once('Database.php');

class ImageCreation {
    private ?int $id;
    private string $url_image;
    private int $id_creation;
    private bool $is_carousel;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->url_image = $data['url_image'] ?? '';
        $this->id_creation = $data['id_creation'] ?? 0;
        $this->is_carousel = $data['is_carousel'] ?? false;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getUrlImage() { return $this->url_image; }
    public function getIdCreation() { return $this->id_creation; }
    public function getIsCarousel() { return $this->is_carousel; }

    // Méthode pour récupérer toutes les images du carrousel
    public static function getCarouselImages(): array {
        $db = Database::getInstance();
        $images = [];
        
        try {
            $query = "SELECT image_creation.*, creation.Nom_Creation 
                     FROM image_creation 
                     JOIN creation ON image_creation.ID_Creation = creation.ID_Creation 
                     WHERE image_creation.is_carousel = 1";
            
            $stmt = $db->query($query);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $row) {
                $images[] = new ImageCreation([
                    'id' => $row['ID_Image'],
                    'url_image' => $row['URL_Image'],
                    'id_creation' => $row['ID_Creation'],
                    'is_carousel' => $row['is_carousel']
                ]);
            }
            
            return $images;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des images du carrousel : " . $e->getMessage());
            return [];
        }
    }

    // Méthode pour basculer l'état du carrousel d'une image
    public static function toggleCarousel(int $creationId, string $imageUrl): bool {
        $db = Database::getInstance();
        try {
            $query = "UPDATE image_creation 
                     SET is_carousel = NOT is_carousel 
                     WHERE ID_Creation = ? AND URL_Image = ?";
            
            $stmt = $db->prepare($query);
            return $stmt->execute([$creationId, $imageUrl]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du carrousel : " . $e->getMessage());
            return false;
        }
    }
} 
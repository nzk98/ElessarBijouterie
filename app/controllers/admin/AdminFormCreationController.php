<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminFormCreationController {
    private array $errors = [];
    private array $success = [];
    private ?object $creation_a_modifier = null;
    private array $matieres_creation = [];

    public function index() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }

        // Gestion de la suppression d'image
        if (isset($_GET['action']) && $_GET['action'] === 'deleteImage') {
            $this->handleImageDeletion();
            return;
        }

        $pageTitle = "Création de Bijou";
        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];

        // Récupérer les catégories de créations et les matières pour le formulaire
        require_once __DIR__ . '/../../models/CategorieCreation.php';
        require_once __DIR__ . '/../../models/Matiere.php';
        require_once __DIR__ . '/../../models/Creation.php';
        
        $categories_creation = CategorieCreation::getAll();
        $matieres = Matiere::getAll();
        
        // Traitement du formulaire avant tout affichage pour gérer les redirections
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleFormSubmission(isset($_POST['id']) ? $_POST['id'] : null);
        }

        // Récupérer la création à modifier si un ID est fourni
        if (isset($_GET['id'])) {
            $this->creation_a_modifier = Creation::getCreationById($_GET['id']);
            if (!$this->creation_a_modifier) {
                $_SESSION['error_message'] = "Création non trouvée"; // Message pour la page de liste
                header('Location: index.php?page=AdminCreations');
                exit();
            }
            $this->matieres_creation = Creation::getMatieresForCreation($this->creation_a_modifier->getId());
        }

        // Inclusion de l'en-tête spécifique à l'administration
        include_once __DIR__ . '/../../includes/header.php';

        // Afficher la sidebar seulement si l'admin est connecté
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../../includes/admin_sidebar.php';
        }

        // Inclusion de la vue principale (le formulaire)
        include_once __DIR__ . '/../../views/admin/formulaireCreation.php';

        // Inclusion du pied de page spécifique à l'administration
        include_once __DIR__ . '/../../includes/footer.php';
    }

    private function handleFormSubmission($id = null) {
        // Validation et récupération des données
        $nom = trim($_POST['nom'] ?? '');
        $description = trim(htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES, 'UTF-8'));
        $prix = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
        $categorie = filter_input(INPUT_POST, 'categorie', FILTER_VALIDATE_INT);
        $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]);
        $matieres_post = isset($_POST['matieres']) && is_array($_POST['matieres']) ? $_POST['matieres'] : [];

        // Validation des champs
        if (empty($nom)) $this->errors['nom'] = "Le nom du produit est obligatoire.";
        if (empty($description)) $this->errors['description'] = "La description est obligatoire.";
        if ($prix === false || $prix <= 0) $this->errors['prix'] = "Le prix doit être un nombre positif.";
        if ($categorie === false) $this->errors['categorie'] = "Veuillez sélectionner une catégorie.";
        if (empty($matieres_post)) $this->errors['matieres'] = "Veuillez sélectionner au moins une matière.";

        // Si des erreurs sont trouvées, on arrête le traitement.
        if (!empty($this->errors)) {
            // Pour que le formulaire se recharge avec les données déjà saisies
            if ($id) {
                // On recharge les données de la création pour la vue
                $this->creation_a_modifier = Creation::getCreationById($id);
                $this->matieres_creation = Creation::getMatieresForCreation($id);
            }
            return;
        }

        // Validation et traitement des images
        $images = [];
        $keep_existing_images = true;
        if (isset($_FILES['image']) && $_FILES['image']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            // Ne pas définir keep_existing_images à false pour conserver les images existantes
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $uploadDir = 'assets/images/creations/';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
            foreach ($_FILES['image']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['image']['error'][$key] === UPLOAD_ERR_OK) {
                    $type = $_FILES['image']['type'][$key];
                    if (!in_array($type, $allowedTypes)) continue;
                    $filename = uniqid() . '_' . basename($_FILES['image']['name'][$key]);
                    $uploadFile = $uploadDir . $filename;
                    if (move_uploaded_file($tmpName, $uploadFile)) $images[] = $uploadFile;
                }
            }
            if (empty($images) && !$id) {
                 $this->errors['image'] = "Au moins une image valide est requise pour une nouvelle création.";
                 return;
            }
        } elseif (!$id) {
            $this->errors['image'] = "Veuillez ajouter au moins une image pour la nouvelle création.";
            return;
        }
        
        // Création de l'objet Creation
        $creation = new Creation([
            'id' => $id, 'nom' => $nom, 'description' => $description, 'prix' => $prix, 'stock' => $stock,
            'id_categorie' => $categorie, 'id_matieres' => $matieres_post, 'images' => $images,
            'keep_existing_images' => $keep_existing_images
        ]);

        $success = $id ? $creation->modifierCreation() : $creation->insererCreation();

        if ($success) {
            $_SESSION['success_message'] = "Produit " . ($id ? "modifié" : "créé") . " avec succès.";
            header('Location: index.php?page=AdminCreations');
            exit();
        } else {
            $this->errors['general'] = "Erreur lors de l'opération dans la base de données.";
        }
    }

    private function handleImageDeletion() {
        header('Content-Type: application/json');
        
        try {
            // Vérifier que la requête est en POST et contient les données JSON
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!$data || !isset($data['creation_id']) || !isset($data['image_path'])) {
                throw new Exception('Données invalides');
            }

            require_once __DIR__ . '/../../models/Creation.php';
            $creation = Creation::getCreationById($data['creation_id']);
            
            if (!$creation) {
                throw new Exception('Création non trouvée');
            }

            // Supprimer l'image de la base de données
            if ($creation->deleteImage($data['image_path'])) {
                // Supprimer le fichier physique
                if (file_exists($data['image_path'])) {
                    unlink($data['image_path']);
                }
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Erreur lors de la suppression de l\'image');
            }

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit();
    }

    // --- Méthodes pour la vue ---
    public function getErrors(): array { return $this->errors; }
    public function hasErrors(): bool { return !empty($this->errors); }
    public function getCreationAModifier(): ?object { return $this->creation_a_modifier; }
    public function getMatieresCreation(): array { return $this->matieres_creation; }
    
    // Méthodes pour récupérer les données nécessaires à la vue
    public function getCategoriesCreation(): array {
        require_once __DIR__ . '/../../models/CategorieCreation.php';
        return CategorieCreation::getAll();
    }
    
    public function getMatieres(): array {
        require_once __DIR__ . '/../../models/Matiere.php';
        return Matiere::getAll();
    }
}

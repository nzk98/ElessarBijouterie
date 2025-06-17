<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminFormCreationController {
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
        require_once __DIR__ . '/../models/CategorieCreation.php';
        require_once __DIR__ . '/../models/Matiere.php';
        require_once __DIR__ . '/../models/Creation.php';
        
        $categories_creation = CategorieCreation::getAll();
        $matieres = Matiere::getAll();
        
        // Récupérer la création à modifier si un ID est fourni
        $creation_a_modifier = null;
        if (isset($_GET['id'])) {
            $creation_a_modifier = Creation::getCreationById($_GET['id']);
            if (!$creation_a_modifier) {
                $_SESSION['error'] = "Création non trouvée";
                header('Location: index.php?page=AdminCreations');
                exit();
            }
            // Récupérer les matières de la création
            $matieres_creation = Creation::getMatieresForCreation($creation_a_modifier->getId());
        }

        // Traitement du formulaire si soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleFormSubmission(isset($_POST['id']) ? $_POST['id'] : null);
        }

        // Inclusion de l'en-tête spécifique à l'administration
        include_once __DIR__ . '/../includes/header.php';

        // Afficher la sidebar seulement si l'admin est connecté
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../includes/admin_sidebar.php';
        }

        // Inclusion de la vue principale (le formulaire)
        include_once __DIR__ . '/../views/formulaireCreation.php';

        // Inclusion du pied de page spécifique à l'administration
        include_once __DIR__ . '/../includes/footer.php';
    }

    private function handleFormSubmission($id = null) {
        try {
            // Validation des données
            $nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8') : '';
            $description = isset($_POST['description']) ? htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8') : '';
            $prix = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
            $categorie = filter_input(INPUT_POST, 'categorie', FILTER_VALIDATE_INT);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT) ?? 0;
            $matieres = isset($_POST['matieres']) ? $_POST['matieres'] : [];

            // Validation des données
            if (empty($nom) || empty($description) || $prix === false || $categorie === false || empty($matieres)) {
                throw new Exception("Tous les champs obligatoires doivent être remplis et valides");
            }

            // Validation des IDs des matières
            $matieres = array_filter($matieres, function($id) {
                return filter_var($id, FILTER_VALIDATE_INT) !== false;
            });

            if (empty($matieres)) {
                throw new Exception("Veuillez sélectionner au moins une matière valide");
            }

            // Traitement des images (optionnelles)
            $images = [];
            $keep_existing_images = true; // Par défaut, on garde les images existantes

            if (isset($_FILES['image']) && $_FILES['image']['error'][0] !== UPLOAD_ERR_NO_FILE) {
                $keep_existing_images = false; // Si de nouvelles images sont uploadées, on ne garde pas les anciennes
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $uploadDir = 'assets/images/creations/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                foreach ($_FILES['image']['tmp_name'] as $key => $tmpName) {
                    if ($_FILES['image']['error'][$key] === UPLOAD_ERR_OK) {
                        $type = $_FILES['image']['type'][$key];
                        if (!in_array($type, $allowedTypes)) {
                            continue;
                        }
                        $filename = uniqid() . '_' . basename($_FILES['image']['name'][$key]);
                        $uploadFile = $uploadDir . $filename;
                        if (move_uploaded_file($tmpName, $uploadFile)) {
                            $images[] = $uploadFile;
                        }
                    }
                }
            }

            // Création ou modification de l'objet Creation
            $creation = new Creation([
                'id' => $id,
                'nom' => $nom,
                'description' => $description,
                'prix' => $prix,
                'stock' => $stock,
                'id_categorie' => $categorie,
                'id_matieres' => $matieres,
                'images' => $images,
                'keep_existing_images' => $keep_existing_images
            ]);

            // Insertion ou mise à jour dans la base de données
            if ($id) {
                if ($creation->modifierCreation()) {
                    $_SESSION['success'] = "Produit modifié avec succès";
                } else {
                    throw new Exception("Erreur lors de la modification dans la base de données");
                }
            } else {
                if ($creation->insererCreation()) {
                    $_SESSION['success'] = "Produit créé avec succès";
                } else {
                    throw new Exception("Erreur lors de l'insertion dans la base de données");
                }
            }

            // Redirection pour éviter la re-soumission du formulaire
            header('Location: index.php?page=AdminCreations');
            exit();

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            // Ne pas rediriger ici pour afficher l'erreur sur la page actuelle
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

            require_once __DIR__ . '/../models/Creation.php';
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
}

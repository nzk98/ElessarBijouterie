<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminFormArticle {
    private array $errors = [];
    private ?array $article_a_modifier = null;

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

        // Récupérer les catégories d'articles pour le formulaire
        require_once __DIR__ . '/../../models/CategorieArticle.php';
        require_once __DIR__ . '/../../models/Article.php';
        
        $categories_article = CategorieArticle::getAll();
        
        // Traitement du formulaire avant l'affichage
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleFormSubmission(isset($_POST['id']) ? $_POST['id'] : null);
        }

        // Récupérer l'article à modifier si un ID est fourni
        if (isset($_GET['id'])) {
            $this->article_a_modifier = Article::getArticleById($_GET['id']);
            if (!$this->article_a_modifier) {
                $_SESSION['error_message'] = "Article non trouvé";
                header('Location: index.php?page=AdminArticles');
                exit();
            }
        }

        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];

        // Inclusion du header commun
		include_once __DIR__ . '/../../includes/header.php';

         // Afficher la sidebar seulement si l'admin est connecté
         if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
             include_once __DIR__ . '/../../includes/admin_sidebar.php';
         }

		// Inclusion de la vue principale
		include_once __DIR__ . '/../../views/admin/formulaireArticle.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../../includes/footer.php';
    }

    private function handleFormSubmission($id = null) {
        $titre = trim(htmlspecialchars($_POST['titre'] ?? '', ENT_QUOTES, 'UTF-8'));
        $contenu = trim(htmlspecialchars($_POST['contenu'] ?? '', ENT_QUOTES, 'UTF-8'));
        $categorie = filter_input(INPUT_POST, 'categorie', FILTER_VALIDATE_INT);

        if (empty($titre)) $this->errors['titre'] = "Le titre de l'article est obligatoire.";
        if (empty($contenu)) $this->errors['contenu'] = "Le contenu de l'article est obligatoire.";
        if ($categorie === false) $this->errors['categorie'] = "Veuillez sélectionner une catégorie.";

        if (!empty($this->errors)) {
            if ($id) {
                // Pour que le formulaire se recharge avec les données de l'article en cours de modification
                $this->article_a_modifier = Article::getArticleById($id);
            }
            return;
        }
        
        // Traitement des images
        $images = [];
        $keep_existing_images = true;
        if (isset($_FILES['image']) && $_FILES['image']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            // Ne pas définir keep_existing_images à false pour conserver les images existantes
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $uploadDir = 'assets/images/articles/';
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
                 $this->errors['image'] = "Au moins une image valide est requise pour un nouvel article.";
                 return;
            }
        } elseif (!$id) {
            $this->errors['image'] = "Veuillez ajouter au moins une image pour le nouvel article.";
            return;
        }

        // Création de l'objet Article
        $article = new Article([
            'id' => $id, 'titre' => $titre, 'contenu' => $contenu,
            'id_categorie' => $categorie, 'images' => $images,
            'keep_existing_images' => $keep_existing_images
        ]);
        
        $success = $id ? $article->updateArticle() : $article->insererArticle();

        if ($success) {
            $_SESSION['success_message'] = "Article " . ($id ? "modifié" : "créé") . " avec succès.";
            header('Location: index.php?page=AdminArticles');
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

            if (!$data || !isset($data['article_id']) || !isset($data['image_path'])) {
                throw new Exception('Données invalides');
            }

            require_once __DIR__ . '/../../models/Article.php';
            
            // Supprimer l'image de la base de données
            if (Article::deleteImage($data['article_id'], $data['image_path'])) {
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
    public function getArticleAModifier(): ?array { return $this->article_a_modifier; }
}


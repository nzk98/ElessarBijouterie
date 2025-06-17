<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminFormArticle {
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
        require_once __DIR__ . '/../models/CategorieArticle.php';
        require_once __DIR__ . '/../models/Article.php';
        
        $categories_article = CategorieArticle::getAll();
        
        // Récupérer l'article à modifier si un ID est fourni
        $article_a_modifier = null;
        if (isset($_GET['id'])) {
            $article_a_modifier = Article::getArticleById($_GET['id']);
            if (!$article_a_modifier) {
                $_SESSION['error'] = "Article non trouvé";
                header('Location: index.php?page=AdminArticles');
                exit();
            }
        }

        // Traitement du formulaire si soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleFormSubmission(isset($_POST['id']) ? $_POST['id'] : null);
        }

        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];

        // Inclusion du header commun
		include_once __DIR__ . '/../includes/header.php';

         // Afficher la sidebar seulement si l'admin est connecté
         if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
             include_once __DIR__ . '/../includes/admin_sidebar.php';
         }

		// Inclusion de la vue principale
		include_once __DIR__ . '/../views/formulaireArticle.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
    }

    private function handleFormSubmission($id = null) {
        try {
            // Validation des données
            $titre = isset($_POST['titre']) ? htmlspecialchars($_POST['titre'], ENT_QUOTES, 'UTF-8') : '';
            $contenu = isset($_POST['contenu']) ? htmlspecialchars($_POST['contenu'], ENT_QUOTES, 'UTF-8') : '';
            $categorie = filter_input(INPUT_POST, 'categorie', FILTER_VALIDATE_INT);

            if (empty($titre) || empty($contenu) || $categorie === false) {
                throw new Exception("Tous les champs obligatoires doivent être remplis et valides");
            }

            // Traitement des images (optionnelles)
            $images = [];
            $keep_existing_images = true; // Par défaut, on garde les images existantes

            if (isset($_FILES['image']) && $_FILES['image']['error'][0] !== UPLOAD_ERR_NO_FILE) {
                $keep_existing_images = false; // Si de nouvelles images sont uploadées, on ne garde pas les anciennes
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $uploadDir = 'assets/images/articles/';
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

            // Création ou modification de l'objet Article
            $article = new Article([
                'id' => $id,
                'titre' => $titre,
                'contenu' => $contenu,
                'id_categorie' => $categorie,
                'images' => $images,
                'keep_existing_images' => $keep_existing_images
            ]);

            // Insertion ou mise à jour dans la base de données
            if ($id) {
                if ($article->updateArticle()) {
                    $_SESSION['success'] = "Article modifié avec succès";
                } else {
                    throw new Exception("Erreur lors de la modification dans la base de données");
                }
            } else {
                if ($article->insererArticle()) {
                    $_SESSION['success'] = "Article créé avec succès";
                } else {
                    throw new Exception("Erreur lors de l'insertion dans la base de données");
                }
            }

            // Redirection pour éviter la re-soumission du formulaire
            header('Location: index.php?page=AdminArticles');
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

            if (!$data || !isset($data['article_id']) || !isset($data['image_path'])) {
                throw new Exception('Données invalides');
            }

            require_once __DIR__ . '/../models/Article.php';
            
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
}


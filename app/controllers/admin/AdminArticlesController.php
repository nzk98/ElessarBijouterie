<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class AdminArticlesController {
    public function index() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }
        require_once __DIR__ . '/../../models/Article.php';
        
        // Gestion des actions
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'delete':
                    if (isset($_GET['id'])) {
                        $id = intval($_GET['id']);
                        if ($id > 0) {
                            Article::deleteArticle($id);
                        }
                    }
                    break;
                case 'update':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->handleUpdate();
                    }
                    break;
            }
            header('Location: index.php?page=AdminArticles');
            exit();
        }

        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];
        $articles = Article::getAllArticlesFull();
        include_once __DIR__ . '/../../includes/header.php';
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../../includes/admin_sidebar.php';
        }
        include_once __DIR__ . '/../../views/admin/adminArticles.php';
        include_once __DIR__ . '/../../includes/footer.php';
    }

    public function form() {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }

        require_once __DIR__ . '/../../models/Article.php';
        require_once __DIR__ . '/../../models/Categorie.php';

        $article = null;
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($id > 0) {
                $article = Article::getArticleById($id);
            }
        }

        $categories = Categorie::getAllCategories();
        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];

        include_once __DIR__ . '/../../includes/header.php';
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            include_once __DIR__ . '/../../includes/admin_sidebar.php';
        }
        include_once __DIR__ . '/../../views/admin/AdminFormArticle.php';
        include_once __DIR__ . '/../../includes/footer.php';
    }

    private function handleUpdate() {
        if (!isset($_POST['id']) || !isset($_POST['titre']) || !isset($_POST['contenu']) || !isset($_POST['categorie'])) {
            return;
        }

        $articleData = [
            'id' => intval($_POST['id']),
            'titre' => $_POST['titre'],
            'contenu' => $_POST['contenu'],
            'id_categorie' => intval($_POST['categorie']),
            'images' => []
        ];

        // Gestion des images
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $uploadDir = 'uploads/articles/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = uniqid() . '_' . $_FILES['images']['name'][$key];
                    $uploadFile = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($tmp_name, $uploadFile)) {
                        $articleData['images'][] = $uploadFile;
                    }
                }
            }
        }

        $article = new Article($articleData);
        $article->updateArticle();
    }
}

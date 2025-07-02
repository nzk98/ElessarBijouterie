<?php

require_once __DIR__ . '/../../models/CategorieArticle.php';
require_once __DIR__ . '/../../models/CategorieCreation.php';

class AdminCategoriesController {
    private $categorieArticleModel;
    private $categorieCreationModel;

    public function __construct() {
        $this->categorieArticleModel = new CategorieArticle();
        $this->categorieCreationModel = new CategorieCreation();
    }

    public function index() {
        // Traiter les actions d'ajout et de suppression
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_categorie_article'])) {
                $this->addCategorieArticle();
            } elseif (isset($_POST['add_categorie_creation'])) {
                $this->addCategorieCreation();
            }
        }
        
        if (isset($_GET['delete_article'])) {
            $this->deleteCategorieArticle();
        }
        
        if (isset($_GET['delete_creation'])) {
            $this->deleteCategorieCreation();
        }

        // Récupérer toutes les catégories
        $categories_article = $this->categorieArticleModel->getAll();
        $categories_creation = $this->categorieCreationModel->getAll();

        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];

        // Inclure le header
        require_once __DIR__ . '/../../includes/header.php';
        
        // Inclure la sidebar
        require_once __DIR__ . '/../../includes/admin_sidebar.php';

        // Afficher la vue principale
        require_once __DIR__ . '/../../views/admin/adminCategories.php';

        // Inclure le footer
        require_once __DIR__ . '/../../includes/footer.php';
    }

    public function addCategorieArticle() {
        if (isset($_POST['nom_categorie_article'])) {
            $nom = trim($_POST['nom_categorie_article']);
            if (!empty($nom)) {
                $this->categorieArticleModel->add($nom);
            }
        }
        header('Location: index.php?page=AdminCategories');
        exit();
    }

    public function addCategorieCreation() {
        if (isset($_POST['nom_categorie_creation'])) {
            $nom = trim($_POST['nom_categorie_creation']);
            if (!empty($nom)) {
                $this->categorieCreationModel->add($nom);
            }
        }
        header('Location: index.php?page=AdminCategories');
        exit();
    }

    public function deleteCategorieArticle() {
        $id = (int)$_GET['delete_article'];
        $this->categorieArticleModel->delete($id);
        header('Location: index.php?page=AdminCategories');
        exit();
    }

    public function deleteCategorieCreation() {
        $id = (int)$_GET['delete_creation'];
        $this->categorieCreationModel->delete($id);
        header('Location: index.php?page=AdminCategories');
        exit();
    }
} 
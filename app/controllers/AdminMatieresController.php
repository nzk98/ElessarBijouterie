<?php
session_start();

class AdminMatieresController {
    private $matiereModel;

    public function __construct() {
        require_once __DIR__ . '/../models/Matiere.php';
        $this->matiereModel = new Matiere();
    }

    public function index() {
        // Vérifier si l'admin est connecté
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header('Location: index.php?page=Accueil');
            exit();
        }

        // Traiter les actions d'ajout et de suppression
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_matiere'])) {
                $this->addMatiere();
            }
        }
        
        if (isset($_GET['delete'])) {
            $this->deleteMatiere();
        }

        // Récupérer toutes les matières
        $matieres = $this->matiereModel->getAll();

        // Configuration de la page
        $pageTitle = "Gestion des Matières";
        $pageStyle = "admin.css";
        $jsFile = ["admin.js"];

        // Inclusion des vues
        include_once __DIR__ . '/../includes/header.php';
        include_once __DIR__ . '/../includes/admin_sidebar.php';
        include_once __DIR__ . '/../views/adminMatieres.php';
        include_once __DIR__ . '/../includes/footer.php';
    }

    public function addMatiere() {
        if (isset($_POST['nom_matiere'])) {
            $nom = trim($_POST['nom_matiere']);
            if (!empty($nom)) {
                $this->matiereModel->add($nom);
            }
        }
        header('Location: index.php?page=AdminMatieres');
        exit();
    }

    public function deleteMatiere() {
        if (isset($_GET['delete'])) {
            $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
            if ($id !== false) {
                $this->matiereModel->delete($id);
            }
        }
        header('Location: index.php?page=AdminMatieres');
        exit();
    }
} 
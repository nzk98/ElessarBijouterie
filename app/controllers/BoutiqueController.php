<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'app/models/Creation.php';
require_once 'app/models/CategorieCreation.php';
require_once 'app/models/Matiere.php';

class BoutiqueController {
	private $creationModel;
	private $categorieModel;

	public function __construct() {
		$this->creationModel = new Creation();
		$this->categorieModel = new CategorieCreation();
	}

	public function index() {
		$pageStyle = "boutique.css";
		$jsFile = ["boutique.js"];

		// Récupérer les catégories
		$categories = $this->categorieModel->getAllCategories();

		// Récupérer les matières
		$matieres = Matiere::getAll();

		// Récupération des filtres
		$selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];
		$selectedMatieres = isset($_GET['matieres']) ? $_GET['matieres'] : [];
		if (!is_array($selectedMatieres)) {
			$selectedMatieres = [];
		}
		$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id-desc';

		// Récupération des créations filtrées
		$creations = $this->creationModel->getFilteredCreations($selectedCategories, $selectedMatieres, $sort);

		// Inclusion du header commun
		$pageTitle = "Boutique - Bijoux artisanaux faits main";
		$metaDesc = "Explorez ma boutique en ligne de bijoux artisanaux : bagues, colliers, bracelets et créations sur-mesure, réalisés à la main.";
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale avec les créations
		include_once __DIR__ . '/../views/boutique.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}
}



?>
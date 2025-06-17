<?php
require_once 'app/models/Creation.php';
require_once 'app/models/CategorieCreation.php';

class BoutiqueController {
	private $creationModel;
	private $categorieModel;

	public function __construct() {
		$this->creationModel = new Creation();
		$this->categorieModel = new CategorieCreation();
	}

	public function index() {
		$pageStyle = "boutique.css";
		$jsFile = ["cart.js", "boutique.js"];

		// Récupérer les catégories
		$categories = $this->categorieModel->getAllCategories();

		
		// Récupération des filtres
		$selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];
		$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id-desc';

		// S'assurer que les tableaux sont bien des tableaux
		if (!is_array($selectedCategories)) {
			$selectedCategories = [];
		}
	

		// Récupération des créations filtrées
		$creations = $this->creationModel->getFilteredCreations($selectedCategories, $sort);

		// Inclusion du header commun
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale avec les créations
		include_once __DIR__ . '/../views/boutique.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}
}



?>
<?php

// Test de la connexion à la base de données
require_once __DIR__ . '/app/models/Database.php';

spl_autoload_register(function ($class) {
    $paths = ["app/controllers", "app/models"];
    foreach ($paths as $path) {
        $file = __DIR__. "/" .$path. "/" .$class. ".php";
        if(file_exists($file)){
            require_once $file;
            return;
        }
    }
});

$page = isset($_GET['page']) ? $_GET['page'] : 'Accueil';

switch($page){
    case 'Accueil':
        $controller = new HomeController();
        $controller -> index();
        break;
        
    case 'Presentation':
        $controller = new PresentationController();
        $controller -> index();
        break;
            
    case 'Contact':
        $controller = new ContactController();
        $controller -> index();
        break;
                
    case "Boutique":
        $controller = new BoutiqueController();
        $controller -> index();
        break;

    case "Produits":
        $controller = new ProduitsController();
        $controller -> index();
        break;
                    
    case "Blog":
        $controller = new BlogController();
        $controller -> index();
        break;

    case "Article":
        $controller = new ArticleController();
        $controller -> index();
        break;
                        
    case "Connexion":
        $controller = new ConnexionController();
        $controller -> index();
        break;
                            
    case "Inscription":
        $controller = new InscriptionController();
        $controller -> index();
        break;

    case "Mdpoublie":
        $controller = new MdpoublieController();
        $controller -> index();
        break;
                                
    case "Panier":
        $controller = new PanierController();
        $controller -> index();
        break;
                                    
    case "Paiement":
        $controller = new PaiementController();
        $controller -> index();
        break;

    case "Historique":
        $controller = new HistoriqueController();
        $controller -> index();
        break;

    case "Dashboard":
        $controller = new DashboardController();
        $controller -> index();
        break;

    case "HistoriqueCommandes":
        $controller = new HistoriqueCommandesController();
        $controller -> index();
        break;

    case "ProfilUtilisateur":
        $controller = new ProfilUtilisateurController();
        $controller -> index();
        break;

    case "AdminLog":
        $controller = new AdminLogController();
        $controller -> index();
        break;

    case "AdminFormCreation":
        $controller = new AdminFormCreationController();
        $controller -> index();
        break;
    
    case "AdminFormArticle":
        $controller = new AdminFormArticle();
        $controller->index();
        break;
        
    case "admin":
    	$controller = new AdminController();
		$controller->index(); // Accès autorisé au dashboard
		break;

    case "Deconnexion":
        $controller = new DeconnexionController();
        $controller->index();
        break;

    case "AdminRegister-espace-2547":
        $controller = new AdminRegisterController();
        $controller->index();
        break;

    case "AdminCarousel":
        $controller = new AdminCarouselController();
        if (isset($_GET['action']) && $_GET['action'] === 'toggleCarousel') {
            $controller->toggleCarousel();
        } else {
            $controller->index();
        }
        break;
        
    case "AdminManage":
        $controller = new AdminManageController();
        $controller->index();
        break;

    case "AdminUsers":
        $controller = new AdminUsersController();
        $controller->index();
        break;

    case "AdminCreations":
        $controller = new AdminCreationsController();
        $controller->index();
        break;
        
    case "AdminArticles":
        $controller = new AdminArticlesController();
        $controller->index();
        break;
        
    case "AdminCategories":
        $controller = new AdminCategoriesController();
        $controller->index();
        break;

    case 'AdminMatieres':
        $controller = new AdminMatieresController();
        $controller->index();
        break;
        
    default:
        $controller = new HomeController();
        $controller -> index();
        break;
}

?>
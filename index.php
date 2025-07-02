<?php
// Démarrage de session pour toute l'application
session_start();

// Test de la connexion à la base de données
require_once __DIR__ . '/app/models/Database.php';

require_once __DIR__ . '/vendor/autoload.php';

spl_autoload_register(function ($class) {
    $paths = ["app/controllers", "app/models","app/controllers/admin", "app/models/admin", "Doc"];
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
        if (isset($_GET['action']) && $_GET['action'] === 'processForm') {
            $controller->processForm();
        } else {
            $controller->index();
        }
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
        $controller = new HistoriqueCommandesController();
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
        require_once __DIR__ . '/app/controllers/admin/AdminLogController.php';
        $controller = new AdminLogController();
        $controller->index();
        break;

    case "AdminFormCreation":
        require_once __DIR__ . '/app/controllers/admin/AdminFormCreationController.php';
        $controller = new AdminFormCreationController();
        $controller->index();
        break;
    
    case "AdminFormArticle":
        require_once __DIR__ . '/app/controllers/admin/AdminFormArticle.php';
        $controller = new AdminFormArticle();
        $controller->index();
        break;
        
    case "admin":
        require_once __DIR__ . '/app/controllers/admin/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        break;

    case "Deconnexion":
        $controller = new DeconnexionController();
        $controller->index();
        break;

    case "AdminRegister-espace-2547":
        require_once __DIR__ . '/app/controllers/admin/AdminRegisterController.php';
        $controller = new AdminRegisterController();
        $controller->index();
        break;

    case "AdminCarousel":
        require_once __DIR__ . '/app/controllers/admin/AdminCarouselController.php';
        $controller = new AdminCarouselController();
        if (isset($_GET['action']) && $_GET['action'] === 'toggleCarousel') {
            $controller->toggleCarousel();
        } else {
            $controller->index();
        }
        break;
        
    case "AdminManage":
        require_once __DIR__ . '/app/controllers/admin/AdminManageController.php';
        $controller = new AdminManageController();
        $controller->index();
        break;

    case "AdminUsers":
        require_once __DIR__ . '/app/controllers/admin/AdminUsersController.php';
        $controller = new AdminUsersController();
        $controller->index();
        break;

    case "AdminCreations":
        require_once __DIR__ . '/app/controllers/admin/AdminCreationsController.php';
        $controller = new AdminCreationsController();
        $controller->index();
        break;
        
    case "AdminArticles":
        require_once __DIR__ . '/app/controllers/admin/AdminArticlesController.php';
        $controller = new AdminArticlesController();
        $controller->index();
        break;
        
    case "AdminCategories":
        require_once __DIR__ . '/app/controllers/admin/AdminCategoriesController.php';
        $controller = new AdminCategoriesController();
        $controller->index();
        break;

    case 'AdminMatieres':
        require_once __DIR__ . '/app/controllers/admin/AdminMatieresController.php';
        $controller = new AdminMatieresController();
        $controller->index();
        break;

    case "MentionsLegales":
        $controller = new MentionsLegalesController();
        $controller->index();
        break;

    case "CGV":
        $controller = new CGVController();
        $controller->index();
        break;

    default:
        $controller = new HomeController();
        $controller -> index();
        break;
}

?>
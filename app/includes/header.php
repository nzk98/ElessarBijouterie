<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$cartCount = 0;
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $cartCount += $item['quantite'];
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="description" content="<?php echo isset($metaDesc) ? strip_tags($metaDesc) : "Bienvenue sur mon site de bijouterie"; ?>">
	<title><?php echo isset($pageTitle) ? strip_tags($pageTitle) : "ElessarBijouterie - Page d'accueil"; ?></title>
	<!-- Lien CSS -->
	<link rel="stylesheet" href="assets/css/style.css">
	<!-- Lien Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<?php
		if (isset($pageStyle)) {
			$cssFile = 'assets/css/' . strip_tags($pageStyle);
			if (file_exists($cssFile)) {
				echo '<link rel="stylesheet" type="text/css" href="' . $cssFile . '?v=' . filemtime($cssFile) . '">';
			}
		}
	?>
</head>
<body>
    <!-- En-tête du site -->
    <header>
        <nav class="main-nav">
            <div class="logo">
                <img src="assets/images/logo.jpg" class="logo-img" alt="Logo Elessar Bijouterie">
                <h1>Elessar Bijouterie</h1>
            </div>
            <div class="burger-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <ul class="nav-links">
                <li><a href="index.php?page=Accueil">Accueil</a></li>
                <li><a href="index.php?page=Presentation">Présentation</a></li>
                <li><a href="index.php?page=Boutique">Boutique</a></li>
                <li><a href="index.php?page=Blog">Blog</a></li>
                <li><a href="index.php?page=Contact">Contact</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="index.php?page=Dashboard">Mon Dashboard </a></li>
                    <li><a href="index.php?page=Deconnexion">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="index.php?page=Connexion">Connexion</a></li>
                <?php endif; ?>
                <li><a href="index.php?page=Panier" class="cart-icon">
                    <svg class="cart-logo" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 22C9.55228 22 10 21.5523 10 21C10 20.4477 9.55228 20 9 20C8.44772 20 8 20.4477 8 21C8 21.5523 8.44772 22 9 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20 22C20.5523 22 21 21.5523 21 21C21 20.4477 20.5523 20 20 20C19.4477 20 19 20.4477 19 21C19 21.5523 19.4477 22 20 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1 1H5L7.68 14.39C7.77144 14.8504 8.02191 15.264 8.38755 15.5583C8.75318 15.8526 9.2107 16.009 9.68 16H19.4C19.8693 16.009 20.3268 15.8526 20.6925 15.5583C21.0581 15.264 21.3086 14.8504 21.4 14.39L23 6H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="cart-count"><?php echo $cartCount; ?></span>
                </a></li>
            </ul>
        </nav>
    </header>
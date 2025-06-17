<?php

class DashboardController {
    public function index() {
        $pageStyle = "dashboard.css";
        $jsFile = ["dashboard.js"];

       // Inclusion du header commun
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
		include_once __DIR__ . '/../views/dashboard.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';

    }
}

    

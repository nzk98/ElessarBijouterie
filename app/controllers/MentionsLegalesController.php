<?php

class MentionsLegalesController {
    
    public function index() {
        // Inclure le header
        require_once __DIR__ . '/../includes/header.php';
        
        // Inclure la vue des mentions légales
        require_once __DIR__ . '/../views/mentionsLegales.php';
        
        // Inclure le footer
        require_once __DIR__ . '/../includes/footer.php';
    }
} 
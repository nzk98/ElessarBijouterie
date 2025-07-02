<?php

class CGVController {
    
    public function index() {
        header('Location: Doc/CGV.pdf');
        exit;
    }
} 
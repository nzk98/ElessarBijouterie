<?php

class PresentationController {
	public function index() {
		$pageStyle = "presentation.css";
		// $jsFile = ["cart-counter.js","presentation.js"];
		$jsFile = ["presentation.js"];

		// Inclusion du header commun
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
		include_once __DIR__ . '/../views/presentation.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}
}







?>
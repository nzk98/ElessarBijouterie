<?php
 class ContactController {
	public function index() {
		$pageStyle = "contact.css";
		// $jsFile = ["cart-counter.js","contact.js"];
		$jsFile = ["contact.js"];

		// Inclusion du header commun
		include_once __DIR__ . '/../includes/header.php';

		// Inclusion de la vue principale
		include_once __DIR__ . '/../views/contact.php';

		// Inclusion du footer commun
		include_once __DIR__ . '/../includes/footer.php';
	}
 }




?>
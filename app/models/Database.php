<?php

class Database {
	// Stocke une seule instance de la connexion PDO (Singleton)
	private static ?PDO $instance = null;

	// Constructeur privé pour empêcher l'instanciation directe de la classe
	private function __construct() {
		// Configuration des informations de connexion à la base de données
		$host = "localhost";
		$dbname = "elessardbijouterie";
		$username = "root";
		$password = "";

		try {
			// Création de l'instance PDO pour la connexion à la base de données
			self::$instance = new PDO("mysql:host=$host;dbname=$dbname;port=3306", $username, $password);
			// Configuration de PDO pour afficher les erreurs sous forme d'exceptions
			self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			// En cas d'erreur de connexion, afficher un message et arrêter l'exécution du script
			die("Erreur de connexion : " . $e->getMessage());
		}
	}

	// Méthode statique pour récupérer l'instance unique de la connexion PDO
	public static function getInstance(): PDO {
		if (self::$instance === null) {
				new self(); // Appelle le constructeur privé pour initialiser l'instance PDO
		}
		return self::$instance;
	}

	// Méthode pour tester la connexion à la base de données
	public static function testConnexion(): bool {
		try {
			$pdo = self::getInstance();
			$pdo->query('SELECT 1');
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}
}

// // Test de la connexion
// if (Database::testConnexion()) {
//     echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin: 10px; border-radius: 5px;'>";
//     echo "✅ La connexion à la base de données est établie avec succès !";
//     echo "</div>";
// } else {
//     echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px; border-radius: 5px;'>";
//     echo "❌ Erreur de connexion à la base de données.";
//     echo "</div>";
// }

$dbh = Database::getInstance();


?>
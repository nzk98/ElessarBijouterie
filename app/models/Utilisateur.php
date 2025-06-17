<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('Database.php');
require('Fonction.php');

class Utilisateur {
	private ?int $id_utilisateur;
	private string $nom_utilisateur;
	private string $prenom_utilisateur;
	private string $email_utilisateur;
	private string $mot_de_passe;
	private string $civilite;
	private string $telephone;
	private string $adresse;
	private string $code_postal;
	private string $ville;
	private ?int $id_adresse;
	

	public function __construct(array $data = []) {
		$this->id_utilisateur = $data['id_utilisateur'] ?? null;
		$this->nom_utilisateur = $data['nom_utilisateur'] ?? '';
		$this->prenom_utilisateur = $data['prenom_utilisateur'] ?? '';
		$this->email_utilisateur = $data['email_utilisateur'] ?? '';
		$this->mot_de_passe = $data['mot_de_passe'] ?? '';
		$this->civilite = $data['civilite'] ?? '';
		$this->telephone = $data['telephone'] ?? '';
		$this->adresse = $data['adresse'] ?? '';
		$this->code_postal = $data['code_postal'] ?? '';
		$this->ville = $data['ville'] ?? '';
		$this->id_adresse = $data['id_adresse'] ?? null;
	}

	// === GETTERS ===
	public function getIdUtilisateur(): ?int { 
		return $this->id_utilisateur; 
	}
	public function getNomUtilisateur(): string { 
		return $this->nom_utilisateur; 
	}
	public function getPrenomUtilisateur(): string { 
		return $this->prenom_utilisateur; 
	}
	public function getEmailUtilisateur(): string { 
		return $this->email_utilisateur; 
	}
	public function getMotDePasse(): string {
		return $this->mot_de_passe; 
	}
	public function getCivilite(): string { 
		return $this->civilite; 
	}
	public function getRole(): string { 
		return $this->role; 
	}
	public function getAdresse(): string {
		return $this->adresse;
	}
	public function getCodePostal(): string {
		return $this->code_postal;
	}
	public function getVille(): string {
		return $this->ville;
	}
	public function getTelephone(): string {
		return $this->telephone;
	}

	// === SETTERS ===
	public function setNomUtilisateur(string $nom): void { 
		$this->nom_utilisateur = $nom; 
	}
	public function setPrenomUtilisateur(string $prenom): void { 
		$this->prenom_utilisateur = $prenom; 
	}
	public function setEmailUtilisateur(string $email): void { 
		$this->email_utilisateur = $email; 
	}
	public function setMotDePasse(string $mdp): void { 
		$this->mot_de_passe = password_hash($mdp, PASSWORD_DEFAULT); 
	}
	public function setCivilite(string $civilite): void { 
		$this->civilite = $civilite; 
	}
	public function setRole(string $role): void { 
		$this->role = $role; 
	}
	public function setAdresse(string $adresse): void {
		$this->adresse = $adresse;
	}
	public function setCodePostal(string $code_postal): void {
		$this->code_postal = $code_postal;
	}
	public function setVille(string $ville): void {
		$this->ville = $ville;
	}
	public function setTelephone(string $telephone): void {
		$this->telephone = $telephone;
	}

	// === MÉTHODES CRUD ===
	
	// MÉTHODE D'INSCRIPTION
	public function inscription(): bool {
		try {
			$db = Database::getInstance();
			$stmt = $db->prepare("INSERT INTO utilisateurs (Nom_Utilisateur, Prenom_Utilisateur, Email_Utilisateur, MotdePasse_Utilisateur, Civilite_Utilisateur, Telephone_Utilisateur) VALUES (:nom, :prenom, :email, :mdp, :civilite, :telephone)");
			return $stmt->execute([
				':nom' => $this->nom_utilisateur,
				':prenom' => $this->prenom_utilisateur,
				':email' => $this->email_utilisateur,
				':mdp' => $this->mot_de_passe,
				':civilite' => $this->civilite,
				':telephone' => $this->telephone
			]);
		} catch (PDOException $e) {
			return false;
		}
	}

	// MÉTHODE DE CONNEXION
	public static function se_connecter(string $email, string $password): bool {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		try {
			$db = Database::getInstance();
			$stmt = $db->prepare("SELECT * FROM utilisateurs WHERE Email_Utilisateur = :email");
			$stmt->execute([':email' => $email]);
			$user = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($user && password_verify($password, $user['MotdePasse_Utilisateur'])) {
				// Création de la session utilisateur
				$_SESSION['user'] = [
					'id' => $user['ID_Utilisateur'],
					'email' => $user['Email_Utilisateur'],
					'nom' => $user['Nom_Utilisateur'],
					'prenom' => $user['Prenom_Utilisateur'],
					'civilite' => $user['Civilite_Utilisateur'],
					'telephone' => $user['Telephone_Utilisateur']
				];
				// Génération du token de session
				require_once('Fonction.php');
				$_SESSION['token'] = genererToken();
				return true;
			}
		} catch (PDOException $e) {}
		return false;
	}

	// MÉTHODE DE MODIFICATION DU PROFIL
	public function gerer_profil(): bool {
		try {
			$db = Database::getInstance();

			// 1. Vérifier ou insérer le code postal
			$stmt = $db->prepare("SELECT ID_CP FROM codepostal WHERE CodePostal_CP = :cp");
			$stmt->execute([':cp' => $this->code_postal]);
			$cp = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($cp) {
				$id_cp = $cp['ID_CP'];
			} else {
				$stmt = $db->prepare("INSERT INTO codepostal (CodePostal_CP) VALUES (:cp)");
				$stmt->execute([':cp' => $this->code_postal]);
				$id_cp = $db->lastInsertId();
			}

			// 2. Vérifier ou insérer la ville
			$stmt = $db->prepare("SELECT ID_Ville FROM villes WHERE Nom_Ville = :ville AND ID_CP = :id_cp");
			$stmt->execute([':ville' => $this->ville, ':id_cp' => $id_cp]);
			$ville = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($ville) {
				$id_ville = $ville['ID_Ville'];
			} else {
				$stmt = $db->prepare("INSERT INTO villes (Nom_Ville, ID_CP) VALUES (:ville, :id_cp)");
				$stmt->execute([':ville' => $this->ville, ':id_cp' => $id_cp]);
				$id_ville = $db->lastInsertId();
			}

			// 3. Insérer ou mettre à jour l'adresse
			if (!empty($this->id_adresse)) {
				// Mise à jour de l'adresse existante
				$stmt = $db->prepare("UPDATE adresse SET Adresse = :adresse, ID_Ville = :id_ville WHERE ID_Adresse = :id_adresse");
				$stmt->execute([
					':adresse' => $this->adresse,
					':id_ville' => $id_ville,
					':id_adresse' => $this->id_adresse
				]);
				$id_adresse = $this->id_adresse;
			} else {
				// Nouvelle adresse
				$stmt = $db->prepare("INSERT INTO adresse (Adresse, ID_Ville) VALUES (:adresse, :id_ville)");
				$stmt->execute([
					':adresse' => $this->adresse,
					':id_ville' => $id_ville
				]);
				$id_adresse = $db->lastInsertId();
			}

			// 4. Mise à jour de l'utilisateur
			$sql = "UPDATE utilisateurs SET 
				Nom_Utilisateur = :nom, 
				Prenom_Utilisateur = :prenom, 
				Civilite_Utilisateur = :civilite, 
				Email_Utilisateur = :email, 
				Telephone_Utilisateur = :telephone,
				ID_Adresse = :id_adresse";

			$params = [
				':id' => $this->id_utilisateur,
				':nom' => $this->nom_utilisateur,
				':prenom' => $this->prenom_utilisateur,
				':civilite' => $this->civilite,
				':email' => $this->email_utilisateur,
				':telephone' => $this->telephone,
				':id_adresse' => $id_adresse
			];

			// Ajouter le mot de passe uniquement s'il est fourni
			if (!empty($this->mot_de_passe)) {
				$sql .= ", MotdePasse_Utilisateur = :mdp";
				$params[':mdp'] = $this->mot_de_passe;
			}

			$sql .= " WHERE ID_Utilisateur = :id";
			
			$stmt = $db->prepare($sql);
			$stmt->execute($params);

			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	public function MDPOublie(){
		global $dbh;
		
			if(!empty($_POST['email'])){
				$sql ="SELECT Email_utilisateur FROM utilisateurs where :email = Email_utilisateur";
				$req = $dbh->prepare($sql);
				$req->bindParam(':email',$_POST['email'],PDO::PARAM_STR);
				if($req->execute()){
					$result_mail = $req->fetch(PDO::FETCH_ASSOC);
					$token = genererToken();

					 //Create an instance; passing `true` enables exceptions
					 $mail = new PHPMailer(true);

					 try {
					 //Server settings
					 $mail->isSMTP();                                            //Send using SMTP
					 $mail->Host       ='dwwm2425.fr';                     //Set the SMTP server to send through
					 $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
					 $mail->Username   = 'contact@dwwm2425.fr';                     //SMTP username
					 $mail->Password   = '!cci18000Bourges!';                               //SMTP password
					 $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
					 $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
	 
					 //Recipients
					 $mail->setFrom('no-reply@dww2425.fr', 'Formulaire contact from - monSite');
					 $mail->addAddress($result_mail['Email_utilisateur'],'nicolas','zinck');     //Add a recipient
		 
					 //Content
					 $mail->isHTML(true);                                  //Set email format to HTML
					 $mail->Subject = "Réccupérer votre Mot de Passe CCI-Formation 18 ";
					 $mail->Body    = "Bonjour voici le lien pour changer votre Mot de Passe <br> Votre lien : http://projet/CCI-Back/index.php?page=mdpoublie&token={$token}";
					 $mail->AltBody = "Bonjour voici le lien pour changer votre Mot de Passe<br> Votre lien :  http://projet/CCI-Back/index.php?page=mdpoublie&token={$token}";
					if($mail->send()){

					
					 $sql = 'UPDATE Utilisateurs SET Token_MDPOublie_Utilisateur = :token WHERE Email_Utilisateur = :email';
					 $req =  $dbh->prepare($sql);
					 $req->bindValue(':token', $token,PDO::PARAM_STR);
					 $req->bindParam(':email',$result_mail['Email_utilisateur'],PDO::PARAM_STR);
					 if($req->execute()){
						
					 }
					}
					 else {
					 }

					 } catch (Exception $e) {
					 }

				}	
				
			}
	}

	public function newMdp(){
			
		global $dbh;
		$newMDP = $_POST['newMDP'];
		$this->setMotDePasse($newMDP);

		$sql ="UPDATE utilisateurs set PassWord_Utilisateur = :newMDP where id_Utilisateur = :id";
		$req = $dbh->prepare($sql);
		$req->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
		$req->bindValue(':newMDP', $this->mot_de_passe, PDO::PARAM_STR);
		if($req->execute()){
			return true;
		}		
	}			

	// MÉTHODE POUR RÉCUPÉRER LES DONNÉES D'UN UTILISATEUR PAR SON ID
	public static function getUtilisateurById(int $id): ?Utilisateur {
		try {
			$db = Database::getInstance();
			$stmt = $db->prepare("SELECT * FROM utilisateurs WHERE ID_Utilisateur = :id");
			$stmt->execute([':id' => $id]);
			$userData = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($userData) {
				return new Utilisateur([
					'id_utilisateur' => $userData['ID_Utilisateur'],
					'nom_utilisateur' => $userData['Nom_Utilisateur'],
					'prenom_utilisateur' => $userData['Prenom_Utilisateur'],
					'email_utilisateur' => $userData['Email_Utilisateur'],
					'mot_de_passe' => $userData['MotdePasse_Utilisateur'],
					'civilite' => $userData['Civilite_Utilisateur'],	
					'telephone' => $userData['Telephone_Utilisateur'],
					'adresse' => $userData['Adresse'] ?? '',
					'code_postal' => $userData['CodePostal'] ?? '',
					'ville' => $userData['Ville'] ?? '',
					'id_adresse' => $userData['ID_Adresse'] ?? null
				]);
			}
			return null;
		} catch (PDOException $e) {
			// Gérer l'erreur si nécessaire
			error_log("Erreur lors de la récupération de l'utilisateur par ID : " . $e->getMessage());
			return null;
		}
	}

	// MÉTHODE POUR RÉCUPÉRER LES DONNÉES COMPLÈTES D'UN UTILISATEUR (avec adresse, ville, code postal)
	public static function getDetailedUtilisateurById(int $id): ?array {
		try {
			$db = Database::getInstance();
			$stmt = $db->prepare("
				SELECT
					utilisateurs.ID_Utilisateur,
					utilisateurs.Nom_Utilisateur,
					utilisateurs.Prenom_Utilisateur,
					utilisateurs.Email_Utilisateur,
					utilisateurs.Civilite_Utilisateur AS civilite,
					utilisateurs.Telephone_Utilisateur AS telephone,
					adresse.Adresse,
					villes.Nom_Ville AS Ville,
					codepostal.CodePostal_CP AS CodePostal
				FROM
					utilisateurs
				LEFT JOIN
					adresse ON utilisateurs.ID_Adresse = adresse.ID_Adresse
				LEFT JOIN
					villes ON adresse.ID_Ville = villes.ID_Ville
				LEFT JOIN
					codepostal ON villes.ID_CP = codepostal.ID_CP
				WHERE
					utilisateurs.ID_Utilisateur = :id
			");
			$stmt->execute([':id' => $id]);
			$userData = $stmt->fetch(PDO::FETCH_ASSOC);

			return $userData ?: null; // Retourne les données sous forme de tableau associatif ou null

		} catch (PDOException $e) {
			// Gérer l'erreur si nécessaire
			error_log("Erreur lors de la récupération détaillée des données utilisateur : " . $e->getMessage());
			return null;
		}
	}

	public static function getAllUtilisateurs() {
		$db = Database::getInstance();
		$stmt = $db->query("SELECT * FROM utilisateurs");
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function deleteUtilisateur($id) {
		$db = Database::getInstance();
		$stmt = $db->prepare("DELETE FROM utilisateurs WHERE ID_Utilisateur = ?");
		return $stmt->execute([$id]);
	}

	public static function countAll() {
		$db = Database::getInstance();
		$stmt = $db->query("SELECT COUNT(*) FROM utilisateurs");
		return (int)$stmt->fetchColumn();
	}
}

?>
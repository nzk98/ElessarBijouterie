<?php
session_start();

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Utilisateur.php'; // Assurez-vous que le modèle Utilisateur est inclus

class ProfilUtilisateurController {
    public function index() {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            header('Location: index.php?page=Connexion');
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $message = '';

        // --- Traitement de la soumission du formulaire de mise à jour ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $formData = [
                'id_utilisateur' => $userId,
                'civilite' => $_POST['civilite'] ?? '',
                'nom_utilisateur' => $_POST['nom'] ?? '',
                'prenom_utilisateur' => $_POST['prenom'] ?? '',
                'email_utilisateur' => $_POST['email'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'code_postal' => $_POST['code_postal'] ?? '',
                'ville' => $_POST['ville'] ?? ''
            ];

            // Inclure l'ID de l'adresse si disponible dans la session
            if (isset($_SESSION['user']['ID_Adresse'])) {
                $formData['id_adresse'] = $_SESSION['user']['ID_Adresse'];
            }

            // Gérer le mot de passe uniquement s'il est renseigné
            if (!empty($_POST['password'])) {
                $utilisateurToUpdate = new Utilisateur($formData);
                $utilisateurToUpdate->setMotDePasse($_POST['password']);
            } else {
                $utilisateurToUpdate = new Utilisateur($formData);
            }

            // Appeler la méthode de mise à jour du profil
            if ($utilisateurToUpdate->gerer_profil()) {
                $message = "<div class=\'alert alert-success\'>Votre profil a été mis à jour avec succès !</div>";
                 header('Location: index.php?page=ProfilUtilisateur');
                 exit();

            } else {
                $message = "<div class=\'alert alert-danger\'>Erreur lors de la mise à jour de votre profil.</div>";
            }
        }
        // --- Fin du traitement de la soumission ---

        // Récupérer les données de l'utilisateur pour l'affichage du formulaire
        $userData = Utilisateur::getDetailedUtilisateurById($userId);

        // Mettre à jour la session avec les données complètes si trouvées
        if ($userData) {
            $_SESSION['user'] = array_merge($_SESSION['user'], $userData);
             // S'assurer que ID_Adresse est aussi dans la session si la requête l'a récupéré
             if (isset($userData['ID_Adresse'])) {
                 $_SESSION['user']['ID_Adresse'] = $userData['ID_Adresse'];
             }
        }

        $pageStyle = "profilUtilisateur.css";
        $jsFile = "profilUtilsiateur.js";

        // Inclusion du header commun
        include_once __DIR__ . '/../includes/header.php';

        // Afficher le message de succès ou d'erreur si défini
        if (!empty($message)) {
            echo $message;
        }

        // Inclusion de la vue principale
        include_once __DIR__  . '/../views/profilUtilisateur.php';

        // Inclusion du footer commun
        include_once __DIR__ . '/../includes/footer.php';
    }
} 
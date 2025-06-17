<?php
session_start();
class MdpoublieController extends Utilisateur{
    private array $successMessage = [];
    private array $errorsMessage = [];
 
    public function index() {
        $this->messageMDP();

        $errorsMessage = $this->getErrorMessage();
        $successMessage = $this->getSuccessMessage();
        
        $metaDesc = "Entrez votre mail et recevez un mail pour reccupérer votre mot de passe";
        $pageTitle = "CCI Formation 18 - Modification de mot de passe";
        $pageStyle = "auth.css";
        $jsFile = "mdpoublie.js";

        //Inclusion du header commun
        include_once __DIR__ . '/../includes/header.php';

        
        if(!isset($_GET['token'])){
            header("location:index.php?page=home ");
            exit;
        }

        if(!$this->verifToken($_GET['token'])){
            header("location:index.php?page=home ");
            exit;
        }
        //Inclusion de la vue MDPOublie
        include_once __DIR__ . '/../views/mdpoublie.php';

        //Inclusion du footer commun
        include_once __DIR__ . '/../includes/footer.php';
        
    }

    public function getErrorMessage() : array {
        return $this->errorsMessage;
    }

    public function getSuccessMessage(): array {
        return $this->successMessage;
    }


    public function  verifToken(){
        global $dbh;
        $sql ='SELECT * FROM utilisateurs where Token_MDPOublie_Utilisateur = :token' ;
        $req = $dbh->prepare($sql);
        $req->bindParam(':token',$_GET['token'],PDO::PARAM_STR);
        if($req->execute()){
            $nb_Result = $req->rowCount();
            if($nb_Result == 1){
                $resultat = $req->fetch(PDO::FETCH_ASSOC);
                $id = $resultat['id_Utilisateur'];
                $_SESSION['id'] = $id ;
               return true;
            }else{
                return false;
            }
        }
    }

    public function messageMDP(){
        $newMDP = $_POST['newMDP'] ?? "";
        $repNewMDP = $_POST['repNewMDP'] ?? "";

        if(empty($newMDP) && empty($repNewMDP)){
            $this->errorsMessage[] = "⚠ Les mots de passe sont obligatoire";
        }
        elseif( empty($newMDP) || empty($repNewMDP)){
            $this->errorsMessage[] = "⚠ Veuillez entrer votre nouveau mot de passe dans les deux champs "; 
        }
        elseif($newMDP !== $repNewMDP){
            $this->errorsMessage[] = "⚠ Les deux mots de passe doivent être identique";
        }
        else{
            if($this->newMdp()){
                $this->successMessage[] = "✔ Votre mot de passe a bien été enregistrer";
            }
            else{
                $this->errorsMessage[] = "⚠ Erreur lors de l'enregistrement du mot de passe";
            }
        }
    }

}
$utilisateur = new mdpController;


$utilisateur->verifToken();

$utilisateur->messageMDP();






?>
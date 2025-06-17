<?php
function genererToken(){
    // Chaine de caractère pour le token
    $chaine = 'azertyuiopqsdfhjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789&é"(-è_çà)=';
    // Je transforme ma chaine en tableau
    $tableau = mb_str_split($chaine);
    // Je calcule la taille de la chaine de caractère avec count
    $longeur = count($tableau);
    // J'initialise une variable token vide
    $token = '';
    // On va générer une clé aléatoire avec une boucle for avec une longueur random entre 16 et 30
    for($i=0;$i<rand(16,30);$i++){
        // J'ajoute un caractère au token à chaque itération
        $token.= $tableau[rand(0,$longeur - 1)];
    }
    // Je hashe le token
    $token = md5(sha1($token));
    // J'enregistre mon token dans une session
    $_SESSION['token'] = $token;
    // Une fois mon token terminé je le retourne
    return $token;
}


?>
<?php

include_once "maLibUtils.php";	// Car on utilise la fonction valider()
include_once "modele.php";	// Car on utilise la fonction connecterUtilisateur()





/**
 * Cette fonction vérifie si le login/passe passés en paramètre sont légaux
 * Elle stocke les informations sur la personne dans des variables de session : session_start doit avoir été appelé...
 * Infos à enregistrer : pseudo, idUser, heureConnexion, isAdmin
 * Elle enregistre l'état de la connexion dans une variable de session "connecte" = true
 * L'heure de connexion doit être stockée au format date("H:i:s")
 * @pre login et passe ne doivent pas être vides
 * @param string $login
 * @param string $password
 * @return false ou true ; un effet de bord est la création de variables de session
 */
function verifUser($login,$password)
{
    if($login != "" && $password != "")
        if($idUserLogin = verifUserBDD($login,$password))
        {
            session_start();
            $_SESSION["pseudo"] = $login;
            $_SESSION["idUser"] = $idUserLogin;
            $_SESSION["heureConnexion"] = date("H:i:s");
            $_SESSION["connecte"] = true;
            return true;
        }
        else return false;
}

/**
 * Créé un nouvel utilisateur dans la base de donnée. La fonction vérifie avant que login et password ne sont pas vides,
 * et que l'utilisateur n'existe pas déjà
 * Renvoie true si l'user a été créé, false sinon
 * @param $login
 * @param $password
 * @return bool|Renvoie
 */
function createUser($login,$password){
    if($login != "" && $password != ""){
        $verif = verifUserBDD($login, $password);
        if($verif==0){ // on vérifie que l'user n'existe pas déjà
            return inscrireUser($login, $password);
        }
        return false;
    }
    return false;
}

/**
 * Indique si le client est un utilisateur qui s'est connecté à son compte.
 * Renvoie l'id de l'user s'il est connecté.
 * @return bool|int
 */
function estConnecte(){
    $idUser = valider("idUser", "SESSION");
    if($idUser)
        return $idUser;
    else
        return false;
}

/**
 * Deconnecte l'utilisateur
 */
function deconnexion(){
    unset($_SESSION["idUser"]);
}


/** Crée une variable de session idBrainstoCourant ainsi que master=0
 * @param $idUser
 * @param $codeBrainstoCourant
 */
function rejoindreBrainsto($idUser, $codeBrainstoCourant){
    $idBrainstoCourant=getChamp('br_id','brainstorm','br_code', $codeBrainstoCourant);

    $_SESSION["idBrainstoCourant"] = $idBrainstoCourant;
    $_SESSION["master"]=0;
    setUserBrainsto($idBrainstoCourant, $idUser);
}

/** Créé un brainsto dans la base ainsi qu'affecte une variable de session idBrainstoCourant et master=1
 * @param $idMaster
 * @param $titre
 * @param $description
 * @return Renvoie
 */
function creerBrainsto($idMaster, $titre, $description){
    $idBrainstoCourant = createBrainstorm($idMaster, $titre, $description);

    $_SESSION["idBrainstoCourant"] = $idBrainstoCourant;
    $_SESSION["master"]=1;
    setUserBrainsto($idBrainstoCourant, $idMaster);

    return $idBrainstoCourant;
}

/** Crée une variable de session avec l'$idBrainstoCourant du $codeBrainstoCourant pour le user d'id $idUser
 * @param $idUser
 * @param $codeBrainstoCourant
 */
function rejoindreMesBraintos($idUser, $codeBrainstoCourant){
    $idBrainstoCourant=getChamp('br_id','brainstorm','br_code', $codeBrainstoCourant);

    $_SESSION["idBrainstoCourant"] = $idBrainstoCourant;
    setUserBrainsto($idBrainstoCourant, $idUser);
}





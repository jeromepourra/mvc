<?php

abstract class RouterDictionnary {

    private $dictionnary = [
        "default" => "IndexController",
        "accueil" => "IndexController",
        // ACCOUNT
        "connexion" => ["account", "LoginController"],
        "inscription" => ["account", "RegisterController"],
        "confirmation-d-inscription" => ["account", "RegisterConfirmController"],
        "deconnexion" => ["account", "LogoutController"],
        "mon-compte" => ["account", "MyAccountController"],
        // ANNONCE
        "annonce" => ["annonce", "ViewAnnonce"],
        "deposer-une-annonce" => ["annonce", "CreateAnnonceController"],
        "annonces-utilisateur" => ["annonce", "ViewUserAnnoncesController"],
        "rechercher" => ["annonce", "ResearchAnnoncesController"],
        "retirer-annonce" => ["annonce", "RemoveAnnonceController"],
        "modifier-annonce" => ["annonce", "UpdateAnnonceController"],
    ];

    public function getFile($sKey) {
        if (array_key_exists($sKey, $this->dictionnary)) {
            return $this->dictionnary[$sKey];
        } else {
            throw new Exception("Aucune route n'a été trouvé pour l'url '{$sKey}'");
        }
    }

}
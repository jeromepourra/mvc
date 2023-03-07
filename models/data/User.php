<?php

namespace ModelData;

require_once ROOT . "models/data/Manager.php";

class User extends Manager {
    
    protected const PROP_CONVERTER = [
        "_id" => "intval",
        "pseudo" => "strval",
        "firstname" => "strval",
        "lastname" => "strval",
        "email" => "strval",
        "phone" => "strval",
        "birthday" => "strval",
        "bio" => "strval",
        "password" => "strval",
        "banned" => "boolval",
        "role" => "intval",
        "inscription_date" => "strval",
        "connexion_date" => "strval"
    ];

}
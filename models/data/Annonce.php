<?php

namespace ModelData;

require_once ROOT . "models/data/Manager.php";

class Annonce extends Manager {
    
    protected const PROP_CONVERTER = [
        "_id" => "intval",
        "user_id" => "intval",
        "category_id" => "intval",
        "title" => "strval",
        "price" => "floatval",
        "description" => "strval",
        "date" => "strval",
        "state" => "intval"
    ];

}
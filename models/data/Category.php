<?php

namespace ModelData;

require_once ROOT . "models/data/Manager.php";

class Category extends Manager {
    
    protected const PROP_CONVERTER = [
        "_id" => "intval",
        "name" => "strval"
    ];

}
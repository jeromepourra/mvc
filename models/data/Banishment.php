<?php

namespace ModelData;

require_once ROOT . "models/data/Manager.php";

class Banishment extends Manager {
    
    protected const PROP_CONVERTER = [
        "user_id" => "intval",
        "reason" => "strval",
        "date" => "strval"
    ];

}
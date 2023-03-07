<?php

namespace ModelData;

require_once ROOT . "models/data/Manager.php";

class AnnoncePicture extends Manager {
    
    protected const PROP_CONVERTER = [
        "_id" => "intval",
        "annonce_id" => "intval",
        "extension" => "strval"
    ];

}
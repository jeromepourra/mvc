<?php

class AnnoncesPicturesModel extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("annonces_pictures");
    }

    public function create($aValues) {

        $aColumns = ["annonce_id", "extension"];

        if (count($aColumns) === count($aValues)) {

            $sColumns = "`" . join("`,`", $aColumns) . "`";
            $sValues = "'" . join("','", $aValues) . "'";
    
            try {
                return $this->insert($sColumns, $sValues, [], true, "ModelData\AnnoncePicture");
            } catch (Exception $e) {
                throw $e;
            }

        } else {
            throw new ModelException(__METHOD__ . " le nombre de colonnes ne correspond pas au nombre de valeurs");
        }

    }

    public function removeFromId($nId) {
        try {
            $this->delete("_id = :id", [":id" => $nId]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function removeAllFromAnnonce($nAnnonce) {
        try {
            $this->delete("annonce_id = :annonce", [":annonce" => $nAnnonce]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getOneFromAnnonce($nAnnonceId) {
        try {
            return $this->read("*", "annonce_id = :id", [":id" => $nAnnonceId], "fetch", PDO::FETCH_CLASS, "ModelData\\AnnoncePicture");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getFromAnnonce($nAnnonceId) {
        try {
            return $this->read("*", "annonce_id = :id", [":id" => $nAnnonceId], "fetchAll", PDO::FETCH_CLASS, "ModelData\\AnnoncePicture");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getCountFromAnnonce($nAnnonceId) {
        try {
            $aFetch = $this->read("COUNT(*)", "annonce_id = :id", [":id" => $nAnnonceId], "fetch", PDO::FETCH_NUM);
            return (int) $aFetch[0];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getFromId($nId) {
        try {
            return $this->read("*", "_id = :id", [":id" => $nId], "fetch", PDO::FETCH_CLASS, "ModelData\\AnnoncePicture");
        } catch (Exception $e) {
            throw $e;
        }
    }

}
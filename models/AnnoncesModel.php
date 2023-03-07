<?php

class AnnoncesModel extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("annonces");
    }

    public function create($aValues) {

        $aColumns = ["user_id", "category_id", "title", "price", "description", "date"];

        if (count($aColumns) === count($aValues)) {

            $sColumns = "`" . join("`,`", $aColumns) . "`";
            $sValues = ":" . join(",:", $aColumns);
            $aBindValues = [];

            foreach ($aColumns as $nIndex => $sColumn) {
                $aBindValues += [":" . $sColumn => $aValues[$nIndex]];
            }
    
            try {
                return $this->insert($sColumns, $sValues, $aBindValues, true, "ModelData\\Annonce");
            } catch (Exception $e) {
                throw $e;
            }

        } else {
            throw new ModelException(__METHOD__ . " le nombre de colonnes ne correspond pas au nombre de valeurs");
        }

    }

    public function modify($nId, $aValues) {

        $aColumns = ["category_id", "title", "price", "description", "state"];

        if (count($aColumns) === count($aValues)) {

            $aUpdates = [];
            $aBindValues = [];

            foreach ($aColumns as $nIndex => $sColumn) {
                array_push($aUpdates, "`" . $aColumns[$nIndex] . "` = :" . $aColumns[$nIndex]);
                $aBindValues += [":" . $sColumn => $aValues[$nIndex]];
            }

            $sUpdates = join(" , ", $aUpdates);
    
            try {
                return $this->updates($sUpdates, "_id = '" . $nId . "'", $aBindValues);
            } catch (Exception $e) {
                throw $e;
            }

        } else {
            throw new ModelException(__METHOD__ . " le nombre de colonnes ne correspond pas au nombre de valeurs");
        }

    }

    public function remove($nId) {
        try {
            $this->delete("_id = :id", [":id" => $nId]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAll() {
        try {
            return $this->read("*", "", [], "fetchAll", PDO::FETCH_CLASS, "ModelData\\Annonce");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getFromResearch($sCondition, $aBindParams) {
        try {
            return $this->read("*", $sCondition, $aBindParams, "fetchAll", PDO::FETCH_CLASS, "ModelData\\Annonce");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getFromId($nId) {
        try {
            return $this->read("*", "_id = :id", [":id" => $nId], "fetch", PDO::FETCH_CLASS, "ModelData\\Annonce");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getFromUser($nUserId) {
        try {
            return $this->read("*", "user_id = :user", [":user" => $nUserId], "fetchAll", PDO::FETCH_CLASS, "ModelData\\Annonce");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getOnlineFromUser($nUserId) {
        try {
            return $this->read("*", "user_id = :user AND state = :state", [":user" => $nUserId, ":state" => Constants::ANNONCE_STATE_ONLINE], "fetchAll", PDO::FETCH_CLASS, "ModelData\\Annonce");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getFromUserWhereId($nId, $nUserId) {
        try {
            return $this->read("*", "_id = :id AND user_id = :user", [":id" => $nId, ":user" => $nUserId], "fetch", PDO::FETCH_CLASS, "ModelData\\Annonce");
        } catch (Exception $e) {
            throw $e;
        }
    }

}
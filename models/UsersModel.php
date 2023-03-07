<?php

class UsersModel extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("users");
    }

    public function pseudoExists($sPseudo) {
        try {
            return !empty($this->read("1", "pseudo = :pseudo", [":pseudo" => $sPseudo], "fetch"));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function emailExists($sEmail) {
        try {
            return !empty($this->read("1", "email = :email", [":email" => $sEmail], "fetch"));
        } catch (Exception $e) {
            throw $e;
        }
    }

    // public function columnContain($sColumn, $sData) {
    //     try {
    //         return !empty($this->read("1", $sColumn . " = :data", [":data" => $sData], "fetch"));
    //     } catch (Exception $e) {
    //         throw $e;
    //     }
    // }

    public function getFromId($nUserId) {
        try {
            return $this->read("*", "_id = :id", [":id" => $nUserId], "fetch", PDO::FETCH_CLASS, "ModelData\User");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPseudoFromId($nUserId) {
        try {
            return $this->read("pseudo", "_id = :id", [":id" => $nUserId], "fetch", PDO::FETCH_CLASS, "ModelData\User");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getFromPseudoOrEmail($sData) {
        try {
            return $this->read("*", "pseudo = :data OR email = :data", [":data" => $sData], "fetch", PDO::FETCH_CLASS, "ModelData\User");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getInfosFromId($nId) {
        try {
            return $this->read("_id,pseudo,email,phone,bio", "_id = :id", [":id" => $nId], "fetch", PDO::FETCH_CLASS, "ModelData\User");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function create($aValues) {

        $aColumns = ["pseudo", "firstname", "lastname", "email", "phone", "birthday", "password"];

        if (count($aColumns) === count($aValues)) {

            $sColumns = "`" . join("`,`", $aColumns) . "`";
            $sValues = ":" . join(",:", $aColumns);
            $aBindValues = [];

            foreach ($aColumns as $nIndex => $sColumn) {
                $aBindValues += [":" . $sColumn => $aValues[$nIndex]];
            }
    
            try {
                $this->insert($sColumns, $sValues, $aBindValues);
            } catch (Exception $e) {
                throw $e;
            }

        } else {
            throw new ModelException(__METHOD__ . " le nombre de colonnes ne correspond pas au nombre de valeurs");
        }

    }

    public function updateConnexionDate($nId) {
        try {
            $this->update("connexion_date", ":date", "_id = :id", [":date" => date("Y-m-d H:i:s", time()), ":id" => $nId]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function changeEmail($sEmail, $nUserId) {
        try {
            $this->update("email", ":email", "_id = :id", [":email" => $sEmail, ":id" => $nUserId]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function changePassword($sPassword, $nUserId) {
        try {
            $this->update("password", ":password", "_id = :id", [":password" => $sPassword, ":id" => $nUserId]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function removeUser($nUserId) {
        try {
            return $this->delete("_id = :id", [":id" => $nUserId]);
        } catch (Exception $e) {
            throw $e;
        }
    }

}
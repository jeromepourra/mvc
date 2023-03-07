<?php

class Model {

    private $sHost;
    private $nPort;
    private $sName;
    private $sUserName;
    private $sUserPassword;
    private $sCharset = "utf8";

    private const HOST_LOCAL = "localhost";
    private const PORT_LOCAL = 3306;
    private const NAME_LOCAL = "mvc";
    private const USERNAME_LOCAL = "root";
    private const USERPASSWORD_LOCAL = "";

    private const HOST_ONLINE = "";
    private const PORT_ONLINE = -1;
    private const NAME_ONLINE = "";
    private const USERNAME_ONLINE = "";
    private const USERPASSWORD_ONLINE = "";

    private $db;
    private $table;

    public function __construct() {
        $this->connect();
    }

    protected function connect() {

        $this->setParams();
        $sDsn = "mysql:host=" . $this->sHost . ";port=" . $this->nPort . ";dbname=" . $this->sName . ";charset=" . $this->sCharset;

        try {
            $this->db = new PDO($sDsn, $this->sUserName, $this->sUserPassword);
        } catch (PDOException $e) {
            // throw $e;
        }

    }

    private function setParams() {
        if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1" || $_SERVER["REMOTE_ADDR"] == "::1") {
            $this->setLocal();
        } else {
            $this->setOnline();
        }
    }

    private function setOnline() {
        $this->sHost = Model::HOST_ONLINE;
        $this->nPort = Model::PORT_ONLINE;
        $this->sName = Model::NAME_ONLINE;
        $this->sUserName = Model::USERNAME_ONLINE;
        $this->sUserPassword = Model::USERPASSWORD_ONLINE;
    }

    private function setLocal() {
        $this->sHost = Model::HOST_LOCAL;
        $this->nPort = Model::PORT_LOCAL;
        $this->sName = Model::NAME_LOCAL;
        $this->sUserName = Model::USERNAME_LOCAL;
        $this->sUserPassword = Model::USERPASSWORD_LOCAL;
    }

    private function isPdo() {
        return $this->db instanceof PDO;
    }

    private function tableExists($table) {
        if ($this->isPdo()) {
            try {
                $oStatement = $this->db->query("SELECT 1 FROM {$table}");
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
        return false;
    }

    protected function setTable($table) {
        if ($this->tableExists($table)) {
            $this->table = $table;
            return true;
        }
        return false;
    }

    private function verify($bCheckTable = true) {
        if (!$this->isPdo()) {
            throw new ModelException("La connexion à la base de donnée a échoué");
        }
        if ($bCheckTable && $this->table === null) {
            throw new ModelException("Aucune table n'a été définie");
        }
    }

    private function setFetchMode(PDOStatement &$oStatement, $nFetchMode, $sFetchClass) {
        if ($sFetchClass !== null) {
            $oStatement->setFetchMode($nFetchMode, $sFetchClass);
        } else {
            $oStatement->setFetchMode($nFetchMode);
        }
    }

    protected function customQuery($sQuery, $aBindParams = [], $sFetchFunc = "fetchAll", $nFetchMode = PDO::FETCH_ASSOC, $sFetchClass = null) {

        try {

            $this->verify(false);

            $oStatement = $this->db->prepare($sQuery);
            $this->setFetchMode($oStatement, $nFetchMode, $sFetchClass);

            if (!empty($aBindParams)) {
                foreach ($aBindParams as $key => $val) {
                    $oStatement->bindValue($key, $val);
                }
            }

            $bExec = $oStatement->execute();
            if ($bExec) {
                return $oStatement->$sFetchFunc();
            } else {
                throw new ModelException("La requête SQL n'a pas pu etre exécutée");
            }

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function bigReader ($sSelector = "*", $sCondition = null, $sOrder = null, $aBindParams = [], $sFetchFunc = "fetchAll", $nFetchMode = PDO::FETCH_ASSOC, $sFetchClass = null) {

        try {

            $this->verify();

            $sQuery = "SELECT " . $sSelector . " FROM `" . $this->table . "`";

            if ($sCondition !== null && !empty($sCondition)) {
                $sQuery = $sQuery . " WHERE " . $sCondition;
            }

            if ($sOrder !== null && !empty($sOrder)) {
                $sQuery = $sQuery . " ORDER BY " . $sOrder;
            }

            $oStatement = $this->db->prepare($sQuery);
            $this->setFetchMode($oStatement, $nFetchMode, $sFetchClass);

            if (!empty($aBindParams)) {
                foreach ($aBindParams as $key => $val) {
                    $oStatement->bindValue($key, $val);
                }
            }

            $bExec = $oStatement->execute();
            if ($bExec) {
                return $oStatement->$sFetchFunc();
            } else {
                throw new ModelException("La requête SQL n'a pas pu etre exécutée");
            }
            
        } catch (PDOException $e) {
            throw $e;
        }

    }

    // protected function read($sSelector = "*", $sCondition = null, $aBindParams = [], $sFetchFunc = "fetchAll", $nFetchMode = PDO::FETCH_ASSOC, $sFetchClass = null) {

    //     try {

    //         $this->verify();

    //         if ($sCondition === null || empty($sCondition)) {
    //             $oStatement = $this->db->prepare("SELECT {$sSelector} FROM `{$this->table}`");
    //         } else {
    //             $oStatement = $this->db->prepare("SELECT {$sSelector} FROM `{$this->table}` WHERE {$sCondition}");
    //         }

    //         $this->setFetchMode($oStatement, $nFetchMode, $sFetchClass);

    //         if (!empty($aBindParams)) {
    //             foreach ($aBindParams as $key => $val) {
    //                 $oStatement->bindValue($key, $val);
    //             }
    //         }

    //         $bExec = $oStatement->execute();
    //         if ($bExec) {
    //             return $oStatement->$sFetchFunc();
    //         } else {
    //             throw new ModelException("La requête SQL n'a pas pu etre exécutée");
    //         }
            
    //     } catch (PDOException $e) {
    //         throw $e;
    //     }

    // }

    protected function read($sSelector = "*", $sCondition = null, $aBindParams = [], $sFetchFunc = "fetchAll", $nFetchMode = PDO::FETCH_ASSOC, $sFetchClass = null) {
        return $this->bigReader($sSelector, $sCondition, null, $aBindParams, $sFetchFunc, $nFetchMode, $sFetchClass);
    }

    protected function readSort($sSelector = "*", $sCondition = null, $sOrder = null, $aBindParams = [], $sFetchFunc = "fetchAll", $nFetchMode = PDO::FETCH_ASSOC, $sFetchClass = null) {
        return $this->bigReader($sSelector, $sCondition, $sOrder, $aBindParams, $sFetchFunc, $nFetchMode, $sFetchClass);
    }

    protected function insert($sColumns, $sValues, $aBindValues = [], $bReturn = false, $sFetchClass = null) {

        if (!empty($sColumns) && !empty($sValues)) {

            try {

                $this->verify();

                $oStatement = $this->db->prepare("INSERT INTO `{$this->table}` ({$sColumns}) VALUES ({$sValues})");

                if (!empty($aBindValues)) {
                    foreach ($aBindValues as $key => $val) {
                        $oStatement->bindValue($key, $val);
                    }
                }

                $bExec = $oStatement->execute();
                if ($bExec) {
                    if ($bReturn) {
                        return $this->insertReturn($sFetchClass);
                    }
                } else {
                    throw new ModelException("La requête SQL n'a pas pu etre exécutée");
                }

            } catch (Exception $e) {
                throw $e;
            }

        } else {
            throw new ModelException("Des valeurs ne sont pas renseignées");
        }

    }

    private function insertReturn($sFetchClass) {
        $nId = $this->db->lastInsertId();
        if ($nId != false) {
            if (empty($sFetchClass)) {
                return $this->insertReturnDefault($nId);
            } else {
                return $this->insertReturnObject($nId, $sFetchClass);
            }
        } else {
            throw new ModelException("Impossible de récupérer l'id de la dernière insertion en base de donnée");
        }
    }

    private function insertReturnDefault($nId) {
        return $this->read("*", "_id = :id", [":id" => $nId], "fetch");
    }

    private function insertReturnObject($nId, $sFetchClass) {
        return $this->read("*", "_id = :id", [":id" => $nId], "fetch", PDO::FETCH_CLASS, $sFetchClass);
    }

    protected function update($sColumn, $sValue, $sCondition, $aBindParams = []) {

        if (!empty($sColumn) && !empty($sValue) && !empty($sCondition)) {

            try {

                $this->verify();

                $oStatement = $this->db->prepare("UPDATE `{$this->table}` SET `{$sColumn}` = {$sValue} WHERE {$sCondition}");

                if (!empty($aBindParams)) {
                    foreach ($aBindParams as $key => $val) {
                        $oStatement->bindValue($key, $val);
                    }
                }

                $bExec = $oStatement->execute();
                if (!$bExec) {
                    throw new ModelException("La requête SQL n'a pas pu etre exécutée");
                }

            } catch (Exception $e) {
                throw $e;
            }

        } else {
            throw new ModelException("Des valeurs ne sont pas renseignées");
        }

    }

    protected function updates($sUpdates, $sCondition, $aBindParams = []) {

        if (!empty($sUpdates) && !empty($sCondition)) {

            try {

                $this->verify();

                $oStatement = $this->db->prepare("UPDATE `{$this->table}` SET {$sUpdates} WHERE {$sCondition}");

                if (!empty($aBindParams)) {
                    foreach ($aBindParams as $key => $val) {
                        $oStatement->bindValue($key, $val);
                    }
                }

                $bExec = $oStatement->execute();
                if (!$bExec) {
                    throw new ModelException("La requête SQL n'a pas pu etre exécutée");
                }

            } catch (Exception $e) {
                throw $e;
            }

        } else {
            throw new ModelException("Des valeurs ne sont pas renseignées");
        }

    }

    protected function delete($sCondition, $aBindParams = []) {

        if (!empty($sCondition)) {

            try {

                $this->verify();

                $oStatement = $this->db->prepare("DELETE FROM `{$this->table}` WHERE {$sCondition}");

                if (!empty($aBindParams)) {
                    foreach ($aBindParams as $key => $val) {
                        $oStatement->bindValue($key, $val);
                    }
                }

                $bExec = $oStatement->execute();
                if (!$bExec) {
                    throw new ModelException("La requête SQL n'a pas pu etre exécutée");
                }

            } catch (Exception $e) {
                throw $e;
            }

        } else {
            throw new ModelException("Des valeurs ne sont pas renseignées");
        }

    }
    
}

?>
<?php

class CategoriesModel extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("categories");
    }

    public function exist($nId) {
        try {
            return !empty($this->read("1", "_id = :id", [":id" => $nId], "fetch"));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAll() {
        try {
            return $this->readSort("*", null, "name", [], "fetchAll", PDO::FETCH_CLASS, "ModelData\Category");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getFromId($nId) {
        try {
            return $this->read("*", "_id = :id", [":id" => $nId], "fetch", PDO::FETCH_CLASS, "ModelData\Category");
        } catch (Exception $e) {
            throw $e;
        }
    }

}

?>
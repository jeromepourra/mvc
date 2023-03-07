<?php

class BanishmentsModel extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("banishments");
    }

    public function getFromUser($nUserId) {
        try {
            return $this->read("date,reason", "user_id = :user", [":user" => $nUserId], "fetch", PDO::FETCH_CLASS, "ModelData\Banishment");
        } catch (Exception $e) {
            throw $e;
        }
    }

}
<?php

require ROOT . "misc/form/FormChecker.php";

class FormLoginChecker extends FormChecker {

    public function check(UsersModel $oUsersModel, BanishmentsModel $oBanishmentsModel) {

        $this->checkSends();
        if (!$this->getSuccess()) {
            return $this->getResponse();
        }

        $this->checkFulls();

        try {

            $oUser = $oUsersModel->getFromPseudoOrEmail($_POST["user"]);

            if ($oUser instanceof ModelData\User) {

                if (!$this->checkPassword($_POST["password"], $oUser->password)) {
                    if (Constants::DEV_MODE) {
                        $this->pushError("password", "Mot de passe incorrect");
                    } else {
                        $this->pushError("password", "Pseudo ou adresse email ou mot de passe incorrect");
                    }
                }

            } else {
                if (Constants::DEV_MODE) {
                    $this->pushError("user", "Pseudo ou adresse email incorrect");
                } else {
                    $this->pushError("password", "Pseudo ou adresse email ou mot de passe incorrect");
                }
            }

        } catch (Exception $e) {
            $this->pushError("global", $e->getMessage());
        }

        if ($this->getSuccess()) {

            if ($oUser->banned) {
                
                require ROOT . "views/helpers/DateHelper.php";
                $oBanishment = $oBanishmentsModel->getFromUser($oUser->_id);

                if ($oBanishment instanceof ModelData\Banishment) {
                    $this->pushError("password", "Votre compte à été banni le " . (new DateHelper())->getFull($oBanishment->date));
                    $this->pushError("password", "Raisons : " . $oBanishment->reason);
                } else {
                    $this->pushError("password", "Votre compte à été banni");
                }

            }

            try {
                $oUsersModel->updateConnexionDate($oUser->_id);
                $this->pushData("User", $oUser);
            } catch (Exception $e) {
                $this->pushError("global", $e->getMessage());
            }

        }

        return $this->getResponse();

    }

}

?>
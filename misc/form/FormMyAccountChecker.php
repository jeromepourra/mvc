<?php

require ROOT . "misc/form/FormChecker.php";

class FormMyAccountChecker extends FormChecker {

    public function checkChangeEmail(UsersModel $oModel) {

        $this->checkSends();
        if (!$this->getSuccess()) {
            return $this->getResponse();
        }

        $this->checkFulls();
        $this->checkContent("form-email-email", Constants::USER_EMAIL_MIN_LEN, Constants::USER_EMAIL_MAX_LEN);
        $this->checkEmail("form-email-email");

        try {

            $oUser = Session::getObject("account", "User");

            if ($oUser instanceof ModelData\User) {

                if (!$this->checkEmailDiff($_POST["form-email-email"], $oUser->email)) {
                    $this->pushError("form-email-email", "L'adresse email doit être différente de l'actuelle");
                }

                if (!$this->checkPassword($_POST["form-email-password"], $oUser->password)) {
                    $this->pushError("form-email-password", "Mot de passe incorrect");
                }

            } else {
                $this->pushError("form-email-global", "Une erreur s'est produite l'utilisateur est inconnu");
            }

        } catch (Exception $e) {
            $this->pushError("form-email-global", $e->getMessage());
        }

        if ($this->getSuccess()) {

            try {
                $oModel->changeEmail($_POST["form-email-email"], $oUser->_id);
                Session::updateUserInfos("email", $_POST["form-email-email"]);
                $this->pushSuccess("form-email-global", "Votre nouvelle adresse email : " . $_POST["form-email-email"]);
            } catch (Exception $e) {
                $this->pushError("form-email-global", $e->getMessage());
            }

        }

        return $this->getResponse();

    }

    public function checkChangePassword(UsersModel $oModel) {

        $this->checkSends();
        if (!$this->getSuccess()) {
            return $this->getResponse();
        }

        $this->checkFulls();
        $this->checkContent("form-password-new", Constants::USER_PASSWORD_MIN_LEN, Constants::USER_PASSWORD_MAX_LEN, Constants::REGEX_PASSWORD, Constants::REGEX_PASSWORD_WRITABLE);
        $this->checkSamePasswords("form-password-new", "form-password-new-conf");

        try {

            $oUser = Session::getObject("account", "User");

            if ($oUser instanceof ModelData\User) {

                if (!$this->checkPassword($_POST["form-password-old"], $oUser->password)) {
                    $this->pushError("form-password-old", "Mot de passe incorrect");
                }

            } else {
                $this->pushError("form-password-global", "Une erreur s'est produite l'utilisateur est inconnu");
            }

        } catch (Exception $e) {
            $this->pushError("form-password-global", $e->getMessage());
        }

        if ($this->getSuccess()) {

            $sPwdHash = password_hash($_POST["form-password-new"], PASSWORD_BCRYPT, Constants::PASSWORD_HASH_OPTIONS);

            try {
                $oModel->changePassword($sPwdHash, $oUser->_id);
                Session::updateUserInfos("password", $sPwdHash);
                $this->pushSuccess("form-password-global", "Votre mot de passe à été changé");
            } catch (Exception $e) {
                $this->pushError("form-password-global", $e->getMessage());
            }

        }

        return $this->getResponse();

    }

    public function checkRemoveAccount(UsersModel $oModel) {

        $this->checkSends();
        if (!$this->getSuccess()) {
            return $this->getResponse();
        }

        $this->checkFulls();

        try {

            $oUser = Session::getObject("account", "User");

            if ($oUser instanceof ModelData\User) {

                if (!$this->checkPassword($_POST["form-remove-password"], $oUser->password)) {
                    $this->pushError("form-remove-password", "Mot de passe incorrect");
                }

            } else {
                $this->pushError("form-remove-global", "Une erreur s'est produite l'utilisateur est inconnu");
            }

        } catch (Exception $e) {
            $this->pushError("form-remove-global", $e->getMessage());
        }

        if ($this->getSuccess()) {

            try {
                $oModel->removeUser($oUser->_id);
                Session::resetAccount();
                $this->pushSuccess("form-remove-global", "Votre compte a été supprimé !");
            } catch (Exception $e) {
                $this->pushError("form-remove-global", $e->getMessage());
            }

        }

        return $this->getResponse();

    }

}

?>
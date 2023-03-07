<?php

require ROOT . "misc/form/FormChecker.php";

class FormRegisterChecker extends FormChecker {

    public function check(UsersModel $oUsersModel) {

        $this->checkSends();
        if (!$this->getSuccess()) {
            return $this->getResponse();
        }

        $this->checkFulls();
        $this->checkContent("pseudo", Constants::USER_PSEUDO_MIN_LEN, Constants::USER_PSEUDO_MAX_LEN, Constants::REGEX_ALPHA_NUM_TIRETS_UNDERSCORE, Constants::REGEX_ALPHA_NUM_TIRETS_UNDERSCORE_WRITABLE);
        $this->checkContent("firstname", Constants::USER_FIRST_NAME_MIN_LEN, Constants::USER_FIRST_NAME_MAX_LEN, Constants::REGEX_ALPHA_ACCENT_TIRET, Constants::REGEX_ALPHA_ACCENT_TIRET_WRITABLE);
        $this->checkContent("lastname", Constants::USER_LAST_NAME_MIN_LEN, Constants::USER_LAST_NAME_MAX_LEN, Constants::REGEX_ALPHA_ACCENT_TIRET, Constants::REGEX_ALPHA_ACCENT_TIRET_WRITABLE);
        $this->checkContent("email", Constants::USER_EMAIL_MIN_LEN, Constants::USER_EMAIL_MAX_LEN);
        $this->checkEmail("email");
        $this->checkPhone("phone");
        $this->checkBirthday("birthday");
        $this->checkContent("password", Constants::USER_PASSWORD_MIN_LEN, Constants::USER_PASSWORD_MAX_LEN, Constants::REGEX_PASSWORD, Constants::REGEX_PASSWORD_WRITABLE);
        $this->checkSamePasswords("password", "passwordconf");
        $this->checkCheckbox("dataconfmajor");
        $this->checkCheckbox("dataconfinfos");

        try {
            $bPseudoExists = $oUsersModel->pseudoExists($_POST["pseudo"]);
            if ($bPseudoExists) {
                $this->pushError("pseudo", "Ce pseudo est déjà utilisé");
            } 
            $bEmailExists = $oUsersModel->emailExists($_POST["email"]);
            if ($bEmailExists) {
                $this->pushError("email", "Cette adresse email est déjà utilisée");
            } 
        } catch (Exception $e) {
            $this->pushError("global", $e->getMessage());
        }

        if ($this->getSuccess()) {

            $sPwdHash = password_hash($_POST["password"], PASSWORD_BCRYPT, Constants::PASSWORD_HASH_OPTIONS);
            $aValues = [$_POST["pseudo"], $_POST["firstname"], $_POST["lastname"], $_POST["email"], $_POST["phone"], $_POST["birthday"], $sPwdHash];
    
            try {
                $oUsersModel->create($aValues);
                $this->pushData("pseudo", $_POST["pseudo"]);
            } catch (Exception $e) {
                $this->pushError("global", $e->getMessage());
            }

        }

        return $this->getResponse();

    }

}

?>
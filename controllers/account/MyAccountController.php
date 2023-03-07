<?php

class MyAccountController extends Controller {
    
    public function __construct($aActions) {

        $this->initialize($aActions, ["GET", "POST"], 0, 0);

        if (Session::get("account", "connected") === false) {
            throw new ControllerException("Vous n'êtes pas connecté", 403);
        }

        try {
            $oUsersModel = $this->loadModel("UsersModel");
            $this->useModelData("User");
        } catch (Exception $e) {
            throw $e;
        }

        $oUser = Session::getObject("account", "User");

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->submit($oUsersModel);
        }

        try {

            $this->setData([
                "controller" => $this->getController(),
                "head-title" => Constants::WEB_SITE_NAME . " - Mon compte",
                "body-title" => "Bonjour " . $oUser->pseudo
            ]);
            $this->setView(["account", "my-account"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function submit(UsersModel $oUsersModel) {

        require ROOT . "misc/form/FormMyAccountChecker.php";

        if (isset($_POST["form-email"])) {

            $oChecker = new FormMyAccountChecker([
                "form-email-email" => true,
                "form-email-password" => true
            ]);
            $oChecker->checkChangeEmail($oUsersModel);

        } elseif (isset($_POST["form-password"])) {

            $oChecker = new FormMyAccountChecker([
                "form-password-old" => true,
                "form-password-new" => true,
                "form-password-new-conf" => true
            ]);
            $oChecker->checkChangePassword($oUsersModel);

        } elseif (isset($_POST["form-remove"])) {

            $oChecker = new FormMyAccountChecker([
                "form-remove-password" => true
            ]);
            $oChecker->checkRemoveAccount($oUsersModel);

        }

        if (isset($oChecker)) {
            $this->setData([
                "response" => $oChecker->getResponse()
            ]);
        }


    }

}
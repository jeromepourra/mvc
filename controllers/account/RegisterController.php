<?php

class RegisterController extends Controller {

    public function __construct($aActions) {

        $this->initialize($aActions, ["GET", "POST"], 0, 0);
        
        if (Session::get("account", "connected") === true) {
            throw new ControllerException("Vous êtes déjà connecté", 403);
        }

        try {
            $oUsersModel = $this->loadModel("UsersModel");
        } catch (Exception $e) {
            throw $e;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->submit($oUsersModel);
        }

        try {

            $this->setData([
                "controller" => $this->getController(),
                "head-title" => Constants::WEB_SITE_NAME . " - Inscription",
                "body-title" => "Inscription",
                "url-params" => [
                    "referrer" => $this->getReferrer()
                ]
            ]);
            $this->setView(["account", "register"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function submit(UsersModel $oUsersModel) {

        require ROOT . "misc/form/FormRegisterChecker.php";

        $oChecker = new FormRegisterChecker([
            "pseudo" => true,
            "firstname" => true,
            "lastname" => true,
            "email" => true,
            "phone" => true,
            "birthday" => true,
            "password" => true,
            "passwordconf" => true,
            "dataconfmajor" => true,
            "dataconfinfos" => true
        ]);
        $oChecker->check($oUsersModel);

        if ($oChecker->getSuccess()) {

            $sPseudo = $oChecker->getData("pseudo");
            Session::userRegistration($sPseudo);
            Router::redirect("confirmation-d-inscription");

        } else {

            $this->setData([
                "response" => $oChecker->getResponse()
            ]);

        }

    }

}
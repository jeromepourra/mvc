<?php

class LoginController extends Controller {

    public function __construct($aActions) {

        $this->initialize($aActions, ["GET", "POST"], 0, 0);

        if (Session::get("account", "connected") === true) {
            throw new ControllerException("Vous êtes déjà connecté", 403);
        }

        try {
            $oUsersModel = $this->loadModel("UsersModel");
            $oBanishmentsModel = $this->loadModel("BanishmentsModel");
            $this->useModelData("User");
            $this->useModelData("Banishment");
        } catch (Exception $e) {
            throw $e;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->submit($oUsersModel, $oBanishmentsModel);
        }
        
        try {

            $this->setData([
                "controller" => $this->getController(),
                "head-title" => Constants::WEB_SITE_NAME . " - Connexion",
                "body-title" => "Connexion",
                "url-params" => [
                    "referrer" => $this->getReferrer()
                ]
            ]);
            $this->setView(["account", "login"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function submit(UsersModel $oUsersModel, BanishmentsModel $oBanishmentsModel) {

        require ROOT . "misc/form/FormLoginChecker.php";

        $oChecker = new FormLoginChecker([
            "user" => true,
            "password" => true
        ]);
        $oChecker->check($oUsersModel, $oBanishmentsModel);

        if ($oChecker->getSuccess()) {

            $oUser = $oChecker->getData("User");
            Session::userConnect($oUser);
            $sReferrer = $this->getReferrer();
            if (!empty($sReferrer)) {
                Router::redirect($sReferrer);
            } else {
                Router::redirect("accueil");
            }

        } else {

            $this->setData([
                "response" => $oChecker->getResponse()
            ]);

        }

    }

}
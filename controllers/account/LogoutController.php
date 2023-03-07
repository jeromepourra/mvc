<?php

class LogoutController extends Controller {

    public function __construct($aActions = null) {

        $this->initialize($aActions, ["GET"], 0, 0);

        if (Session::get("account", "connected") === false) {
            throw new ControllerException("Vous n'êtes pas connecté", 403);
        }

        try {
            $this->useModelData("User");
        } catch (Exception $e) {
            throw $e;
        }

        $oUser = Session::getObject("account", "User");
        Session::userDisconnect();

        try {

            $this->setData([
                "controller" => $this->getController(),
                "head-title" => Constants::WEB_SITE_NAME . " - Déconnexion",
                "body-title" => "Déconnexion",
                "pseudo" => $oUser->pseudo
            ]);
            $this->setView(["account", "logout"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

}
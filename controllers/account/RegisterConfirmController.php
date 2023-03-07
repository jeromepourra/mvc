<?php

class RegisterConfirmController extends Controller {

    public function __construct($aActions) {

        $this->initialize($aActions, ["GET"]);

        if (Session::get("___tmp___", "registration") === true) {

            $this->setData([
                "controller" => $this->getController(),
                "head-title" => Constants::WEB_SITE_NAME . " - Confirmation d'inscription",
                "body-title" => "Confirmation d'inscription",
                "register-pseudo" => Session::get("___tmp___", "registration-pseudo"),
                "register-email" => Session::get("___tmp___", "registration-email")
            ]);

            Session::resetTemporary();

            $this->setView(["account", "register-confirm"]);
            $this->setLayout();
            $this->render();

        } else {
            throw new ControllerException("L'accès vous a été refusé", 403);
        }

    }

}
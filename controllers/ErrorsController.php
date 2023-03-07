<?php

class ErrorsController extends Controller {

    public function __construct($aActions, $sMessage = "") {

        $this->initialize($aActions, ["GET"], 1, 1);
        
        switch ($aActions[0]) {
            case null:
                $this->on404($sMessage);
                break;
            case "403":
                $this->on403($sMessage);
                break;
            case "404":
                $this->on404($sMessage);
                break;
            default:
                throw new ControllerException($this->getUnknownActionMessage($aActions[0]));
        }

    }

    private function on403($sMessage) {

        try {

            $this->setData([
                "head-title" => Constants::WEB_SITE_NAME . " - Erreur 403",
                "body-title" => "Erreur 403. Une autorisation est requise.",
                "message" => $sMessage
            ]);
            $this->setView(["errors", "403"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function on404($sMessage) {

        try {

            $this->setData([
                "head-title" => Constants::WEB_SITE_NAME . " - Erreur 404",
                "body-title" => "Erreur 404. Fichier ou ressource introuvable.",
                "message" => $sMessage
            ]);
            $this->setView(["errors", "404"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

}
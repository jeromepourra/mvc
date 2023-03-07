<?php

class RemoveAnnonceController extends Controller {

    public function __construct($aActions) {

        $this->initialize($aActions, ["GET"], 1, 1);

        if (Session::get("account", "connected") === false) {
            throw new ControllerException("Vous n'êtes pas connecté", 403);
        }

        try {
            $oAnnoncesModel = $this->loadModel("AnnoncesModel");
            $oAnnoncesPicturesModel = $this->loadModel("AnnoncesPicturesModel");
            $this->useModelData("User");
            $this->useModelData("Annonce");
            $this->useModelData("AnnoncePicture");
        } catch (Exception $e) {
            throw $e;
        }

        try {

            $oUser = Session::getObject("account", "User");
            $nAnnonceId = intval($aActions[0]);
            $oAnnonce = $oAnnoncesModel->getFromUserWhereId($nAnnonceId, $oUser->_id);

            if ($oAnnonce instanceof ModelData\Annonce) {
                $oAnnoncesModel->remove($nAnnonceId);
                $oAnnoncesPicturesModel->removeAllFromAnnonce($nAnnonceId);
                Router::redirect("mes-annonces");
            } else {
                throw new ControllerException("Vous n'êtes pas autorisé à retirer cette annonce", 403);
            }

        } catch (Exception $e) {
            throw $e;
        }

    }

}
<?php

class ViewAnnonce extends Controller {

    public function __construct($aActions) {

        $this->initialize($aActions, ["GET"], 1, 1);

        try {
            $oUsersModel = $this->loadModel("UsersModel");
            $oCategoriesModel = $this->loadModel("CategoriesModel");
            $oAnnoncesModel = $this->loadModel("AnnoncesModel");
            $oAnnoncesPicturesModel = $this->loadModel("AnnoncesPicturesModel");
            $this->useModelData("User");
            $this->useModelData("Category");
            $this->useModelData("Annonce");
            $this->useModelData("AnnoncePicture");
        } catch (Exception $e) {
            throw $e;
        }

        $nAnnonceId = intval($aActions[0]);

        try {

            $this->setAnnonceData($oAnnonce, $nAnnonceId, $oUsersModel, $oCategoriesModel, $oAnnoncesModel, $oAnnoncesPicturesModel);
            $this->setData([
                "controller" => $this->getController(),
                "head-title" => Constants::WEB_SITE_NAME . " - " . $oAnnonce->title,
                "body-title" => ""
            ]);
            $this->setView(["annonce", "annonce"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function setAnnonceData(&$oAnnonce, $nAnnonceId, UsersModel $oUsersModel, CategoriesModel $oCategoriesModel, AnnoncesModel $oAnnoncesModel, AnnoncesPicturesModel $oAnnoncesPicturesModel) {

        try {

            $aAnnonceData = null;
            $oAnnonce = $oAnnoncesModel->getFromId($nAnnonceId);

            if ($oAnnonce instanceof ModelData\Annonce) {

                if ($oAnnonce->state === Constants::ANNONCE_STATE_ONLINE) {
                    $oCategory = $oCategoriesModel->getFromId($oAnnonce->category_id);
                    $aPictures = $oAnnoncesPicturesModel->getFromAnnonce($nAnnonceId);
                    $oUser = $oUsersModel->getInfosFromId($oAnnonce->user_id);
                    $aAnnonceData = [
                        "Category" => $oCategory,
                        "Annonce" => $oAnnonce,
                        "Pictures" => $aPictures,
                        "User" => $oUser
                    ];
                    $this->setData($aAnnonceData);
                } else {
                    throw new ControllerException("Cette annonce n'est pas en ligne", 403);
                }


            } else {
                throw new ControllerException("Cette annonce n'existe pas", 404);
            }

        } catch (Exception $e) {
            throw $e;
        }

    }

}
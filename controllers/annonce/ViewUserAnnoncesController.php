<?php

class ViewUserAnnoncesController extends Controller {

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

        $nUserId = intval($aActions[0]);

        try {

            $this->setAnnoncesData($nUserId, $oUsersModel, $oCategoriesModel, $oAnnoncesModel, $oAnnoncesPicturesModel);

            $this->setData([
                "controller" => $this->getController(),
                "head-title" => Constants::WEB_SITE_NAME . " - Mes annonces",
                "body-title" => "Mes annonces"
            ]);
            $this->setView(["annonce", "user-annonces"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function setAnnoncesData($nUserId, UsersModel $oUsersModel, CategoriesModel $oCategoriesModel, AnnoncesModel $oAnnoncesModel, AnnoncesPicturesModel $oAnnoncesPicturesModel) {

        try {

            $oUser = $oUsersModel->getInfosFromId($nUserId);

            if ($oUser instanceof ModelData\User) {

                $oMe = Session::getObject("account", "User");
                $bOwner = $oMe !== null ? $oMe->_id == $oUser->_id : false;

                $aAnnoncesData = [];
                if ($bOwner) {
                    $aAnnonces = $oAnnoncesModel->getFromUser($oUser->_id);
                } else {
                    $aAnnonces = $oAnnoncesModel->getOnlineFromUser($oUser->_id);
                }

                foreach ($aAnnonces as $oAnnonce) {
                    $oPictures = $oAnnoncesPicturesModel->getFromAnnonce($oAnnonce->_id);
                    $oCategory = $oCategoriesModel->getFromId($oAnnonce->category_id);
                    array_push($aAnnoncesData, ["Annonce" => $oAnnonce, "Picture" => $oPictures, "Category" => $oCategory]);
                }
    
                $this->setData([
                    "owner" => $bOwner,
                    "User" => $oUser,
                    "Annonces" => $aAnnoncesData,
                    "Categories" => $oCategoriesModel->getAll()
                ]);

            } else {
                throw new ControllerException("Cet utilisateur n'existe pas", 404);
            }

        } catch (Exception $e) {
            throw $e;
        }

    }

}
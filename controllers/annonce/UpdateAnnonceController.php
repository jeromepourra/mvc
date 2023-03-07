<?php

class UpdateAnnonceController extends Controller {

    public function __construct($aActions) {

        $this->initialize($aActions, ["GET", "POST"], 1, 3);

        if (Session::get("account", "connected") === false) {
            throw new ControllerException("Vous n'êtes pas connecté", 403);
        }

        try {
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
        $bRemoveImg = false;
        $nRemoveId = null;
        if (isset($aActions[1]) && $aActions[1] === "supprimer-image") {
            if (isset($aActions[2])) {
                $bRemoveImg = true;
                $nRemoveId = intval($aActions[2]);
            }
        }

        try {

            $this->setAnnonceData($oAnnonce, $aPictures, $nAnnonceId, $oCategoriesModel, $oAnnoncesModel, $oAnnoncesPicturesModel);

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $this->submit($nAnnonceId, $oCategoriesModel, $oAnnoncesModel, $oAnnoncesPicturesModel);
            } elseif ($bRemoveImg) {
                $nCount = $oAnnoncesPicturesModel->getCountFromAnnonce($nAnnonceId);
                if ($nCount > Constants::ANNONCE_PICTURES_MIN) {
                    $oPicture = $oAnnoncesPicturesModel->getFromId($nRemoveId);
                    unlink(ROOT . Constants::PATH_ANNONCES . str_pad($nAnnonceId, Constants::ANNONCE_PICTURE_DIRECTORY_NAME_LENGTH, "0", STR_PAD_LEFT) . '/' . $nRemoveId . "." . $oPicture->extension);
                    $oAnnoncesPicturesModel->removeFromId($nRemoveId);
                }
                Router::redirect("modifier-annonce/" . $oAnnonce->_id);
            }

            $this->setData([
                "controller" => $this->getController(),
                "head-title" => Constants::WEB_SITE_NAME . " - Modifier l'annonce",
                "body-title" => "Modifier votre annonce"
            ]);
            $this->setView(["annonce", "update-annonce"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function setAnnonceData(&$oAnnonce, &$aPictures, $nAnnonceId, CategoriesModel $oCategoriesModel, AnnoncesModel $oAnnoncesModel, AnnoncesPicturesModel $oAnnoncesPicturesModel) {

        try {

            $aAnnonceData = null;
            $oUser = Session::getObject("account", "User");
            $oAnnonce = $oAnnoncesModel->getFromUserWhereId($nAnnonceId, $oUser->_id);

            if ($oAnnonce instanceof ModelData\Annonce) {
                $aPictures = $oAnnoncesPicturesModel->getFromAnnonce($oAnnonce->_id);
                $aAnnonceData = ["Annonce" => $oAnnonce, "Pictures" => $aPictures];
            } else {
                throw new ControllerException("Vous n'êtes pas autorisé à modifier cette annonce", 403);
            }

            $this->setData([
                "Annonces" => $aAnnonceData,
                "Categories" => $oCategoriesModel->getAll()
            ]);

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function submit($nAnnonceId, CategoriesModel $oCategoriesModel, AnnoncesModel $oAnnoncesModel, AnnoncesPicturesModel $oAnnoncesPicturesModel) {

        require ROOT . "misc/form/FormUpdateAnnonceChecker.php";
        
        $oChecker = new FormUpdateAnnonceChecker([
            "category" => true,
            "title" => true,
            "price" => true,
            "description" => true
        ], [
            "pictures" => false
        ]);
        $oChecker->check($nAnnonceId, $oCategoriesModel, $oAnnoncesModel, $oAnnoncesPicturesModel);

        if ($oChecker->getSuccess()) {
            Router::redirect("annonces-utilisateur/" . (Session::getObject("account", "User"))->_id);
        } else {
            $this->setData([
                "response" => $oChecker->getResponse()
            ]);
        }

    }

}
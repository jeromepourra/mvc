<?php

class ResearchAnnoncesController extends Controller {

    public function __construct($aActions) {

        $this->initialize($aActions, ["GET"]);

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

        try {

            $this->research($aCondition, $aBindParams, $sBodyTitleCategory, $oCategoriesModel);
            $this->setResearchData($oUsersModel, $oCategoriesModel, $oAnnoncesModel, $oAnnoncesPicturesModel, $aCondition, $aBindParams);

            $this->setData([
                "controller" => $this->getController(),
                "head-title" => Constants::WEB_SITE_NAME . " - Mes annonces",
                "body-title" => "Annonces : " . $sBodyTitleCategory
            ]);
            $this->setView(["annonce", "research"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function research(&$aCondition, &$aBindParams, &$sBodyTitleCategory, CategoriesModel $oCategoriesModel) {

        $aCondition = [];
        $aBindParams = [];
        $sBodyTitleCategory = "Toutes categories";
        array_push($aCondition, "state = :state");
        $aBindParams += [":state" => Constants::ANNONCE_STATE_ONLINE];

        if (isset($_GET["category"]) && !empty($_GET["category"])) {
            array_push($aCondition, "category_id = :category");
            $aBindParams += [":category" => $_GET["category"]];
            $oCategory = $oCategoriesModel->getFromId($_GET["category"]);
            if ($oCategory instanceof ModelData\Category) {
                $sBodyTitleCategory = $oCategory->name;
            } else {
                $sBodyTitleCategory = "Catégorie inconnue";
            }
        }

        if (isset($_GET["pricemin"]) && $_GET["pricemin"] !== "") {
            array_push($aCondition, "price >= :pricemin");
            $aBindParams += [":pricemin" => $_GET["pricemin"]];
        }
        
        if (isset($_GET["pricemax"]) && $_GET["pricemax"] !== "") {
            array_push($aCondition, "price <= :pricemax");
            $aBindParams += [":pricemax" => $_GET["pricemax"]];
        }

    }

    private function setResearchData(UsersModel $oUsersModel, CategoriesModel $oCategoriesModel, AnnoncesModel $oAnnoncesModel, AnnoncesPicturesModel $oAnnoncesPicturesModel, $aCondition, $aBindParams) {

        try {

            $aAnnoncesData = [];
            $sCondition = join(" AND ", $aCondition);
            $aAnnonces = $oAnnoncesModel->getFromResearch($sCondition, $aBindParams);

            foreach ($aAnnonces as $oAnnonce) {
                $oPicture = $oAnnoncesPicturesModel->getOneFromAnnonce($oAnnonce->_id);
                $oCategory = $oCategoriesModel->getFromId($oAnnonce->category_id);
                $oUser = $oUsersModel->getInfosFromId($oAnnonce->user_id);
                array_push($aAnnoncesData, ["Annonce" => $oAnnonce, "Picture" => $oPicture, "Category" => $oCategory, "User" => $oUser]);
            }

            $this->setData([
                "Annonces" => $aAnnoncesData
            ]);

        } catch (Exception $e) {
            throw $e;
        }

    }

}
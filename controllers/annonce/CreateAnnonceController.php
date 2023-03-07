<?php

class CreateAnnonceController extends Controller {

    public function __construct($aActions) {

        $this->initialize($aActions, ["GET", "POST"], 0, 0);

        if (Session::get("account", "connected") === false) {
            Router::redirect("connexion", ["referrer" => "deposer-une-annonce"]);
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

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->submit($oCategoriesModel, $oAnnoncesModel, $oAnnoncesPicturesModel);
        }

        try {

            $this->setData([
                "controller" => $this->getController(),
                "head-title" => Constants::WEB_SITE_NAME . " - Déposer une annonce",
                "body-title" => "Déposer une annonce",
                "Categories" => $oCategoriesModel->getAll()
            ]);
            $this->setView(["annonce", "create-annonce"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

    private function submit(CategoriesModel $oCategoriesModel, AnnoncesModel $oAnnoncesModel, AnnoncesPicturesModel $oAnnoncesPicturesModel) {

        require ROOT . "misc/form/FormCreateAnnonceChecker.php";
        
        $oChecker = new FormCreateAnnonceChecker([
            "category" => true,
            "title" => true,
            "price" => true,
            "description" => true
        ], [
            "pictures" => false
        ]);
        $oChecker->check($oCategoriesModel, $oAnnoncesModel, $oAnnoncesPicturesModel);

        if ($oChecker->getSuccess()) {
            Router::redirect("annonces-utilisateur/" . (Session::getObject("account", "User"))->_id);
        } else {
            $this->setData([
                "response" => $oChecker->getResponse()
            ]);
        }

    }

}
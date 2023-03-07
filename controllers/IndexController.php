<?php

class IndexController extends Controller {

    public function __construct($aActions) {

        $this->initialize($aActions, ["GET"]);
        
        try {
            $oCategoriesModel = $this->loadModel("CategoriesModel");
            $this->useModelData("Category");
        } catch (Exception $e) {
            throw $e;
        }

        try {

            $this->setData([
                "controller" => $this->getController(),
                "head-title" => Constants::WEB_SITE_NAME,
                "Categories" => $oCategoriesModel->getAll()
            ]);

            $this->setView(["index", "index"]);
            $this->setLayout();
            $this->render();

        } catch (Exception $e) {
            throw $e;
        }

    }

}
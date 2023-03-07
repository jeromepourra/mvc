<?php

require ROOT . "misc/form/FormChecker.php";
require ROOT . "misc/form/FormFileUploader.php";

class FormCreateAnnonceChecker extends FormChecker {

    public function check(CategoriesModel $oCategoriesModel, AnnoncesModel $oAnnoncesModel, AnnoncesPicturesModel $oAnnoncesPicturesModel) {

        $this->checkSends();
        $this->checkFileSends();
        if (!$this->getSuccess()) {
            return $this->getResponse();
        }

        $this->checkFulls();
        $this->checkContent("title", Constants::ANNONCE_TITLE_MIN_LEN, Constants::ANNONCE_TITLE_MAX_LEN);
        $this->checkNumeric("price", Constants::ANNONCE_PRICE_MIN, Constants::ANNONCE_PRICE_MAX, 2);
        $this->checkContent("description", Constants::ANNONCE_DESCRIPTION_MIN_LEN, Constants::ANNONCE_DESCRIPTION_MAX_LEN);
        $this->checkFiles("pictures", Constants::ANNONCE_PICTURES_MIN, Constants::ANNONCE_PICTURES_MAX, Constants::ANNONCE_PICTURE_MAX_OCTET, Constants::ANNONCE_PICTURES_ACCEPT_EXT, true);

        try {
            if (!$oCategoriesModel->exist($_POST["category"])) {
                $this->pushError("category", "Cette catégorie n'existe pas");
            }
        } catch (Exception $e) {
            throw $e;
        }

        if ($this->getSuccess()) {

            $oUser = Session::getObject("account", "User");

            if ($oUser instanceof ModelData\User) {

                $sDate = date("Y-m-d H:i:s", time());
                $nPrice = str_replace(",", ".", $_POST["price"]);
                $aValues = [$oUser->_id, $_POST["category"], $_POST["title"], $nPrice, $_POST["description"], $sDate];

                try {

                    $oAnnonce = $oAnnoncesModel->create($aValues);

                    if ($oAnnonce instanceof ModelData\Annonce) {
                        $this->uploadFiles("pictures", $oAnnonce->_id, $oAnnoncesPicturesModel);
                        $this->pushData("Annonce", $oAnnonce);
                    } else {
                        $this->pushError("global", "Une erreur s'est produite l'annonce est inconnue");
                    }

                } catch (Exception $e) {
                    $this->pushError("global", $e->getMessage());
                }

            }  else {
                $this->pushError("global", "Une erreur s'est produite l'utilisateur est inconnu");
            }

        }

        return $this->getResponse();

    }

    private function uploadFiles($sKey, $nAnnonceId, AnnoncesPicturesModel $oAnnoncesPicturesModel) {
        
        if (isset($_FILES[$sKey])) {

            $aFiles = $_FILES[$sKey];
            $sAnnonceId = strval($nAnnonceId);

            for ($i=0; $i<count($aFiles["name"]); $i++) {

                $sName = $aFiles["name"][$i];
                $sTmpName = $aFiles["tmp_name"][$i];
                $nError = $aFiles["error"][$i];

                if ($nError === UPLOAD_ERR_OK) {

                    $sExtension = pathinfo($sName)["extension"];

                    try {

                        $aValues = [$sAnnonceId, $sExtension];
                        $oPicture = $oAnnoncesPicturesModel->create($aValues);

                        if ($oPicture instanceof ModelData\AnnoncePicture) {
                            $this->uploadFile($sKey, $oPicture->_id, $sAnnonceId, $sName, $sExtension, $sTmpName);
                        }

                    } catch (Exception $e) {
                        throw $e;
                    }

                }

            }

        } else {
            $this->keyError(__METHOD__, $sKey);
        }

    }

    private function uploadFile($sKey, $sNum, $sAnnonceId, $sName, $sExtension, $sFromName) {

        $sToName = $sNum . "." . $sExtension;
        $sToDir = ROOT . Constants::PATH_ANNONCES . str_pad($sAnnonceId, Constants::ANNONCE_PICTURE_DIRECTORY_NAME_LENGTH, "0", STR_PAD_LEFT) . "/";

        $oUploader = new FormFileUploader($sFromName, $sToDir . $sToName);
        $bUpload = $oUploader->uploadFile();

        if ($bUpload) {
            $oUploader->redimAndCropImage(500, 500);
            return $sToName;
        } else {
            $this->pushError($sKey, "Une erreur s'est produite lors de l'upload du fichier '" . htmlspecialchars($sName) . "'");
        }

    }

}

?>
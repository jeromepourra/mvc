<?php 

class FormFileUploader {

    private $from;
    private $to;

    private $fromPath;
    private $toPath;

    private $fromName;
    private $toName;

    private $fromExtension;
    private $toExtension;

    public function __construct($sFrom, $sTo) {

        $this->from = $sFrom;
        $aPathInfosFrom = pathinfo($sFrom);
        $this->fromPath = $aPathInfosFrom["dirname"];
        $this->fromName = $aPathInfosFrom["filename"];
        $this->fromExtension = $aPathInfosFrom["extension"];

        $this->to = $sTo;
        $aPathInfosTo = pathinfo($sTo);
        $this->toPath = $aPathInfosTo["dirname"];
        $this->toName = $aPathInfosTo["filename"];
        $this->toExtension = $aPathInfosTo["extension"];

    }

    private function createDir($sDir) {
        if (!file_exists($sDir)) {
            mkdir($sDir, 0777, true);
        }
    }

    private function exists($sFile) {
        return file_exists($sFile);
    }

    public function uploadFile() {
        $this->createDir($this->toPath);
        if ($this->exists($this->from)) {
            if (!$this->exists($this->to)) {
                $moved = move_uploaded_file($this->from, $this->to);
                if (!$moved) {
                    $this->printError(__METHOD__, "Une erreur est survenu lors du déplacement du fichier '" . $this->toName . "." . $this->toExtension . "'");
                } else {
                    return true;
                }
            } else {
                $this->printError(__METHOD__, "Un fichier dans le dossier '" . $this->toPath . "' portant le nom '" . $this->toName . "." . $this->toExtension . "' existe déjà");
            }
        } else {
            $this->printError(__METHOD__, "Le fichier temporaire à uploader dans le dossier '" . $this->fromPath . "' portant le nom '" . $this->fromName . "." . $this->fromExtension . "' n'existe pas");
        }
        return false;
    }

    public function redimImage($nWidth, $nHeight) {
        $aImgInfo = getimagesize($this->to);
        $nWidthSrc = $aImgInfo[0];
        $nHeightSrc = $aImgInfo[1];
        $sType = $aImgInfo["mime"];
    }

    public function redimAndCropImage($nWidth, $nHeight) {

        $aImgInfo = getimagesize($this->to);
        $nWidthSrc = $aImgInfo[0];
        $nHeightSrc = $aImgInfo[1];
        $sType = $aImgInfo["mime"];
        
        switch ($sType) {
            case "image/jpeg":
                $sImgCreateFunc = "imagecreatefromjpeg";
                $sImgSaveFunc = "imagejpeg";
                break;
            case "image/png":
                $sImgCreateFunc = "imagecreatefrompng";
                $sImgSaveFunc = "imagepng";
                break;
            case "image/gif":
                $sImgCreateFunc = "imagecreatefromgif";
                $sImgSaveFunc = "imagegif";
                break;
            default: 
                throw new Exception("Le type de l'image est inconnu");
        }
    
        $nRatioThumb = $nWidth / $nHeight;
        $nRatioOrigin = $nWidthSrc / $nHeightSrc;
        
        if ($nRatioOrigin >= $nRatioThumb) {
            $nOriginY = $nHeightSrc; 
            $nOriginX = ceil(($nOriginY * $nWidth) / $nHeight);
            $nCropX = ceil(($nWidthSrc - $nOriginX) / 2);
            $nCropY = 0;
        } else {
            $nOriginX = $nWidthSrc; 
            $nOriginY = ceil(($nOriginX * $nHeight) / $nWidth);
            $nCropY = ceil(($nHeightSrc - $nOriginY) / 2);
            $nCropX = 0;
        }
    
        $oImage_1 = imagecreatetruecolor($nWidth, $nHeight);
        $oImage_2 = $sImgCreateFunc($this->to);
    
        imagecopyresampled($oImage_1, $oImage_2, 0, 0, $nCropX, $nCropY, $nWidth, $nHeight, $nOriginX, $nOriginY);
        $sImgSaveFunc($oImage_1, $this->to);
        imagedestroy($oImage_1);
        imagedestroy($oImage_2);
    
    }

    private function printError($sFrom, $sError) {
        print $sFrom . "()" . " >>> " . $sError;
    }

}
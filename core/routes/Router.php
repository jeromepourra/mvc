<?php

require ROOT . "core/routes/RouterException.php";
require ROOT . "core/routes/RouterDictionnary.php";

require ROOT . "core/controllers/Controller.php";
require ROOT . "core/controllers/ControllerException.php";

require ROOT . "core/models/Model.php";
require ROOT . "core/models/ModelException.php";

class Router extends RouterDictionnary {

    public function run($sUrl, $aActions) {

        try {

            if ($sUrl === null) {
                $mName = $this->getFile("default");
            } else {
                $mName = $this->getFile($sUrl);
            }

            if (is_array($mName)) {
                $sControllerPath = ROOT . "controllers/" . join("/", $mName) . ".php";
                $sControllerClass = $mName[count($mName) - 1];
            } else {
                $sControllerPath = ROOT . "controllers/" . $mName . ".php";
                $sControllerClass = $mName;
            }

            if (file_exists($sControllerPath)) {

                require $sControllerPath;
            
                if (class_exists($sControllerClass)) {

                    try {
                        $oController = new $sControllerClass($aActions);
                    } catch (RouterException $e) {
                        $this->onRouterException($e);
                    } catch (Exception $e) {
                        print $e->getMessage();
                    }
            
                } else {
                    $this->notFound("La class '{$sControllerClass}' n'existe pas.");
                }
            
            } else {
                $this->notFound("Le fichier controller '{$sControllerPath}' n'existe pas.");
            }

        } catch (Exception $e) {
            $this->notFound($e->getMessage());
        }

    }

    private function onRouterException($e) {
        require ROOT . "controllers/ErrorsController.php";
        $sMessage = $e->getMessage();
        $nCode = $e->getCode();
        $oController = new ErrorsController([$nCode], $sMessage);
    }

    private function notFound($sMessage) {
        require ROOT . "controllers/ErrorsController.php";
        $oController = new ErrorsController([404], $sMessage);
    }

    public static function redirect($sTo = "", $aParams = []) {
        $sUrl = WEB_ROOT . $sTo;
        $sParams = http_build_query($aParams, "", "&");
        if (!empty($sParams)) {
            $sUrl = $sUrl . "?" . $sParams;
        }
        header("Location:{$sUrl}");
        die;
    }

}
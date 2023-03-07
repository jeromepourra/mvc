<?php

class Controller {

    private $data = [];
    private $views = [];
    private $layout = null;
    private $controller = null;

    private $referrer = null;

    protected function initialize($aActions, $aMethods = [], $nMinActions = 0, $nMaxActions = 0) {

        $this->controller = [
            "name" => str_replace("Controller", "", get_class($this)),
            "fullName" => get_class($this)
        ];

        $this->setReferrer();
        if (!$this->isMethodAccepted($aMethods)) {
            throw new ControllerException($this->getMethodInvalidMessage($aMethods), 403);
        }
        if (!$this->isActionsAccepted($aActions, $nMinActions, $nMaxActions)) {
            if ($nMaxActions === 0) {
                throw new ControllerException($this->getNoActionAcceptMessage(), 403);
            } else {
                throw new ControllerException($this->getActionNeededMessage(), 403);
            }
        }
    }

    private function isMethodAccepted($aMethods) {
        return empty($aMethods) || in_array($_SERVER["REQUEST_METHOD"], $aMethods);
    }

    private function isActionsAccepted($aActions, $nMinActions, $nMaxActions) {
        $nActions = 0;
        if (is_array($aActions)) {
            $nActions = count($aActions);
        }
        return $nActions >= $nMinActions && $nActions <= $nMaxActions;
    }

    protected function setView($mName) {

        if (is_array($mName)) {
            $sViewPath = ROOT . "views/" . join("/", $mName) . ".php";
            $sViewFile = $mName[count($mName) - 1];
        } else {
            $sViewPath = ROOT . "views/" . $mName . ".php";
            $sViewFile = $mName;
        }

        if (file_exists($sViewPath)) {
            array_push($this->views, [
                "path" => $sViewPath,
                "file" => $sViewFile
            ]);
        } else {
            throw new ControllerException("Le fichier view '{$sViewPath}' n'existe pas.");
        }

    }

    protected function setLayout($sName = "default") {

        if (!empty($sName)) {

            $sLayoutPath = ROOT . "views/layout/" . $sName . ".php";

            if (file_exists($sLayoutPath)) {
                $this->layout = [
                    "path" => $sLayoutPath,
                    "file" => $sName
                ];
            } else {
                throw new ControllerException("Le fichier layout '{$sLayoutPath}' n'existe pas.");
            }

        }

    }

    protected function setData($aData) {

        if (isset($this->data["controller"])) {
            $this->data["controller"] = array_merge($this->data["controller"], $aData);
        } else {
            $this->data["controller"] = $aData;
        }

    }

    protected function getData() {
        return $this->data;
    }

    private function setReferrer() {
        if (isset($_GET["referrer"])) {
            $this->referrer = $_GET["referrer"];
        }
    }

    protected function getReferrer() {
        return $this->referrer;
    }

    protected function getController() {
        return $this->controller;
    }

    protected function getMethodInvalidMessage($aMethods) {
        if (Constants::DEV_MODE) {
            return "Vous essayez d'acceder à cette page avec une méthode '" . $_SERVER["REQUEST_METHOD"] . "' cette page n'accepte que la ou les méthodes '" . join("','", $aMethods) . "'";
        }
        return "Cette page n'existe pas";
    }

    protected function getNoActionAcceptMessage() {
        if (Constants::DEV_MODE) {
            return "Aucune action n'est acceptée pour le controller '" . $this->getController()["name"] . "'";
        }
        return "Cette page n'existe pas";
    }

    protected function getActionNeededMessage() {
        if (Constants::DEV_MODE) {
            return "Le nombre d'actions nécéssaires pour le controller '" . $this->getController()["name"] . "' n'est pas valide";
        }
        return "Cette page n'existe pas";
    }

    protected function getUnknownActionMessage($sAction) {
        return "L'action '{$sAction}' est invalide pour le controller '" . $this->getController()["name"] . "'";
    }
    
    protected function render() {

        if (!empty($this->views)) {

            extract($this->data);
            ob_start();
            foreach ($this->views as $aView) {
                require $aView["path"];
            }
            $view_content = ob_get_clean();

            if ($this->layout === null) {
                echo $view_content;
            } else {
                require $this->layout["path"];
            }

        } else {
            throw new ControllerException("Aucun rendu n'a été défini pour le controller '" . $this->controller["name"] . "'");
        }

    }

    protected function loadModel($mName) {

        if (is_array($mName)) {
            $sModelPath = ROOT . "models/" . join("/", $mName) . ".php";
            $sModelClass = $mName[count($mName) - 1];
        } else {
            $sModelPath = ROOT . "models/" . $mName . ".php";
            $sModelClass = $mName;
        }

        if (file_exists($sModelPath)) {

            require $sModelPath;

            if (class_exists($sModelClass)) {
                return new $sModelClass();
            } else {
                throw new ControllerException("La class '{$sModelClass}' n'existe pas.");
            }

        } else {
            throw new ControllerException("Le fichier model '{$sModelPath}' n'existe pas.");
        }

    }

    protected function useModelData($mName) {

        if (is_array($mName)) {
            $sModelDataPath = ROOT . "models/data/" . join("/", $mName) . ".php";
            $sModelDataClass = "ModelData\\" . $mName[count($mName) - 1];
        } else {
            $sModelDataPath = ROOT . "models/data/" . $mName . ".php";
            $sModelDataClass = "ModelData\\" . $mName;
        }

        if (file_exists($sModelDataPath)) {

            require $sModelDataPath;

            if (!class_exists($sModelDataClass)) {
                throw new ControllerException("La class '{$sModelDataClass}' n'existe pas.");
            }

        } else {
            throw new ControllerException("Le fichier model data '{$sModelDataPath}' n'existe pas.");
        }

    }

}

?>
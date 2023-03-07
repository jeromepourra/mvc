<?php

const ROOT_FILE_NAME = "dispatch.php";
define("WEB_ROOT", str_replace(ROOT_FILE_NAME, "", $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] .  $_SERVER["SCRIPT_NAME"]));
define("ROOT", str_replace(ROOT_FILE_NAME, "", $_SERVER["SCRIPT_FILENAME"]));

require ROOT . "iniset.php";
require ROOT . "misc/Session.php";
require ROOT . "misc/Constants.php";
require ROOT . "core/routes/Router.php";

if (!Session::get("initialized")) {
    Session::initialize();
}

$sParams = $_GET["url"];
$sController = null;
$aActions = null;

if (isset($sParams)) {

    if (!empty($sParams)) {

        // FICHIER DE TEST
        if (Constants::DEV_MODE && $sParams === "dev.php") {
            require ROOT . "dev.php";
            die;
        }

        $aParams = explode("/", $sParams);
        $sController = array_shift($aParams);
        $aActions = $aParams;

    }

}

$oRouter = new Router();
$oRouter->run($sController, $aActions);
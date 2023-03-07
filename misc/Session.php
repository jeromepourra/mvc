<?php

session_start();

class Session {

    public static function initialize($sKey = null) {

        $_SESSION["initialized"] = true;

        if ($sKey == null || $sKey == "account") {
            $_SESSION["account"] = [
                "connected" => false,
                "User" => null
            ];
        }
    
        if ($sKey == null || $sKey == "___tmp___") {
            $_SESSION["___tmp___"] = [];
        }

    }

    public static function reset() {
        session_unset();
        self::initialize();
    }
    
    public static function resetTemporary() {
        self::initialize("___tmp___");
    }
    
    public static function resetAccount() {
        self::initialize("account");
    }

    public static function get(): mixed {

        $aParams = func_get_args();
    
        if (count($aParams) === 0) {
            return $_SESSION;
        } else {
    
            $mData = $_SESSION;
    
            foreach ($aParams as $sParam) {
                if (isset($mData[$sParam])) {
                    $mData = $mData[$sParam];
                    continue;
                }
                return null;
            }
    
            return $mData;
    
        }
    
    }

    public static function getObject(): mixed {

        $aParams = func_get_args();
        $sObject = call_user_func_array(["Session", "get"], $aParams);

        if ($sObject !== null) {
            $oObject = unserialize($sObject);
            return $oObject;
        }

        return null;

    }

    public static function userRegistration($sPseudo) {
        $_SESSION["___tmp___"]["registration"] = true;
        $_SESSION["___tmp___"]["registration-pseudo"] = $sPseudo;
    }

    public static function userConnect($oUser) {
        $_SESSION["account"]["connected"] = true;
        $_SESSION["account"]["User"] = serialize($oUser);
    }

    public static function userDisconnect() {
        $_SESSION["account"]["connected"] = false;
        $_SESSION["account"]["User"] = null;
    }

    public static function updateUserInfos($sKey, $sValue) {

        $oUser = Session::getObject("account", "User");
        $oUser->$sKey = $sValue;
        $_SESSION["account"]["User"] = serialize($oUser);

        // if (isset($_SESSION["account"]["User"][$sKey])) {
        //     $_SESSION["account"]["User"][$sKey] = $sValue;
        // }
    }

}
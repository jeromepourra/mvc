<?php

class FrontHelper {

    private static $controller;

    public static function initialize($controller) {
        self::$controller = $controller;
    }

    public static function get() {

        $aParams = func_get_args();
    
        if (count($aParams) === 0) {
            return self::$controller;
        } else {
            $aParams = func_get_args();
            array_unshift($aParams, self::$controller);
            return call_user_func_array("self::getFrom", $aParams);
        }

    }

    public static function getFrom() {

        $aParams = func_get_args();
        $mFromVar = array_shift($aParams);
    
        if (count($aParams) === 0) {
            return $mFromVar;
        } else {

            $mData = $mFromVar;

            foreach ($aParams as $sParam) {
                switch (gettype($mData)) {
                    case "array":
                        if (isset($mData[$sParam])) {
                            $mData = $mData[$sParam];
                            break;
                        } else {
                            return null;
                        }
                    case "object":
                        if (isset($mData->$sParam)) {
                            $mData = $mData->$sParam;
                            break;
                        } else {
                            return null;
                        }
                    default:
                        return null;
                }
                
            }

            if (is_string($mData)) {
                return htmlspecialchars($mData);
            }

            return $mData;
    
        }

    }

    // public static function get() {

    //     $aParams = func_get_args();
    
    //     if (count($aParams) === 0) {
    //         return self::$controller;
    //     } else {
    
    //         $mData = self::$controller;
    
    //         foreach ($aParams as $sParam) {
                
    //             if (isset($mData[$sParam])) {
    //                 $mData = $mData[$sParam];
    //                 continue;
    //             }
    //             return null;
    //         }

    //         if (is_string($mData)) {
    //             return htmlspecialchars($mData);
    //         }

    //         return $mData;
    
    //     }

    // }

    public static function getUrlParams() {
        $aParams = self::get("url-params");
        if (!empty($aParams)) {
            $sParams = http_build_query($aParams, "", "&");
            if (!empty($sParams)) {
                return "?" . $sParams;
            }
        }
        return "";
    }

    public static function getFormInputValue($sField) {
        if (Constants::REMEMBER_FORM_INPUT_VALUES) {
            $sValue = self::get("response", "values", $sField);
            if (!empty($sValue)) {
                return $sValue;
            }
        }
        return "";
    }

    public static function echoFormMessage($sMessage, $sCssClass) {
        echo '<small class="' . $sCssClass . '">' . htmlspecialchars($sMessage) . '</small>';
    }

    public static function echoFormMessages($sField, $bSuccess = false, $sCssClass = "text-end") {

        // ($bGlobal ? "fs-5 text-center" : "text-end")

        if ($bSuccess) {
            $aMessages = self::get("response", "messages", "success", $sField);
            $sCssClass = "d-block text-success " . $sCssClass;
        } else {
            $aMessages = self::get("response", "messages", "errors", $sField);
            $sCssClass = "d-block text-danger " . $sCssClass;
        }

        if (!empty($aMessages)) {
            echo '<div>';
            foreach($aMessages as $sMessage) {
                self::echoFormMessage($sMessage, $sCssClass);
            }
            echo '</div>';
        }
    
    }

}

FrontHelper::initialize($controller);
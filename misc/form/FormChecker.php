<?php

class FormChecker {

    private $fields; // [sField => bRequired, ...]
    private $files; // [sFile => bRequired, ...]
    private $response;

    public function __construct($aFields, $aFiles = []) {

        $this->fields = $aFields;
        $this->files = $aFiles;
        $this->response = [
            "data" => [],
            "success" => true,
            "messages" => [
                "success" => [],
                "errors" => []
            ],
            "values" => []
        ];

    }

    // INPUT PART

    protected function checkSend($sField) {
        if (!isset($_POST[$sField])) {
            $this->pushError($sField, "Ce champ n'a pas été envoyé");
        }
    }

    protected function checkFull($sField) {
        if (isset($this->fields[$sField])) {
            if (empty($_POST[$sField])) {
                if ($this->fields[$sField]) {
                    $this->pushError($sField, "Vous devez remplir ce champ");
                }
            } else {
                $this->response["values"][$sField] = $_POST[$sField];
            }
        } else {
            $this->keyError(__METHOD__, $sField);
        }
    }

    protected function checkSends() {
        foreach (array_keys($this->fields) as $sField) {
            if (!isset($_POST[$sField])) {
                $this->pushError($sField, "Ce champ n'a pas été envoyé");
            }
        }
    }

    protected function checkFulls() {
        foreach ($this->fields as $sField => $bRequired) {
            if (empty($_POST[$sField])) {
                if ($bRequired) {
                    $this->pushError($sField, "Vous devez remplir ce champ");
                }
            } else {
                $this->response["values"][$sField] = $_POST[$sField];
            }
        }
    }

    protected function checkContent($sKey, $nMinLen, $nMaxLen, $sRegEx=null, $sRegExWritable=null) {
        if (isset($_POST[$sKey])) {
            $sData = $_POST[$sKey];
            $nDataLen = strlen($sData);
            if ($nDataLen < $nMinLen || $nDataLen > $nMaxLen) {
                $this->pushError($sKey, "Doit contenir de " . $nMinLen . " à " . $nMaxLen . " caractères");
            }
            if ($sRegEx !== null) {
                $bMatch = preg_match($sRegEx, $sData);
                if (!$bMatch) {
                    if ($sRegExWritable !== null) {
                        $this->pushError($sKey, $sRegExWritable);
                    } else {
                        $this->pushError($sKey, "Doit uniquement comporter les caractères " . $sRegEx);
                    }
                }
            }
        } else {
            $this->keyError(__METHOD__, $sKey);
        }
    }

    protected function checkNumeric($sKey, $nMinVal, $nMaxVal, $nDecimalPlace = 0) {
        if (isset($_POST[$sKey])) {
            $sData = $_POST[$sKey];
            if (!$nDecimalPlace) {
                if (filter_var($sData, FILTER_VALIDATE_INT) === false) {
                    $this->pushError($sKey, "Doit comporter un chiffre entier");
                } else {
                    $nData = intval($sData);
                    if ($nData < $nMinVal || $nData > $nMaxVal) {
                        $this->pushError($sKey, "Doit être compris entre " . $nMinVal . " et " . $nMaxVal);
                    }
                }
            } else {
                $sData = str_replace(",", ".", $sData);
                if (filter_var($sData, FILTER_VALIDATE_FLOAT) === false) {
                    $this->pushError($sKey, "Doit comporter uniquement des chiffres, jusqu'à " . $nDecimalPlace . " décimales séparé par un point ou une virgule");
                } else {
                    $aData = explode(".", $sData);
                    if (count($aData) === 2) {
                        if (strlen($aData[1]) > $nDecimalPlace) {
                            $this->pushError($sKey, "Jusqu'à " . $nDecimalPlace . " décimales maximum");
                        }
                    }
                    $nData = floatval($sData);
                    if ($nData < $nMinVal || $nData > $nMaxVal) {
                        $this->pushError($sKey, "Doit être compris entre " . $nMinVal . " et " . $nMaxVal);
                    }
                }
            }
        } else {
            $this->keyError(__METHOD__, $sKey);
        }
    }

    protected function checkEmail($sKey) {
        if (isset($_POST[$sKey])) {
            $sData = $_POST[$sKey];
            if (!filter_var($sData, FILTER_VALIDATE_EMAIL)) {
                $this->pushError($sKey, "Doit être une adresse email valide");
            }
        } else {
            $this->keyError(__METHOD__, $sKey);
        }
    }

    protected function checkPhone($sKey) {
        if (isset($_POST[$sKey])) {
            $sData = $_POST[$sKey];
            $nDataLen = strlen($sData);
            if (!is_numeric($sData) || $nDataLen != 10) {
                $this->pushError($sKey, "Doit être un numéro de téléphone valide");
            }
        } else {
            $this->keyError(__METHOD__, $sKey);
        }
    }

    protected function checkBirthday($sKey, $sFormat="Y-m-d") {
        if (isset($_POST[$sKey])) {
            $sData = $_POST[$sKey];
            $oDateTime = DateTime::createFromFormat($sFormat, $sData);
            if (!$oDateTime || $oDateTime->format($sFormat) ==! $sData) {
                $this->pushError($sKey, "Doit être une date valide");
            } else {
                if ($oDateTime->getTimestamp() > time()) {
                    $this->pushError($sKey, "Wow!");
                    $this->pushError($sKey, "Quoi?!");
                    $this->pushError($sKey, "Mais oui!...");
                    $this->pushError($sKey, "... j'ai compris.");
                    $this->pushError($sKey, "C'est donc ça!");
                    $this->pushError($sKey, "La machine à voyager dans le temps existe bel et bien!");
                } else {
                    $oDateInterval = date_diff($oDateTime, date_create());
                    if ($oDateInterval->y < Constants::USER_REQUIRED_AGE) {
                        $this->pushError($sKey, "Vous devez être majeur");
                    }
                }
            }
        } else {
            $this->keyError(__METHOD__, $sKey);
        }
    }

    protected function checkSamePasswords($sKey_1, $sKey_2) {
        if (isset($_POST[$sKey_1])) {
            if (isset($_POST[$sKey_2])) {
                $sData_1 = $_POST[$sKey_1];
                $sData_2 = $_POST[$sKey_2];
                if ($sData_1 !== $sData_2) {
                    $this->pushError($sKey_2, "Les mots de passes ne correspondent pas");
                }
            } else {
                $this->keyError(__METHOD__, $sKey_2);
            }
        } else {
            $this->keyError(__METHOD__, $sKey_1);
        }
    }

    protected function checkCheckbox($sKey) {
        if (isset($_POST[$sKey])) {
            $sData = $_POST[$sKey];
            if ($sData != "on") {
                $this->pushError($sKey, "Vous devez cocher ce champ");
            }
        } else {
            $this->keyError(__METHOD__, $sKey);
        }
    }

    protected function checkEmailDiff($sNewEmail, $sOldEmail) {
        return $sNewEmail != $sOldEmail;
    }

    protected function checkPassword($sPwdClear, $sPwdHash) {
        return password_verify($sPwdClear, $sPwdHash);
    }

    // FILES PART

    protected function checkFileSend($sFile) {
        if (!isset($_FILES[$sFile])) {
            $this->pushError($sFile, "Ce champ n'a pas été envoyé");
        }
    }

    protected function checkFileSends() {
        foreach (array_keys($this->files) as $sFile) {
            if (!isset($_FILES[$sFile])) {
                $this->pushError($sFile, "Ce champ n'a pas été envoyé");
            }
        }
    }

    protected function checkFiles($sKey, $nMinFile, $nMaxFile, $nMaxSize, $aAcceptExt, $bImg = false) {
        if (isset($_FILES[$sKey])) {

            $aFiles = $_FILES[$sKey];

            if ($aFiles["error"][0] === UPLOAD_ERR_NO_FILE) {
                if ($nMinFile > 0) {
                    $this->pushError($sKey, "Vous devez uploader de " . $nMinFile . " à " . $nMaxFile . " fichiers");
                    return;
                } else {
                    return;
                }
            }

            if (count($aFiles["name"]) >= $nMinFile && count($aFiles["name"]) <= $nMaxFile) {
                for ($i=0; $i<count($aFiles["name"]); $i++) {
                    $sName = $aFiles["name"][$i];
                    $sNameSafe = htmlspecialchars($sName);
                    $sTmpName = $aFiles["tmp_name"][$i];
                    $sType = $aFiles["type"][$i];
                    $nError = $aFiles["error"][$i];
                    $nSize = $aFiles["size"][$i];
                    if ($nError !== UPLOAD_ERR_OK) {
                        $this->pushError($sKey, "Une erreur est survenue avec le fichier '" . $sNameSafe . "'");
                    }
                    if ($nSize > $nMaxSize) {
                        $this->pushError($sKey, "Le fichier '" . $sNameSafe . "' est trop volumineux, la taille maximale est de " . $nMaxSize . " octets");
                    }
                    if (!in_array($sType, $aAcceptExt)) {
                        $this->pushError($sKey, "Le type du fichier '" . $sNameSafe . "' n'est pas accepté.");
                    } elseif ($bImg) {
                        $this->checkFileIsImage($sKey, $sName, $sTmpName);
                    }
                }
            } else {
                $this->pushError($sKey, "Vous devez uploader de " . $nMinFile . " à " . $nMaxFile . " fichiers");
            }
        } else {
            $this->keyError(__METHOD__, $sKey);
        }
    }

    protected function checkFileIsImage($sKey, $sName, $sTmpName) {
        if (exif_imagetype($sTmpName) === false) {
            $this->pushError($sKey, "Le fichier '" . $sName . "' n'est pas une image.");
        }
    }

    // RESPONSE PART

    protected function pushData($sKey, $mData) {
        $this->response["data"][$sKey] = $mData;
    }

    protected function pushSuccess($sKey, $sMsg) {
        if (isset($this->response["messages"]["success"][$sKey])) {
            array_push($this->response["messages"]["success"][$sKey], $sMsg);
        } else {
            $this->response["messages"]["success"][$sKey] = [$sMsg];
        }
    }

    protected function pushError($sKey, $sMsg) {
        $this->response["success"] = false;
        if (isset($this->response["messages"]["errors"][$sKey])) {
            array_push($this->response["messages"]["errors"][$sKey], $sMsg);
        } else {
            $this->response["messages"]["errors"][$sKey] = [$sMsg];
        }
    }

    public function getResponse() {
        $aParams = func_get_args();
        if (count($aParams) === 0) {
            return $this->response;
        } else {
            $data = $this->response;
            foreach ($aParams as $sParam) {
                if (isset($data[$sParam])) {
                    $data = $data[$sParam];
                    continue;
                }
                return null;
            }
            return $data;
        }
    }

    public function getSuccess() {
        return $this->getResponse("success");
    }

    public function getData() {
        $aCallable = [$this, "getResponse"];
        $aParams = func_get_args();
        array_unshift($aParams, "data");
        return call_user_func_array($aCallable, $aParams);
    }

    // DEV MISTAKE PART

    protected function keyError($sFrom, $sKey) {
        $this->printError($sFrom, "La clef:" . $sKey . " n'est pas indexé");
    }

    private function printError($sFrom, $sError) {
        print $sFrom . "()" . " >>> " . $sError;
    }

}

?>
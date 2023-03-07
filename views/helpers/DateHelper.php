<?php 

class DateHelper {

    const DAY_NAMES = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
    const MONTH_NAMES = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aôut", "Septembre", "Octobre", "Novembre", "Décembre"];

    private function getDateTime($sDate, $sFormat = "Y-m-d H:i:s", $sTimeZone = "UTC") {
        $oDateTime = DateTime::createFromFormat($sFormat, $sDate, new DateTimeZone($sTimeZone));
        if (!$oDateTime || $oDateTime->format($sFormat) ==! $sDate) {
            return null;
        }
        return $oDateTime;
    }

    public function getFull($sDate, $sFromTimeZone = "UTC", $sToTimeZone = "Europe/Paris") {

        $oDateTime = $this->getDateTime($sDate, "Y-m-d H:i:s", $sFromTimeZone);

        if ($oDateTime instanceof DateTime) {
            
            if ($sFromTimeZone != $sToTimeZone) {
                $oDateTime->setTimezone(new DateTimeZone("Europe/Paris"));
            }

            $sDayName = DateHelper::DAY_NAMES[$oDateTime->format("w")];
            $sDay = $oDateTime->format("d");
            $sMonthName = DateHelper::MONTH_NAMES[$oDateTime->format("n") - 1];
            $sYear = $oDateTime->format("Y");
            $sTime = $oDateTime->format("H:i:s");

            return "{$sDayName} {$sDay} {$sMonthName} {$sYear} à {$sTime}";

        }

        return "Date inconnue";

    }

}
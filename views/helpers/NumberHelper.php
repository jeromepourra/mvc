<?php 

class NumberHelper {

    private function getFloat($sNum) {
        if (is_numeric($sNum)) {
            return floatval($sNum);
        }
        return null;
    }
    
    public function format($sNum) {
        $nNum = $this->getFloat($sNum);
        if ($nNum !== null) {
            return number_format($nNum, 2, ",", " ");
        }
        return "Nombre invalide";
    }

}
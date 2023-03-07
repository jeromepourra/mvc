<?php

namespace ModelData;

abstract class Manager {

    function __get($name) {
        if (isset($this->$name)) {
            return $this->$name;
        } else {
            if (\Constants::DEV_MODE) {
                print "Impossible de lire la propriété " . $name . " car elle n'existe pas dans la classe " . get_class($this);
            }
        }
    }

    function __set($name, $value) {
        if (array_key_exists($name, get_class($this)::PROP_CONVERTER)) {
            $sFunc = get_class($this)::PROP_CONVERTER[$name];
            if (function_exists($sFunc)) {
                $this->$name = get_class($this)::PROP_CONVERTER[$name]($value);
            } else {
                $this->$name = $value;
            }
        } else {
            if (\Constants::DEV_MODE) {
                print "Impossible de définir la propriété " . $name . " car elle n'est pas définie dans la classe " . get_class($this);
            }
        }
    }

}
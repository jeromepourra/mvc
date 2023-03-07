<?php

class Constants {

    public const DEV_MODE = true;

    public const WEB_SITE_NAME = "Chercher & Trouver";
    public const WEB_SITE_DESCRIPTION_DEFAULT = self::WEB_SITE_NAME . " est un site de petites annonces en ligne où vous pourrez y trouver toute sortes de biens mis en vente par des particulier dans toute la France";

    public const REMEMBER_FORM_INPUT_VALUES = true;

    // REG EXP
    public const REGEX_ALPHA = "/^[A-Za-z]*$/";
    public const REGEX_ALPHA_WRITABLE = "Peut contenir uniquement des caractères alphabétique [&nbsp;a-z&nbsp;,&nbsp;A-Z&nbsp;], pas d'espace ni accents";

    public const REGEX_ALPHA_TIRET = "/^[A-Za-z\-]*$/";
    public const REGEX_ALPHA_TIRET_WRITABLE = "Peut contenir uniquement des caractères alphabétique [&nbsp;a-z&nbsp;,&nbsp;A-Z&nbsp;], ainsi que des tirets [&nbsp;-&nbsp;], pas d'espace ni accents";

    public const REGEX_ALPHA_ACCENT_TIRET = "/^[A-Za-zÀ-ÖØ-öø-ÿ\-]*$/";
    public const REGEX_ALPHA_ACCENT_TIRET_WRITABLE = "Peut contenir uniquement des caractères alphabétique [&nbsp;a-z&nbsp;,&nbsp;A-Z&nbsp;], ainsi que des tirets [&nbsp;-&nbsp;], des accents, pas d'espace";

    public const REGEX_ALPHA_NUM = "/^[A-Za-z0-9]*$/";
    public const REGEX_ALPHA_NUM_WRITABLE = "Peut contenir uniquement des caractères alphabétique [&nbsp;a-z&nbsp;,&nbsp;A-Z&nbsp;], numérique [&nbsp;0-9&nbsp;], pas d'espace ni accents";

    public const REGEX_ALPHA_NUM_TIRETS_UNDERSCORE = "/^[A-Za-z0-9\-_]*$/";
    public const REGEX_ALPHA_NUM_TIRETS_UNDERSCORE_WRITABLE = "Peut contenir uniquement des caractères alphabétique [&nbsp;a-z&nbsp;,&nbsp;A-Z&nbsp;], numérique [&nbsp;0-9&nbsp;], ainsi que les caractères spéciaux [&nbsp;-&nbsp;,&nbsp;_&nbsp;], pas d'espace ni accents";

    public const REGEX_PASSWORD = "/^[A-Za-z0-9\-_*-+=!?.@#$%\s]*$/";
    public const REGEX_PASSWORD_WRITABLE = "Peut contenir uniquement des caractères alphabétique [&nbsp;a-z&nbsp;,&nbsp;A-Z&nbsp;], numérique [&nbsp;0-9&nbsp;], des espaces [&nbsp;&nbsp;], ainsi que les caractères spéciaux [&nbsp;-&nbsp;,&nbsp;_&nbsp;,&nbsp;*&nbsp;,&nbsp;-&nbsp;,&nbsp;+&nbsp;,&nbsp;=&nbsp;,&nbsp;!&nbsp;,&nbsp;?&nbsp;,&nbsp;.&nbsp;,&nbsp;@&nbsp;,&nbsp;#&nbsp;,&nbsp;$&nbsp;,&nbsp;%&nbsp;]";

    // FORM ACCOUNT
    public const USER_PSEUDO_MIN_LEN = 2;
    public const USER_PSEUDO_MAX_LEN = 32;

    public const USER_FIRST_NAME_MIN_LEN = 2;
    public const USER_FIRST_NAME_MAX_LEN = 32;

    public const USER_LAST_NAME_MIN_LEN = 2;
    public const USER_LAST_NAME_MAX_LEN = 32;

    public const USER_EMAIL_MIN_LEN = 1;
    public const USER_EMAIL_MAX_LEN = 126;
 
    public const USER_PASSWORD_MIN_LEN = 8;
    public const USER_PASSWORD_MAX_LEN = 72;

    public const USER_REQUIRED_AGE = 18;

    // FORM ANNONCE
    public const ANNONCE_TITLE_MIN_LEN = 3;
    public const ANNONCE_TITLE_MAX_LEN = 32;

    public const ANNONCE_PRICE_MIN = 0;
    public const ANNONCE_PRICE_MAX = 1000000;

    public const ANNONCE_DESCRIPTION_MIN_LEN = 3;
    public const ANNONCE_DESCRIPTION_MAX_LEN = 1024;

    public const ANNONCE_PICTURES_MIN = 1;
    public const ANNONCE_PICTURES_MAX = 5;
    public const ANNONCE_PICTURES_ACCEPT_EXT = ["image/gif", "image/jpeg", "image/png"];
    public const ANNONCE_PICTURE_MAX_OCTET = 5 * 1024 * 1024; // 5 Mo
    public const ANNONCE_PICTURE_FILE_NAME_LENGTH = 3;
    public const ANNONCE_PICTURE_DIRECTORY_NAME_LENGTH = 9;

    public const ANNONCE_STATES = [
        -1 => "Status de l'annonce inconnu...",
        0 => "En attente de validation",
        1 => "Validation refusée",
        2 => "Supprimée",
        3 => "En ligne"
    ];

    public const ANNONCE_STATE_WAIT = 0;
    public const ANNONCE_STATE_DENIED = 1;
    public const ANNONCE_STATE_REMOVED = 2;
    public const ANNONCE_STATE_ONLINE = 3;

    public const PASSWORD_HASH_OPTIONS = [
        "cost" => 12
    ];

    // PATH
    public const PATH_IMG = "img/";
    public const PATH_UPLOAD = self::PATH_IMG . "upload/";
    public const PATH_ANNONCES = self::PATH_UPLOAD . "annonces/";

}
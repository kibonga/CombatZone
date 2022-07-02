<?php
// ROOT LEVEL
define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "");

// FIRST LEVEL DIRECTORIES
define("MODELS", ROOT . "/models");
define("VIEWS", ROOT . "/views");
define("DATA", ROOT . "/data");
define("CONFIG", ROOT . "/config");
define("ASSETS", ROOT . "/assets");

// SECOND LEVEL DIRECTORIES
// Views
define("FIXED", VIEWS . "/fixed");
define("PAGES", VIEWS . "/pages");
// Pages (inside views)
define("V_PRODUCTS", PAGES . "/products");
define("V_LOGS", PAGES . "/logs");
define("V_USERS", PAGES . "/users");
define("V_AUTH", PAGES . "/auth");
define("V_ADMIN", V_USERS . "/admin");
define("V_ADMIN_MODE", V_ADMIN . "/mode");
define("V_GLOVES", PAGES . "/gloves");
define("V_CUSTOMER", V_USERS . "/customer");
define("V_PUBLIC",  PAGES . "/public");
// Models
define("M_USERS", MODELS . "/users");
define("M_PRODUCTS", MODELS . "/products");
define("M_LOGS", MODELS . "/logs");
define("M_AUTH", MODELS . "/auth");
// Made changes here
// define("M_ADMIN", MODELS . "/admin");
define("M_ADMIN", M_USERS . "/admin");
define("M_CUSTOMER", MODELS . "/customer");
// Static
define("S_CSS", ASSETS . "/css");
define("S_IMG", ASSETS . "/img");
define("S_JS", ASSETS . "/js");
define("S_IMG_GLOVES", S_IMG . '/gloves');

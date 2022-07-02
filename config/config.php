<?php
// Configuration constants
define("SERVER", env("SERVER"));
define("DATABASE", env("DATABASE"));
define("USERNAME", env("USERNAME"));
define("PASSWORD", env("PASSWORD"));
define("WEBSITE", env("WEBSITE"));

// View data constants
// define("TITLE", viewData(""));

// Env function
function env($param)
{
    $rows = file(CONFIG . "/.env");
    $const = null;

    foreach ($rows as $i => $r) {
        $r = trim($r);
        list($name, $val) = explode("=", $r);
        if ($name == $param) {
            $const = $val;
            break;
        }
    }
    return $const;
}

<?php

if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/../env.php")) {
    include($_SERVER["DOCUMENT_ROOT"] . "/../env.php");
}

if(!function_exists("env")) {
    function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }
        return $value;
    }
}

<?php

$env_vars = [
    "DB_NAME" => "",
    "DB_HOST" => "",
    "DB_USER" => "",
    "DB_PASS" => "",
    "DB_PORT" => ""
];

foreach ($env_vars as $key => $val) {
    putenv("$key=$val");
}

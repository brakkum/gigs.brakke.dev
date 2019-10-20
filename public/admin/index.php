<?php
    session_start();
    include($_SERVER["DOCUMENT_ROOT"] . "/../autoload.php");
    include($_SERVER["DOCUMENT_ROOT"] . "/../db.php");
    $db = new Db();

    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: /admin/login.php");
    }

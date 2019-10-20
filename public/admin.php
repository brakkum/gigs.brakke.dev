<?php
    if (!isset($path)) {
        header("Location: /");
    }
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: /login");
    }

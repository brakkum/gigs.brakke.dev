<?php

    if (!isset($path)) {
        header("Location: /");
    }
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: /login");
    }
    $gig_id = $_GET["gig_id"];
    $gig = $db->getGig($gig_id);
    if (empty($gig)) {
        header("Location: /admin");
    }

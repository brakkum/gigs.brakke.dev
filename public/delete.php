<?php

    if (!isset($path)) {
        header("Location: /");
    }
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: /login");
    }

    $gig_id = isset($_GET["gig_id"]) ? intval($_GET["gig_id"]) : 0;
    $gig = $db->getGig($gig_id);

    if (empty($gig) && !$is_new_gig) {
        header("Location: /");
    }

    if (isset($_POST["confirm"])) {
        $db->deleteGig($gig_id);
        header("Location: /");
    } elseif (isset($_POST["deny"])) {
        header("Location: /");
    }

?>
<form method="post">
    <h1><?php echo $gig["gig_date"]; ?> - <?php echo $gig["gig_group"]; ?></h1>
    <h2><?php echo $gig["gig_location"]; ?></h2>
    <div>
        Are you sure you want to delete this gig?
    </div>
    <div class="admin-buttons">
        <button class="admin-button large" type="submit" name="confirm">
            Yes
        </button>
        <button class="admin-button large" type="submit" name="deny">
            No
        </button>
    </div>
</form>

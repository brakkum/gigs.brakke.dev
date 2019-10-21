<?php

    if (!isset($path)) {
        header("Location: /");
    }
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: /login");
    }

    $is_new_gig = $path === "/new";

    $gig_id = $_GET["gig_id"] ?? "";
    $gig = $db->getGig($gig_id);

    if (empty($gig) && !$is_new_gig) {
        header("Location: /");
    }

    $gig_date = $gig["gig_date"] ? date("Y-m-d", strtotime($gig["gig_date"])) : "";
    $gig_group = $gig["gig_group"] ?? "";
    $gig_location = $gig["gig_location"] ?? "";
    $gig_description = $gig["gig_description"] ?? "";

?>

<form method="post" class="gig-form">
    <input type="hidden" name="gig_id" value="<?php echo $gig_id; ?>" />
    <label>
        Date
        <input type="date" name="gig_date" value="<?php echo $gig_date; ?>" class="input" />
    </label>
    <label>
        Group
        <input type="text" name="gig_group" value="<?php echo $gig_group; ?>" class="input" />
    </label>
    <label>
        Location
        <input type="text" name="gig_location" value="<?php echo $gig_location; ?>" class="input" />
    </label>
    <label>
        Description
        <textarea name="gig_description" maxlength="500" class="input"><?php echo trim($gig_description); ?></textarea>
    </label>
    <button class="admin-button input" type="submit">
        Submit
    </button>
</form>

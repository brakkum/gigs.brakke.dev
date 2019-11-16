<?php

    if (!isset($path)) {
        header("Location: /");
    }
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: /login");
    }

    $is_new_gig = $path === "/new";
    $is_duplicating_post = isset($_GET["duplicate"]);

    $gig_id = isset($_GET["gig_id"]) ? intval($_GET["gig_id"]) : 0;
    $gig = $db->getGig($gig_id);

    if (empty($gig) && !$is_new_gig) {
        header("Location: /");
    }

    $gig_date = $gig["gig_date"] ? date("Y-m-d", strtotime($gig["gig_date"])) : "";
    $gig_group = $gig["gig_group"] ?? "";
    $gig_location = $gig["gig_location"] ?? "";
    $gig_description = $gig["gig_description"] ?? "";
    $message = $_POST["message"] ?? "";

    if (isset($_POST["form_submitted"])) {
        $date = $_POST["gig_date"] ?? "";
        $group = $_POST["gig_group"] ?? "";
        $location = $_POST["gig_location"] ?? "";
        $description = $_POST["gig_description"] ?? "";
        $gig_id = intval($_POST["gig_id"]);
        if (!$date || !$group || !$location) {
            $message = "You're missing stuff";
            $gig_description = $description;
            $gig_date = $date;
            $gig_location = $location;
            $gig_group = $group;
        } else {
            if ($gig_id == 0) {
                // new gig
                $date = date("Y-m-d H:i:s", strtotime($date));
                $new_id = $db->newGig($date, $group, $location, $description);
                unset($_POST);
                $_POST["message"] = "Success";
                header("Location: /edit?gig_id=$new_id");
            } else {
                // update gig
                $date = date("Y-m-d H:i:s", strtotime($date));
                $db->updateGig($gig_id, $date, $group, $location, $description);
                unset($_POST);
                $_POST["message"] = "Success";
                header("Location: /edit?gig_id=$gig_id");
            }
        }
    }

?>

<form method="post" class="gig-form">
    <input type="hidden" name="gig_id" value="<?php echo ($is_new_gig || $is_duplicating_post) ? 0 : $gig_id; ?>" />
    <input type="hidden" name="form_submitted" value="1" />
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
    <button class="admin-button submit input" name="gig-submit-button" type="submit">
        Submit
    </button>
</form>
<?php if ($message) : ?>
    <h2 class="gig-page-message"><?php echo $message; ?></h2>
<?php endif; ?>

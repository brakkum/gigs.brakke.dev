<?php
    include($_SERVER["DOCUMENT_ROOT"] . "/../db.php");
    $gig_id = $_GET["gig_id"] ?? "";
    $db = new Db();
    $gig = $db->getGig($gig_id);
    if (empty($gig)) {
        die("Where my gig");
    }
    $gig_videos = $db->query("SELECT * FROM gig_videos WHERE gig_id={$gig["id"]}");
    if ($gig["gig_description"]) : ?>
        <div class="gigs-table-item">
            <h4>About</h4>
            <p><?php echo $gig["gig_description"]; ?></p>
        </div>
    <?php endif;
    if ($gig_videos->num_rows > 0) : ?>
        <div class="gigs-table-item">
            <h4>Videos</h4>
            <?php foreach ($gig_videos as $j => $video) : ?>
                <iframe src="<?php echo $video["video_url"] ?>" id="gig<?php echo $gig_id; ?>Video<?php echo $j ?>">
                </iframe>
            <?php endforeach; ?>
        </div>
    <?php endif ?>

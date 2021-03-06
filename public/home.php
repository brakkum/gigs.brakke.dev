<?php
    if (!isset($path)) {
        header("Location: /");
    }

    $query = $_GET;
    $group = $query["group"] ?? "";
    $date = $query["date"] ?? "";
    $location = $query["location"] ?? "";
    $order_by = $query["ob"] ?? "gig_date";
    $order = $query["order"] ?? "DESC";
    $gigs = $db->getAllGigs($group, $date, $location, $order_by, $order);

    function get_table_header($column) {
        $query = $_GET;
        $order = $query["order"] ?? "DESC";
        $order_by = $query["ob"] ?? "gig_date";
        $order_display = "";
        if ($column === "Date") {
            $order_display = (!$order_by || $order_by === "gig_date") ? $order : "";
            $query["ob"] = "gig_date";
        } elseif ($column === "Group") {
            $order_display = $order_by === "gig_group" ? $order : "";
            $query["ob"] = "gig_group";
        } elseif ($column === "Location") {
            $order_display = $order_by === "gig_location" ? $order : "";
            $query["ob"] = "gig_location";
        }
        if ($order_display !== "") {
            $order_display = $order_display == "ASC" ? "&uarr;" : "&darr;";
        }
        $ob_is_same = $query["ob"] === $order_by;
        $new_order = ($ob_is_same && $order === "ASC") ? "DESC" : "ASC";
        $query["order"] = $new_order;
        $new_query = http_build_query($query);
        $active_class = $order_display !== "" ? "selected" : "";
        return "<a href='?$new_query' class='column-header $active_class'>$column</a><span>$order_display</span>";
    }

?>
<div class="body">
    <section class="search-bar">
        <form method="get" class="search-form">
            <input placeholder="Search By Group" value="<?php echo $group; ?>" name="group" />
            <input placeholder="Search By Date" value="<?php echo $date; ?>" name="date" />
            <input placeholder="Search By Location" value="<?php echo $location; ?>" name="location" />
            <button type="submit">Search</button>
        </form>
    </section>
    <?php $num_results = $gigs->num_rows; ?>
    <?php if ($num_results > 0) : ?>
        <div class="gig-stats">
            <div class="gig-stat">
                <?php echo $num_results ?> gigs
            </div>
            <?php if ($is_admin) : ?>
                <div class="gig-stat">
                    <a href="/new" class="admin-button">
                        New gig
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="gigs-table">
            <div class="gigs-table-row head">
                <div class="gigs-table-item"><?php echo get_table_header("Date"); ?></div>
                <div class="gigs-table-item"><?php echo get_table_header("Group"); ?></div>
                <div class="gigs-table-item"><?php echo get_table_header("Location"); ?></div>
                <?php if ($is_admin) : ?>
                    <div class="gigs-table-item"></div>
                <?php endif; ?>
            </div>
            <?php foreach ($gigs as $gig) : ?>
                <div class="gigs-table-row" onclick="<?php echo !$is_admin ? "toggleRow({$gig["id"]});" : "null" ; ?>">
                    <div class="gigs-table-item">
                        <?php echo $gig["gig_date"]; ?>
                    </div>
                    <div class="gigs-table-item">
                        <?php echo $gig["gig_group"]; ?>
                    </div>
                    <div class="gigs-table-item">
                        <?php echo $gig["gig_location"]; ?>
                    </div>
                    <?php if ($is_admin) : ?>
                        <div class="gigs-table-item">
                            <span class="admin-button" onclick="toggleRow(<?php echo $gig["id"]; ?>);">
                                Open
                            </span>
                            <a class="admin-button" href="/edit?gig_id=<?php echo $gig["id"]; ?>">
                                Edit
                            </a>
                            <a class="admin-button" href="/edit?gig_id=<?php echo $gig["id"]; ?>&duplicate">
                                Duplicate
                            </a>
                            <a class="admin-button" href="/delete?gig_id=<?php echo $gig["id"]; ?>">
                                Delete
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="gigs-table-row extras" id="gig<?php echo $gig["id"] ?>">

                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <h2 style="text-align: center">No results I guess...</h2>
    <?php endif ?>
</div>
<script type="application/javascript">
    toggleRow = id => {
        let extrasRow = document.getElementById(`gig${id}`);
        if (extrasRow.classList.contains("active")) {
            extrasRow.classList.add("fade-out");
            setTimeout(() => {
                extrasRow.classList.remove("fade-out");
                extrasRow.classList.remove("active");
                extrasRow.innerHTML = "";
            }, 1000);
        } else {
            fetch(`/gig_about.php?gig_id=${id}`, {
                method: "get"
            }).then(res => res.text())
                .then(text => {
                    extrasRow.innerHTML = text;
                    extrasRow.classList.add("fade-in");
                    setTimeout(() => {
                        extrasRow.classList.add("active");
                    }, 10);
                    setTimeout(() => {
                        extrasRow.classList.remove("fade-in");
                    }, 1000);
                }).catch(e => console.log(e));
        }
    };
</script>

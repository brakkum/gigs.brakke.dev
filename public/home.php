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
        if ($column === "date") {
            $order_display = $query["ob"] === "gig_date" ? $order : "";
            $new_order = $order === "ASC" ? "DESC" : "ASC";
            $query["order"] = $new_order;
            $query["ob"] = "gig_date";
            $new_query = http_build_query($query);
            return "<a href='?$new_query'>Date</a>$order_display";
        } elseif ($column === "group") {
            $order_display = $query["ob"] === "gig_group" ? $order : "";
            $new_order = $order === "ASC" ? "DESC" : "ASC";
            $query["order"] = $new_order;
            $query["ob"] = "gig_group";
            $new_query = http_build_query($query);
            return "<a href='?$new_query'>Group</a>$order_display";
        } elseif ($column === "location") {
            $order_display = $query["ob"] === "gig_location" ? $order : "";
            $new_order = $order === "ASC" ? "DESC" : "ASC";
            $query["order"] = $new_order;
            $query["ob"] = "gig_location";
            $new_query = http_build_query($query);
            return "<a href='?$new_query'>Group</a>$order_display";
        }
    }

?>
<div class="body">
    <section class="search-bar">
        <form method="get" class="search-form">
            <input placeholder="<?php echo $group ? $group : "Search By Group" ?>" name="group" />
            <input placeholder="<?php echo $date ? $date : "Search By Date" ?>" name="date" />
            <input placeholder="<?php echo $location ? $location : "Search By Location" ?>" name="location" />
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
                <div class="gigs-table-item"><?php echo get_table_header("date"); ?></div>
                <div class="gigs-table-item"><?php echo get_table_header("group"); ?></div>
                <div class="gigs-table-item"><?php echo get_table_header("location"); ?></div>
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

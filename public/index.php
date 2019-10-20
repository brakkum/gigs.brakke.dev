<?php
    include($_SERVER["DOCUMENT_ROOT"] . "/autoload.php");
    include($_SERVER["DOCUMENT_ROOT"] . "/db.php");

    $db = new Db();
    $group = $_GET["group"] ?? "";
    $gigs = $db->query("
        SELECT * FROM gigs WHERE 
        gig_group LIKE '%$group%' 
        ORDER BY gig_date DESC
    ");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="A listing of gigs performed by bassist Daniel Brakke">
        <meta name="keywords" content="Daniel,Brakke,Bass,Twin,Cities,Music,Gigs,Gigging">
        <meta name="author" content="Daniel Brakke">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Brakke's Gigs</title>
        <link href="./reset.css" rel="stylesheet" type="text/css" />
        <link href="./style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <header class="site-header">
            <h1 class="site-heading">Brakke's Gigs</h1>
        </header>
        <div class="body">
            <section class="search-bar">
                <form method="get" class="search-form">
                    <input placeholder="<?php echo $group ? $group : "Search By Group" ?>" name="group" />
                    <button type="submit">Search</button>
                </form>
            </section>
            <?php $num_results = $gigs->num_rows; ?>
            <?php if ($num_results > 0) : ?>
                <div class="gig-stats">
                    <div class="gig-stat">
                        <?php echo $num_results ?> gigs
                    </div>
                </div>
                <div class="gigs-table">
                    <div class="gigs-table-row head">
                        <div class="gigs-table-item">Date</div>
                        <div class="gigs-table-item">Group</div>
                        <div class="gigs-table-item">Location</div>
                    </div>
                    <?php foreach ($gigs as $gig) : ?>
                        <div class="gigs-table-row" onclick="toggleRow(<?php echo $gig["id"] ?>);">
                            <div class="gigs-table-item">
                                <?php echo $gig["gig_date"]; ?>
                            </div>
                            <div class="gigs-table-item">
                                <?php echo $gig["gig_group"]; ?>
                            </div>
                            <div class="gigs-table-item">
                                <?php echo $gig["gig_location"]; ?>
                            </div>
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
    </body>
</html>

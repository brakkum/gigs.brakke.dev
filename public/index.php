<?php
    session_start();
    include($_SERVER["DOCUMENT_ROOT"] . "/../db.php");

    $db = new Db();

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
        <link href="/reset.css" rel="stylesheet" type="text/css" />
        <link href="/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <header class="site-header">
            <h1 class="site-heading">
                <a href="/">
                    Brakke's Gigs
                </a>
            </h1>
        </header>
        <?php
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            switch ($path) {
                case "/admin":
                    include("./admin.php");
                    break;
                case "/login":
                    include("./login.php");
                    break;
                case "/logout":
                    include("./logout.php");
                    break;
                case "/new-admin":
                    include("./new_admin.php");
                    break;
                default:
                    include("./home.php");
                    break;
            }
        ?>
    </body>
</html>

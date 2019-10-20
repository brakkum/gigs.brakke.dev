<?php
    session_start();
    include($_SERVER["DOCUMENT_ROOT"] . "/../autoload.php");
    include($_SERVER["DOCUMENT_ROOT"] . "/../db.php");
    $db = new Db();

    $error = "";
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if ($db->isAdmin($username, $password)) {
            $_SESSION["logged_in"] = true;
            header("Location: /admin");
        } else {
            $error = "Nah, not good enough";
        }
    }

?>
<html>
    <form method="post">
        <input type="text" name="username" placeholder="Username" />
        <input type="password" name="password" placeholder="Password" />
        <button type="submit">Submit</button>
    </form>
    <h1><?php echo $error ? $error : ""; ?></h1>
</html>

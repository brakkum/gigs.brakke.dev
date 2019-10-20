<?php
    session_start();
    include($_SERVER["DOCUMENT_ROOT"] . "/../autoload.php");
    include($_SERVER["DOCUMENT_ROOT"] . "/../db.php");
    $db = new Db();

    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

        $db->newAdmin($username, $password);
        $_SESSION["logged_in"] = true;
        header("Location: /admin");
    }
?>

<html>
    <form method="post">
        <input type="text" name="username" placeholder="Username" />
        <input type="password" name="password" placeholder="Password" />
        <button type="submit">Submit</button>
    </form>
</html>

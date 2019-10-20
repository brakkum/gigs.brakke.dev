<?php
    if (!isset($path)) {
        header("Location: /");
    }

    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

        $db->newAdmin($username, $password);
        $_SESSION["logged_in"] = true;
        header("Location: /admin");
    }
?>

<html lang="en">
    <form method="post">
        <input type="text" name="username" placeholder="Username" />
        <input type="password" name="password" placeholder="Password" />
        <button type="submit">Submit</button>
    </form>
</html>

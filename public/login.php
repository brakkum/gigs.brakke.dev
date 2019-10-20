<?php
    if (!isset($path)) {
        header("Location: /");
    }

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
<form method="post">
    <input type="text" name="username" placeholder="Username" />
    <input type="password" name="password" placeholder="Password" />
    <button type="submit">Submit</button>
</form>
<h1 style="text-align: center; font-size: 2em;"><?php echo $error; ?></h1>

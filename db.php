<?php
    include($_SERVER["DOCUMENT_ROOT"] .  "/../autoload.php");

class Db {

    private $conn;

    public function __construct()
    {
        $this->conn = mysqli_connect(
            env("DB_HOST"),
            env("DB_USER"),
            env("DB_PASS"),
            env("DB_NAME")
        );
        if ($this->conn->connect_errno) {
            die("well shit. " . $this->conn->connect_error);
        }
    }

    public function query($query) {
        return $this->conn->query($query);
    }

    public function getGig($gig_id) {
        $sql = $this->conn->prepare("select * from gigs where id=?");
        $sql->bind_param("i", $gig_id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }

    public function newAdmin($username, $password)
    {
        $admin_check = $this->conn->prepare("select * from admin where username=?");
        $admin_check->bind_param("s", $username);
        $admin_check->execute();
        if ($admin_check->get_result()->num_rows > 0) {
            die("Username exists");
        }
        $admin_check->close();
        $new_admin = $this->conn->prepare("insert into admin (username, password) values (?, ?)");
        $new_admin->bind_param("ss", $username, $password);
        $new_admin->execute();
        $new_admin->close();
    }

    public function isAdmin($username, $password)
    {
        $sql = $this->conn->prepare("select * from admin where username=?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $result = $sql->get_result();
        if ($result->num_rows == 0) {
            return false;
        }
        return password_verify($password, $result->fetch_assoc()["password"]);
    }
}

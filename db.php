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

    public function getAllGigs($group, $date, $location, $order_by, $order) {
        $allowed_order_by = ["gig_date", "gig_location", "gig_group"];
        $allowed_order = ["ASC", "DESC", ""];
        if (!in_array($order_by, $allowed_order_by) || !in_array($order, $allowed_order)) {
            die("hey now");
        }
        $order = $order === "DESC" ? "DESC" : "ASC";
        $sql = $this->conn->prepare("
            SELECT * FROM gigs WHERE
            gig_group LIKE ? AND
            gig_date LIKE ? AND
            gig_location LIKE ?
            ORDER BY $order_by $order"
        );
        $group_like = "%$group%";
        $date_like = "%$date%";
        $location_like = "%$location%";
        $sql->bind_param("sss", $group_like, $date_like, $location_like);
        $sql->execute();
        return $sql->get_result();
    }

    public function getGig($gig_id) {
        $sql = $this->conn->prepare("select * from gigs where id=?");
        $sql->bind_param("i", $gig_id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }

    public function newGig($date, $group, $location, $description)
    {
        $sql = $this->conn->prepare("insert into gigs (gig_date, gig_group, gig_location, gig_description) values (?, ?, ?, ?)");
        $sql->bind_param("ssss", $date, $group, $location, $description);
        if ($sql->execute()) {
            return $sql->insert_id;
        } else {
            var_dump($sql->error_list);
            die();
        }
    }

    public function updateGig($gig_id, $date, $group, $location, $description)
    {
        $sql = $this->conn->prepare("update gigs set gig_date = ?, gig_group = ?, gig_location = ?, gig_description = ? where id = ?");
        $sql->bind_param("sssss", $date, $group, $location, $description, $gig_id);
        $sql->execute();
        if ($sql->execute()) {
            return $sql->insert_id;
        } else {
            var_dump($sql->error_list);
            die();
        }
    }

    public function deleteGig($gig_id)
    {
        $sql = $this->conn->prepare("delete from gigs where id = ?");
        $sql->bind_param("s",  $gig_id);
        if ($sql->execute()) {
            return $sql->insert_id;
        } else {
            var_dump($sql->error_list);
            die();
        }
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

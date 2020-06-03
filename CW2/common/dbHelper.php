<?php

class dbHelper
{
    // Database info
    private $host = 'iat.net.cn';
    private $dbName = 'sports';
    private $user = 'sports';
    private $pass = 'sports';
    private $dbh = null;

    public function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->dbName";
        date_default_timezone_set('Asia/Shanghai');
        $this->dbh = new PDO($dsn, $this->user, $this->pass);
        $this->dbh->query("set names utf8");
    }

    public function __destruct()
    {
        $this->dbh = null;
    }

    public function login($username, $password)
    {
        $password = md5($password);
        // User input is automatically enclosed in quotation marks, so there is no danger of SQL injection attacks.
        $sql = "select `id`, `username`, `name`,`email`, `gender`, `birthday` from `users` where `username`= ? and `password` = ?";
        $sth = $this->dbh->prepare($sql);

        $sth->execute([$username, $password]);
        $result = $sth->fetch();
        if ($result) {
            return ['code' => 0, 'data' => $result];
        } else {
            return ['code' => 1, 'message' => 'Username and password does not match'];
        }
    }

    public function updateProfile($id, $username, $name, $email, $gende, $birthday, $profile)
    {
        $sql = "update `users` set `username` = ?, `name` = ?, `email` = ?, `gender` = ?, `birthday` = ?, `profile` = ?, `updated_at` = ? where `id` = ?";
        $sth = $this->dbh->prepare($sql);
        $result = $sth->execute([$id, $username, $name, $email, $gende, $birthday, $profile, getNowTime()]);

        if ($result) {
            return ['code' => 0, 'message' => 'Successfully updated', 'data' => $result];
        } else {
            return ['code' => 1, 'message' => 'Update failed'];
        }
    }

    public function register($username, $password, $name, $email, $gende, $birthday, $profile)
    {
        $password = md5($password);
        $sql = "insert into `users`(`username`, `password`, `name`, `email`, `gender`, `birthday`,`profile`, `created_at`, `updated_at`) values(?,?,?,?,?,?,?,?,?)";
        $sth = $this->dbh->prepare($sql);
        $result = $sth->execute([$username, $password, $name, $email, $gende, $birthday, $profile, getNowTime(), getNowTime()]);

        if ($result) {
            return ['code' => 0, 'data' => $result];
        } else {
            return ['code' => 1, 'message' => 'Register failed'];
        }
    }

    // Add sports
    public function addSports($sport, $duration, $start_date, $start_time, $distance, $calories, $avg_speed,  $is_public)
    {
        $sql = "insert into `sports`(`user_id`, `sport`, `duration`, `start_date`, `start_time`, `distance`,`calories`, `avg_speed`, `is_public`, `created_at`, `updated_at`) values(?,?,?,?,?,?,?,?,?,?, ?)";
        $sth = $this->dbh->prepare($sql);
        $result = $sth->execute([$_SESSION['id'], $sport, $duration, $start_date, $start_time, $distance, $calories, $avg_speed, $is_public, getNowTime(), getNowTime()]);
        // var_dump($result);exit;
        // print_r($sth->errorInfo());
        if ($result) {
            return ['code' => 0, 'data' => $result];
        } else {
            return ['code' => 1, 'message' => 'Add failed'];
        }
    }

    // Edit sports
    public function editSports($id, $sport, $duration, $start_date, $start_time, $distance, $calories, $avg_speed,  $is_public)
    {
        $sql = "update `sports` set `sport` = ?, `duration` = ?, `start_date` = ?, `start_time` = ?, `distance` = ?,`calories` = ?, `avg_speed` = ?, `is_public` = ?, `updated_at` = ? where `id` = ?";
        $sth = $this->dbh->prepare($sql);
        $result = $sth->execute([$sport, $duration, $start_date, $start_time, $distance, $calories, $avg_speed, $is_public, getNowTime(), $id]);
        // print_r($sth->errorInfo());
        if ($result) {
            return ['code' => 0, 'data' => $result];
        } else {
            return ['code' => 1, 'message' => 'Edit failed'];
        }
    }

    // Delete sports
    public function deleteSports($id)
    {
        $sql = "delete from `sports` where `id` = ? and `user_id` = ?";
        $sth = $this->dbh->prepare($sql);
        $result = $sth->execute([$id, $_SESSION['id']]);
        // print_r($sth->errorInfo());
        if ($result) {
            return ['code' => 0, 'data' => $result];
        } else {
            return ['code' => 1, 'message' => 'Delete failed'];
        }
    }

    // Get my sports
    public function getMySports($page, $sport, $start_date, $sort, $order)
    {
        $skip = ($page - 1) * 10;
        // $sql = "select * from `sports` where `user_id`= ? limit $skip, 10";
        $sql = "select * from `sports` where `sport` like '%" . $sport . "%' and `start_date` like '%" . $start_date . "%' and `user_id`= ? limit $skip, 10";
        if ($sort) {
            $sql = "select * from `sports` where `sport` like '%" . $sport . "%' and `start_date` like '%" . $start_date . "%' and `user_id`= ? order by " . $sort . " " . $order . " limit $skip, 10";
        }
        
        $sth = $this->dbh->prepare($sql);

        $sth->execute([$_SESSION['id']]);
        // print_r($sth->errorInfo());

        $result = $sth->fetchAll();

        if ($result) {
            return ['code' => 0, 'data' => $result];
        } else {
            return ['code' => 0, 'data' => []];
        }
    }

    // Get all sports
    public function getAllSports($page, $sport, $start_date, $sort, $order)
    {
        $skip = ($page - 1) * 10;
        // $sql = "select * from `sports` where `user_id`= ? limit $skip, 10";
        $sql = "select * from `sports` join `users` on sports.user_id = users.id where `is_public` = 1 and `sport` like '%" . $sport . "%' and `start_date` like '%" . $start_date . "%'   limit $skip, 10";
        if ($sort) {
            $sql = "select * from `sports` join `users` on sports.user_id = users.id where `is_public` = 1 and `sport` like '%" . $sport . "%' and `start_date` like '%" . $start_date . "%' order by " . $sort . " " . $order . " limit $skip, 10";
        }
        
        $sth = $this->dbh->prepare($sql);

        $sth->execute();
        print_r($sth->errorInfo());

        $result = $sth->fetchAll();

        if ($result) {
            return ['code' => 0, 'data' => $result];
        } else {
            return ['code' => 0, 'data' => []];
        }
    }
}

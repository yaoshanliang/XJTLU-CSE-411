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

    public function login($username, $password) {
        $password = md5($password);
        // User input is automatically enclosed in quotation marks, so there is no danger of SQL injection attacks.
        $sql = "select `username`, `name`, `gender` from `users` where `username`= ? and `password` = ?";
        $sth = $this->dbh->prepare($sql);

        $sth->execute([$username, $password]);
        $result = $sth->fetch();
        if ($result) {
            return ['code' => 0, 'data' => $result];
        } else {
            return ['code' => 1, 'message' => 'Username and password does not match'];
        }
    }

    public function register($username, $password) {
        $password = md5($password);
        $sql = "insert into `users`(`username`, `password`) values(?,?)";
        $sth = $this->dbh->prepare($sql);
        $sth = $sth->execute([$username, $password]);
    }

}

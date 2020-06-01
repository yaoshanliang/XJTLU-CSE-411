<?php

Class dbHelper {

    public function __construct() {
        
    }
    // $dbms='mysql';
    // $host='iat.net.cn';
    // $dbName='sports';
    // $user='sports';
    // $pass='sports';
    // $dsn="$dbms:host=$host;dbname=$dbName";
    // date_default_timezone_set('Asia/Shanghai');
}



$dbh = new PDO($dsn, $user, $pass);
$dbh->query("set names utf8");
$sql = "insert into `kyj_videos` (`id`, `uid`, `title`, `thumb`, `href`, `likes`, `views`, `comments`, `size`, `steps`, `shares`, `datetime`, `lat`, `lng`, `city`, `add_time`, `update_time`) values (?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$sth = $dbh->prepare($sql);

$sql2 = "update `kyj_videos` set `uid` = ?, `title` = ?, `thumb` = ?, `href` = ?, `likes` = ?, `views` = ?, `comments` = ?, `size` = ?, `steps` = ?, `shares` = ?, `datetime` = ?, `lat` = ?, `lng` = ?, `city` = ?, `update_time` = ? where `id` = ?";
$sth2 = $dbh->prepare($sql2);
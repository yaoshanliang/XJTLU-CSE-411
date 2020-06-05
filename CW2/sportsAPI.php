<?php
session_start();

ini_set('display_errors', 0);
include_once('./common/function.php');
include_once('./common/dbHelper.php');
$dbHelper = new dbHelper();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['is_public'] == 'true') {
        $is_public = 1;
    } else {
        $is_public = 0;
    }

    if ($_POST['id']) {
        $result = $dbHelper->editSports($_POST['id'], $_POST['sports'], $_POST['duration'], $_POST['start_date'], $_POST['start_time'], $_POST['distance'], $_POST['calories'], $_POST['avg_speed'], $is_public);
    } else {
        $result = $dbHelper->addSports($_POST['sports'], $_POST['duration'], $_POST['start_date'], $_POST['start_time'], $_POST['distance'], $_POST['calories'], $_POST['avg_speed'], $is_public);
    }
    header('content-type:application/json;charset=utf8');
    echo json_encode($result);
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

    $result = file_get_contents('php://input');
    $data = explode('=', $result);
    $result = $dbHelper->deleteSports($data[1]);

    header('content-type:application/json;charset=utf8');
    echo json_encode($result);
}

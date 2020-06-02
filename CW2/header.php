<?php
session_start();

ini_set('display_errors', 1);
include_once('./common/function.php');
include_once('./common/dbHelper.php');
$dbHelper = new dbHelper();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sports Tracker</title>
    <meta name="description" content="Sports Tracker for All Users" />
    <link rel="shortcut icon" href="./images/favicon.ico" />
    <script src="./js/jquery.js"></script>
    <script src="./js/web.js"></script>
    <script src="./js/function.js"></script>
    <link rel="stylesheet" href="./css/lib.css" />
    <link rel="stylesheet" href="./css/style.css" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <div id="wrapper">
        <div id="navigation">
            <?php include_once('./menu.php'); ?>
        </div>
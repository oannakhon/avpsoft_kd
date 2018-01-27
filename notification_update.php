<?php
session_start();
include './mainfn.php';
$id = $_GET['id'];
$url = base64_decode($_GET['url']);
mysqli_query($link, "UPDATE `notification` SET"
        . "`NoStatus` = '2' "
        . "WHERE `id` = '$id' "
        . "AND `UserId` = '$_SESSION[UserId]'");

header("Location: ".$url);
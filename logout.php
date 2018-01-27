<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
session_destroy(); //Delete Session All

//Delete Cookies

@setcookie("UserId", "", time()-3600);
@setcookie("UserFullName", "", time()-3600);
@setcookie("UEmail", "", time()-3600);

header('Location: login.php');
?>
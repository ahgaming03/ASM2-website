<?php
session_start();
$_SESSION["loggedin"] = false;
$_SESSION["user_id"] = false;
header("Location: index.php");
exit;
?>
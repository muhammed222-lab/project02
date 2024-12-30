<?php
session_start(); // Start the session

$_SESSION = [];

session_destroy();

header("Location: index.php");
exit();
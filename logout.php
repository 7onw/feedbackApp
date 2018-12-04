<?php
session_start();

session_destroy();
    // auf Login-Seite weiterleiten
header("Location: login.php");
?>
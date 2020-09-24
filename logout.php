<?php
    require_once 'header.php';
    if(isset($_SESSION['username']))
    {
        $_SESSION = array();
        session_destroy();
        header('Location: index.php');
    }
    else 
    {
        echo "<div class='content'>You cannot logout because you are not logged in!</div>";
    }
?>
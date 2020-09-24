<?php
require_once 'functions.php';

if(isset($_POST['username']))
{
    $username = sanitizeString($_POST['username']);
    $result = queryMysql("SELECT * FROM users WHERE username='$username'");
    if($result->num_rows)
    {
        echo "<span class='taken'>That username is taken - please choose another one!
        </span>";
    }
    else
    {
        echo "<span class='available'>This username is available</span>";
    }
}

if(isset($_POST['lusername']))
{
    $username = sanitizeString($_POST['lusername']);
    $result = queryMysql("SELECT * FROM lokali WHERE username='$username'");
    if($result->num_rows)
    {
        echo "<span class='taken'>That username is taken - please choose another one!
        </span>";
    }
    else
    {
        echo "<span class='available'>This username is available</span>";
    }
}
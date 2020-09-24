<?php
    
    session_start();
    require_once "functions.php";

    $loggedin = false;
    $user = "Guest";
    $restoran=false;
    $tip=$id="";
    if(isset($_SESSION['username']))
    {
        $loggedin = true;
        $id = $_SESSION['id']; // $id - id logovanog korisnika
        $user = $_SESSION['username']; // $user - username logovanog korisnika
        if(isset($_SESSION['tip'])){
        $tip=$_SESSION['tip'];
        $restoran=true;
        }
      
    }
   
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoGrada</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="GH">
<div class="header">
<a href="index.php"><img src="Slike/logo.png" alt="Logo" id="header_image"></a>
</div>  <?php if($loggedin && $tip == "") { ?>
<div class="restorani"> <a href="restorani.php">NađiMesto</a></div>
<div class="restorani"> <a href="edit.php">Profil</a></div>
<div class="restorani"> <a href="logout.php">LogOut</a></div>
<div class="ime">Username:<br><i><?php echo($user); ?></i></div>
<?php } else if($tip != "") { ?>
<div class="restorani"> <a href="restorani.php">NađiMesto</a></div>
<div class="restorani"> <a href="edit.php">Profil</a></div>
<div class="restorani"> <a href="logout.php">LogOut</a></div>
<div class="ime"> <a href="brojmesta.php">Username:<br><i><?php echo($user); ?></i></a></div>
<?php } else { ?>
<div class="restorani"> <a href="restorani.php">NađiMesto</a></div>
<div class="login"> <a href="login.php">Log in</a></div>
<div class="signup"> <a href="signup.php">Sign Up</a></div>
<div class="restorani"> <a href="dodajlokal.php">DodajLokal</a></div>
<?php } ?>
</div>


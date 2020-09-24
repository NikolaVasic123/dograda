<?php
    require_once 'header.php';
    if(!$loggedin) 
    {
        die("<h3>You must <a href='login.php'>login</a> first to see the content of this page.</h3></div></body></html>");
    }
    $result = $lresult=$error=$lusername = $lemail = $ltip = $lmesto = $lslika = "";
    $errorUsername=$errorEmail=$errorTip=$errorMesto="";
    if($tip != "") {
        $lresult = queryMysql("SELECT * FROM lokali WHERE id = $id");
    }else{
        $result = queryMysql("SELECT * FROM users WHERE id = $id");
    }
    if( $lresult != ""){
        if($lresult->num_rows > 0)
        {
            $row = $lresult->fetch_assoc();
            $lusername = $row['username'];
            $lemail = $row['email'];
            $ltip = $row['tip'];
            $lmesto = $row['mesta'];
        }
    }else{
        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $lusername = $row['username'];
            $lemail = $row['email'];
        }
    }
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        if( $result != ""){
            if(!empty($_POST['username']))
        {
            $lusername = sanitizeString($_POST['username']);
        }
        else
        {
            $errorUsername = "First name cannot be left blank.";
        }
        if(empty($_POST['email'])) // Da li je vrednost elementa prazna?
        {
            $errorEmail = "Email field cannot be left blank.";
        }
        else 
        {
            $lemail = sanitizeString($_POST['email']);
            // check if e-mail adress is well-formed
            if(!filter_var($lemail, FILTER_VALIDATE_EMAIL))
            {
                $errorEmail = "Invalid email format.";
                $lemail = "";
            }
        }
        if($errorUsername == "" && $errorEmail == ""){
            queryMysql("UPDATE users
            SET username = '$lusername',
                email = '$lemail'
                WHERE id = $id"
                );
        }
    }else{
        if(!empty($_POST['username']))
        {
            $lusername = sanitizeString($_POST['username']);
        }
        else
        {
            $errorUsername = "First name cannot be left blank.";
        }
        if(empty($_POST['email'])) // Da li je vrednost elementa prazna?
        {
            $errorEmail = "Email field cannot be left blank.";
        }
        else 
        {
            $lemail = sanitizeString($_POST['email']);
            // check if e-mail adress is well-formed
            if(!filter_var($lemail, FILTER_VALIDATE_EMAIL))
            {
                $errorEmail = "Invalid email format.";
                $lemail = "";
            }
        }
        if(!empty($_POST['tip']))
        {
            $ltip = sanitizeString($_POST['tip']);
        }
        else 
        {
            $errorTip = "You mush choose one tipe.";
        }
        if(!empty($_POST['mesto']))
        {
            $lmesto = sanitizeString($_POST['mesto']);
        }
        else 
        {
            $errorMesto = "You mush enter number of place.";
        }
        if($errorUsername == "" && $errorEmail == "" && $errorMesto == "" && $errorTip == ""){
            queryMysql("UPDATE lokali
            SET username = '$lusername',
                email = '$lemail',
                tip = '$ltip',
                mesta = '$lmesto'
                WHERE id = $id"
                );
            }
    }
}


?>

<?php  if($tip != "") { ?>
<div id="content">
    <h2>Promeni profil lokala</h2>
<div class="error">
     <?php echo $errorEmail ?><?php echo $errorUsername ?><?php echo $errorMesto ?><?php echo $errorTip ?>
</div>
<div class="prijava">
    <form action="edit.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" placeholder="Your username...">
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" placeholder="Email"><br>
        <label for="tip">Tip Objekta:</label>
        <select name="tip" id="tip">
            <option value="" >--Choose--</option>
            <option value="k">Kafic</option>
            <option value="r">Restoran</option>
        </select><br><br>
          <label for="mesto">Broj mesta (1 - 150):</label>
        <input type="number" id="mesto" name="mesto" placeholder="1" min="1" max="150"><br><br>
        <br>
        <input type="submit" value="Edit">
    </form> 


</div></div>
<?php } else { ?>
    <div id="content">
    <h2>Promeni profil</h2>
<div class="error">
     <?php echo $error; ?>
</div>
<div class="prijava">
    <form action="edit.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" placeholder="Your username...">
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" placeholder="Email"><br>
        <br>
        <input type="submit" value="Edit">
    </form> 
</div></div>
<?php } ?>
<?php
    require_once "footer.php";
?>
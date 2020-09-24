<?php 
   require_once "header.php";
    $error = '';
    
   // Ako smo dosli na stranicu POST metodom, sakupiti podatke i izvrsiti proveru
   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
       $username = $connection->real_escape_string($_POST['username']);
       $password = $connection->real_escape_string($_POST['password']);
       if($username == "" || $password == "")
       {
           $error = "Username and/or password cannot be left blank";
       }
       else 
       {
           $result = queryMysql("SELECT * FROM users WHERE username='$username'");
           $lresult= queryMysql("SELECT * FROM lokali WHERE username='$username'");
           if($connection->error) 
           {
               $error = "Error in query: $connection->error";
           }
           else 
           {
               if($result->num_rows == 0)
               {
                   $error = "Username doesn't exist - please sign up first!";
               }
               else
               {
                   // Postoji korisnik u bazi sa zadatim username-mom
                   $row = $result->fetch_assoc(); // Dohvata trenutni red i vraca rezultat kao asocijativni niz
                   
                   if(!password_verify($password, $row['password']))
                   {
                       $error = "Passwords don't match - please try annother password!";
                   }
                   else 
                   {
                       $_SESSION['id'] = $row['id'];
                       $_SESSION['username'] = $row['username'];
                       header('Location: index.php');
                   }
               }
               if($lresult->num_rows == 0)
               {
                   $error = "Username doesn't exist - please create one first!";
               }
               else
               {
                  
                   $row = $lresult->fetch_assoc(); 
                   
                   if(!password_verify($password, $row['password']))
                   {
                       $error = "Passwords don't match - please try annother password!";
                   }
                   else 
                   {
                       $_SESSION['id'] = $row['id'];
                       $_SESSION['username'] = $row['username'];
                       $_SESSION['tip'] = $row['tip'];
                      
                      header('Location: index.php');
                   }
                }
           }
       }
   }
?>


<div id="content">
<h2>Prijavite se</h2>
<div class="prijava">
<div class="error">
            <?php echo $error; ?>
    </div>
<form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Your username...">
            <br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Your password...">
            <br>
            <input type="submit" value="Login">
 </form>
</div>
</div>

<?php
    require_once "footer.php";
?>
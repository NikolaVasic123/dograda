<?php 
   require_once "header.php";
   $email=$error = "";
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $connection->real_escape_string($_POST['username']);
        $password = $connection->real_escape_string($_POST['password']);
        $email = sanitizeString($_POST['email']);
        // check if e-mail adress is well-formed
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $email = "";
        }
        if($username == "" || $password == "" || $email == "") {
            $error = "All fields are required!";
        }
        else {
            $result = queryMysql("SELECT * FROM users WHERE username = '$username'");
            // $result - rezultat izvrsenja upita
            if($result->num_rows > 0) {
                // Korisnik sa ovim usernamemom vec postoji!
                $error = "That username is taken - please choose another one!";
            }
            else {
                // Upis novog korisnika
                $codedPassword = PASSWORD_HASH($password, PASSWORD_DEFAULT);
                queryMysql("INSERT INTO users(username, password, email) 
                    VALUES('$username', '$codedPassword', '$email')");
                header("Location: login.php");
            }
        }
    }
?>

<div id="content">
    <h2>Kreirajte novi nalog</h2>
<div class="prijava">
<div class="error">
    <?php echo $error; ?>
 </div>
<div id="info">
</div>
<form action="signup.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" 
                placeholder="Your username..."
                onBlur="checkUser(this)">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Your password...">
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" placeholder="Email">
            <br>
            <input type="submit" value="Sign up!">   
</form>
<script>
    function checkUser(inp)
    {
        var username = inp.value;
        if(username == '') {
            document.getElementById('info').innerHTML = "";
            return;
        }

        // AJAX request
        var params = "username=" + username;
        var request = ajaxRequest();
        if(request !== false) {
            request.open("POST", "checkuser.php", true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.setRequestHeader("Content-length", params.length);
            request.setRequestHeader("Connection", "close");

            request.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200)
                {
                    document.getElementById('info').innerHTML = this.responseText;
                }
            }

            request.send(params);
        }
    }
    function ajaxRequest()
{
    try {
        var request = new XMLHttpRequest();
    }
    catch(e1) {
        try {
            request = new ActiveXObject("Msxm12.XMLHTTP");
        }
        catch(e2) {
            try {
                request = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(e3) {
                request = false;
            }
        }
    }
    return request;
}
</script>
</div>
</div>

<?php
    require_once "footer.php";
?>
<?php 
   require_once "header.php";
   $error="";
   $lusername = $lemail = $ltip = $lmesto = $lslika = $vreme= "";
   if($_SERVER["REQUEST_METHOD"] == "POST") {
    $lusername = $connection->real_escape_string($_POST['username']);
    $lpassword = $connection->real_escape_string($_POST['password']);
    $lemail = sanitizeString($_POST['email']);
    $ltip = $connection->real_escape_string($_POST['tip']);
    $lmesto = $connection->real_escape_string($_POST['mesto']);
    $vreme = $connection->real_escape_string($_POST['vreme']);
    if(!filter_var($lemail, FILTER_VALIDATE_EMAIL))
    {
        $lemail = "";
    }
    if($lusername == "" || $lpassword == "" || $lemail == ""|| $ltip == ""|| $lmesto == ""|| $vreme == "") {
        $error = "All fields are required!";
    }
    else {
        $result = queryMysql("SELECT * FROM users WHERE username = '$lusername'");
        $lresult = queryMysql("SELECT * FROM lokali WHERE username = '$lusername'");
        // $result - rezultat izvrsenja upita
        if($result->num_rows > 0) {
            // Korisnik sa ovim usernamemom vec postoji!
            $error = "That username is taken - please choose another one!";
        }
        else if($lresult->num_rows > 0){
            $error = "That username is taken - please choose another one!";
        }else{
            // Upis novog korisnika
            $lcodedPassword = PASSWORD_HASH($lpassword, PASSWORD_DEFAULT);
            queryMysql("INSERT INTO lokali(username, password, email, tip, mesta, slobodnamesta,vreme) 
                VALUES('$lusername', '$lcodedPassword', '$lemail', '$ltip', '$lmesto', '$lmesto', '$vreme')");
            header("Location: login.php");
        }
    }
}
 // Da li je korisnik poslao slicicu
 if(isset($_FILES['image']['name']) && !empty($_FILES['image']['name']))
 {
     // Da li postoji folder za profilne slicice, i ako ne postoji, napravi ga
     if(!file_exists('profile_images/'))
     {
         mkdir('profile_images/', 0777, true);
     }

     // Svaki korisnik ima svoju sliku u formatu id.jpg
     $saveto = "profile_images/$lusername.jpg";

     // Prebacuje se slicica iz privremene lokacije u lokaciju u folderu projekta
     move_uploaded_file($_FILES['image']['tmp_name'], $saveto);

     // Redimenzioniranje slicice:
     // 1) Provera ekstenzije datoteke i kreiranje nove slike na osnovu poslate iz forme
     $typeok = true;
     switch($_FILES['image']['type'])
     {
         case "image/gif":
             $src = imagecreatefromgif($saveto);
             break;
         case "image/jpeg":
         case "image/jpg":
             $src = imagecreatefromjpeg($saveto);
             break;
         case "image/png":
             $src = imagecreatefrompng($saveto);
             break;
         default:
             $typeok = false;
     }

     if(!$typeok)
     {
         $imageError = "Allowed types for profile photo are: gif/jpeg/jpg/png!";
     }
     else
     {
         // 2) Menjamo dimenzije nove slike
         list($w, $h) = getimagesize($saveto);

         $max = 200;
         $tw = $w;
         $th = $h;
         if($w > $h && $w > $max)
         {
             $th = $max * $h / $w;
             $tw = $max;
         }
         elseif($h > $w && $h > $max)
         {
             $tw = $max * $w / $h;
             $th = $max;
         }
         else
         {
             $th = $tw = $max;
         }

         // 3) Kreiranje nove slicice sa zadatim dimenzijama ($tw x $th)
         $tmp = imagecreatetruecolor($tw, $th);
         imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
         imageconvolution($tmp, array(array(-1, -1, -1), array(-1, 16, -1),
             array(-1, -1, -1)), 8, 0);
         imagejpeg($tmp, $saveto);
         imagedestroy($src);
         imagedestroy($tmp);
     }

 }
   ?>


<div id="content">
    <h2>Dodajte Vaš ugostiteljski objekat</h2>
<div class="prijava">
<div class="error">
     <?php echo $error; ?>
</div>
<div id="info">
</div>
    <form action="dodajlokal.php" method="POST" enctype="multipart/form-data">
    <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Your username..."
            onBlur="checkUser(this)">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Your password...">
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
            <label for="image">Profil photo:</label>
            <input type="file" name="image" id="image">
            <br><br>
            <label for="vreme">Radno vreme:</label>
            <input type="text" name="vreme" id="vreme" placeholder="00:00 - 24:00"><br>
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
        var params = "lusername=" + username;
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
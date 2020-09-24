<?php
    require_once "header.php";
    if(!$restoran) 
    {
        die("<h3>You must <a href='login.php'>login</a> first to see the content of this page.</h3></div></body></html>");
    }
    $brojSmesta=queryMysql("SELECT slobodnamesta FROM lokali WHERE username = '$user'");
    
    $brojmesta=$brojSmesta->fetch_assoc();
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $br= $connection->real_escape_string($_POST['mesto']);
        $brojmesta['slobodnamesta'] =  $brojmesta['slobodnamesta'] - $br;
        $MESTA=$brojmesta['slobodnamesta'];
        var_dump($MESTA);
        queryMysql("UPDATE lokali
        SET slobodnamesta = '$MESTA'
        WHERE username = '$user'"
            );
    }
    
?>
<div id="content">
<h2>Unesite broj pristiglih ljudi</h2>
<div class="prijava">
<form action="brojmesta.php" method="POST">
<label for="mesto">Broj novih ljudi (-150 do 150):</label>
<input type="number" id="mesto" name="mesto" placeholder="<?php echo $brojmesta['slobodnamesta']; ?>" min="-150" max="150"><br><br>
<input type="submit" value="Edit">
</form>

</div></div>

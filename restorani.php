<?php
    require_once "header.php";
   
?>


<div id="content">
<h2>Kafići i Restorani</h2>
<div class="restoranii">
        <select name="tip" id="tip">
            <option value="" >--Choose--</option>
            <option value="k">Kafic</option>
            <option value="r">Restoran</option>
         </select><br><br>
<?php
    $result = queryMysql("SELECT * FROM lokali");
    while($row = $result->fetch_assoc()){
        $username=$row['username'];
        echo "<div class='p'>";
        if(file_exists("profile_images/$username.jpg"))
        {
            echo "<img src='profile_images/$username.jpg'>";
        }
        echo "<div class='pi'>";
       echo"<b>"; echo $row['username']; echo "</b>";
        echo "<br>";
        //echo $row['email'];
        echo "<br>";
        echo "Radno vreme:";echo $row['vreme'];  
        echo "<br>";
        echo $row['mesta']; echo "["; echo $row['slobodnamesta']; echo "]";
        
        
        echo "</div>";
        echo "</div>";
    }
?>
</div></div>


<?php
    require_once "footer.php";
?>
<?php
include './mainfn.php';
//Check table update by create Date
$result = mysqli_query($link, "SHOW TABLES");
while($table = mysqli_fetch_array($result)){  
    $result_field = mysqli_query($link, "SHOW COLUMNS FROM `$table[0]` LIKE 'CreateDate'");
    $exists = (mysqli_num_rows($result_field))?TRUE:FALSE;
    if($exists) {
        echo $table[0];
        echo " -> ";
        $result_date = mysqli_query($link, "SELECT MAX(`CreateDate`), MAX(`UpdateDate`) FROM `$table[0]`");
        $date = mysqli_fetch_array($result_date);
        if($date[0]=="0000-00-00 00:00:00")$date[0]="";
        if($date[1]=="0000-00-00 00:00:00")$date[1]="";
        echo $date[0].", ".$date[1];
        echo "<br>";
    }
}

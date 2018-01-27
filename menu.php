<?php
include_once 'mainfn.php';
?>
<aside id="hoe-left-panel" hoe-position-type="absolute">

                    <ul class="nav panel-list thaikanit">


<?php
//Gen Main menu
$result_ad_menu = mysqli_query($link,"SELECT * FROM `ad_menu` WHERE `MenuGId` = '0'");
while($gmenu = mysqli_fetch_array($result_ad_menu)){
    $gMenuId = $gmenu['MenuId'];
    $gMenuName = $gmenu['MenuName'];
    $gMenuFilename = $gmenu['MenuFilename'];
    $gMenuClass = $gmenu['MenuClass'];  
    $hoe = '';
    
    //Check Active
    if($menuactive==$gMenuId){
        $active = 'active';
    }else{
        $active = '';
    } 
    
    //Gen Sub ด้วย ad_menu และ permission
    //Find submenu    
    $sql = "SELECT `ad_menu`.`MenuName`, `ad_menu`.`MenuFilename` "
            . "FROM `ad_menu` INNER JOIN `ad_permission` ON  `ad_menu`.`MenuId` = `ad_permission`.`MenuId` "
            . "WHERE `UserId` LIKE '$UserId' AND `MenuGId` LIKE '$gMenuId' AND `PerStatus` = '1' ORDER BY `ad_permission`.`MenuId`";
    $resultsub = mysqli_query($link,$sql); // Must join ad_permission
    $num = mysqli_num_rows($resultsub);
    //ถ้ามีเมนูย่อยหรือไม่
    if($num!=0){
        $gMenuFilename = 'javascript:void(0)';
        $hoe = 'hoe-has-menu';
    }
    
?>
                        <li class="<?php echo $hoe." ".$active; ?>">
                            <a href="<?php echo $gMenuFilename; ?>">
                                <i class="<?php echo $gMenuClass; ?>"></i>
                                <span class="menu-text"><?php echo $gMenuName; ?></span>
                            </a>
                            <?php
                            if($num!=0){
                                echo "<ul class=\"hoe-sub-menu\">";
                                
                                while($menu = mysqli_fetch_array($resultsub)){                               
                                $MenuName = $menu['MenuName'];
                                $MenuFilename = $menu['MenuFilename'];

                                echo "<li>";
                                    echo "<a href=\"".$MenuFilename."\">";
                                    echo "<span class=\"menu-text\">".$MenuName."</span>";
                                    echo "</a>";
                                echo "</li>";
                                }
                                
                                echo "</ul>";
                            }
                            
                            
                            ?>
                        </li>                        
<?php } ?>
                    </ul>
</aside><!--aside left menu end-->
<?php
    $UserId = $_SESSION['UserId'];
    $UserFullName = $_SESSION['UserFullName'];
    $BId = $_SESSION['BId'];
    $avtar = "profile/imgprofile/".$UserId.".jpg";
    
    $result_rank = mysqli_query($link, "SELECT * FROM `ad_user` WHERE `UserId` = '$UserId'");
    $rank = mysqli_fetch_array($result_rank);
?>

<header id="hoe-header" hoe-lpanel-effect="shrink">
    <div class="hoe-left-header">
        <a href="index.php"><?php echo CValue($link, 'programname'); ?></a>
        <span class="hoe-sidebar-toggle"><a href="#"></a></span>
    </div>

    <div class="hoe-right-header" hoe-position-type="relative">
        <span class="hoe-sidebar-toggle"><a href="#"></a></span>
        <ul class="left-navbar">
            <li>
                <div class="top-search hidden-xs">
                    <form method="post" action="search.php">
                        <input type="text" class="form-control" name="search" placeholder="บ้านเลขที่หรือเลขที่แปลง" style="font-family: tahoma" required value="<?php echo @$_SESSION['search']; ?>">
                        <i class="ion-search"></i>
                    </form>

                </div>
            </li>

        </ul>
        <ul class="right-navbar navbar-right">
            <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle"><img src="assets/images/location.png"> <?php echo BName($link, $BId); ?></a>
                <ul class="dropdown-menu dropdown-menu-scale lang-dropdown">                                
                    <?php
                    $result_branch = mysqli_query($link, "SELECT `a`.`BId`, `a`.`BName` FROM `branch` AS `a` "
                            . "INNER JOIN `branch_permission` AS `b` "
                            . "ON `a`.`BId` = `b`.`BId` "
                            . "WHERE `b`.`UserId` = '$UserId' "
                            . "AND `b`.`PerStatus` = '1'"
                            . "ORDER BY `a`.`BName`");
                    while($branch = mysqli_fetch_array($result_branch)){
                        echo "<li><a href=\"switchbranch.php?BId=".$branch[0]."\"><i class=\"fa fa-map-marker\"></i> ".$branch[1]."</a></li>";
                    }
                    ?>
                </ul>
            </li>

            <li class="dropdown thaikanit">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle"><img src="assets/images/th.png" alt=""> ภาษาไทย</a>
                <ul class="dropdown-menu dropdown-menu-scale lang-dropdown">
                    <li><a href="#"><img src="assets/images/th.png" alt=""> ภาษาไทย </a></li>
                    <li><a href="#"><img src="assets/images/us.png" alt=""> English </a></li>
                    <li><a href="#"><img src="assets/images/mm.png" alt=""> Myanmar </a></li>
                    <li><a href="#"><img src="assets/images/laos.png" alt=""> Laos </a></li>
                </ul>
            </li>
            <li class="dropdown thaikanit">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle"><img src="<?php echo $avtar; ?>" alt="" width="32" class="img-circle"><?php echo $UserFullName; ?> <img src="ranking/<?php echo $rank['Rank']; ?>.png" title="ระดับความเชี่ยวชาญ <?php echo $rank['Rank']; ?>"></a>
                <ul class="dropdown-menu dropdown-menu-scale user-dropdown">
                    <li><a href="profile/profile.php"><i class="ion-person"></i> โปรไฟล์ </a></li>
                    <li><a href="logout.php"><i class="ion-log-out"></i> ออกจากระบบ </a></li>
                </ul>
            </li>

            <li class="dropdown" id="notification">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle"> <i class="ion-ios-bell-outline"></i></a>
                <ul class="dropdown-menu dropdown-menu-scale lg-dropdown notifications">
                    <li> <p>ยังไม่มีรายการแจ้งเตือน </p></li>

                </ul>
            </li>

        </ul>
    </div>
</header>

      
        
    <script type="text/javascript">

            $(document).ready(function () {
  
            
            //---------ส่วนแจ้งเตือน----------------------
            $(function(){
                setInterval(function(){ // เขียนฟังก์ชัน javascript ให้ทำงานทุก ๆ 30 วินาที
                    // 1 วินาที่ เท่า 1000
                    // คำสั่งที่ต้องการให้ทำงาน ทุก ๆ 3 วินาที
                    var getData=$.ajax({ // ใช้ ajax ด้วย jQuery ดึงข้อมูลจากฐานข้อมูล
                            url:"ajax_notification.php",
                            data:"rev=1",
                            async:false,
                            success:function(getData){
                                $("li#notification").html(getData); // ส่วนที่ 3 นำข้อมูลมาแสดง
                            }
                    }).responseText;
                },3000);    
            });
            
            
            
        });
          
         
              function readall () {
                var getData=$.ajax({ // ใช้ ajax ด้วย jQuery ดึงข้อมูลจากฐานข้อมูล
                            url:"ajax_notification.php",
                            data:"readall=1",
                            async:false,
                            success:function(getData){
                                $("li#notification").html(getData); // ส่วนที่ 3 นำข้อมูลมาแสดง
                            }
                }).responseText   
              }
               
             


</script>
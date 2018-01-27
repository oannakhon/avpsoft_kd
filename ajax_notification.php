<?php
session_start();
include './mainfn.php';
if(isset($_GET['readall'])){
    if($_GET['readall']==1){
        mysqli_query($link, "UPDATE `notification` SET"
                . "`NoStatus` = '2' "
                . "WHERE `UserId` = '$_SESSION[UserId]' "
                . "AND `NoStatus` != '0'");
    }
}
$result = mysqli_query($link, "SELECT * FROM `notification` "
        . "WHERE `UserId` = '$_SESSION[UserId]'"
        . " AND `NoStatus` = '1'");
$num = mysqli_num_rows($result);


?>
<style>
    .scroll {
    width: 100px;
    height: 300px;
    overflow-x: hidden;
    overflow-y: scroll;
}
#style-4::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 2px rgba(0,0,0,0.3);
	background-color: #F5F5F5;
}

#style-4::-webkit-scrollbar
{
	width: 2px;
	background-color: #F5F5F5;
}

#style-4::-webkit-scrollbar-thumb
{
	background-color: #000000;
	border: 1px solid #555555;
}

</style>

    <a href="#" data-toggle="dropdown" class="dropdown-toggle"> <i class="ion-ios-bell-outline"></i>
        <?php
        if($num!=0){
        ?>
        <span class="label label-danger"><?php echo $num; ?></span>
        <?php } ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-scale lg-dropdown notifications scroll" id="style-4">
        <li> <p>มีรายการแจ้งเตือน <?php echo $num; ?> รายการ <a href="#" onclick="return readall();"> อ่านทั้งหมด</a></p></li>
        <?php 
        $result2 = mysqli_query($link, "SELECT * FROM `notification` WHERE `UserId` = '$_SESSION[UserId]' AND `NoStatus` != '0' ORDER BY `id` DESC LIMIT 30 ");
        while ($no = mysqli_fetch_array($result2)){
            if($no['NoStatus']=="1"){
                $unread = "unread-notifications";
            }else{
                $unread = "";
            }
        ?>
        <li class="<?php echo $unread; ?>">
            <a href="notification_update.php?id=<?php echo $no['id']; ?>&url=<?php echo base64_encode($no['NoLink']); ?>">
                <i class="<?php echo $no['NoIcon']; ?> pull-right"></i>
                <span class="line"><?php echo $no['NoText']; ?></span>
                <span class="small-line"><?php echo facebook_time_ago($no['NoDate']); ?></span>
            </a>
        </li>
        
        <?php } ?>

    </ul>


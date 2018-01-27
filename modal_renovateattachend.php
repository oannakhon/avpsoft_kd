<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];

if(isset($_POST["submit"])){
    $IFDate = date('Y-m-d');
    $RVId = $_POST['RVId'];
    $id = $_POST['id'];
    //Check folder exist ถ้าไม่มี folder ให้ make folder
    if (!file_exists("attachments/".$IFDate)) {
    mkdir("attachments/".$IFDate, 0777);
    }
    
    $name = $_FILES["fileToUpload"]["name"];
    $ext = end((explode(".", $name))); # extra () to prevent notice

    
    $IFName = time().".".$ext; 
    $target_dir = "attachments/".$IFDate."/";
    $target_file = $target_dir . basename($IFName);
    $ACName = basename($IFName);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        
        //เพิ่มลง ฐานข้อมูล-------------------------------
        mysqli_query($link, "INSERT INTO `attachments` (`RefId`, `ACType`, `BId`, `ACName`, `ACStatus`, `CreateBy`, `CreateDate`) "
                . "VALUES ('$RVId','attachend','$BId','$ACName','1','$CreateBy', '$CreateDate')");
        
     
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=unitaftersale.php?id=$id#renovation\">";
    exit;
}

if(isset($_GET['RVId'])){
    $id = $_GET['id'];
    $RVId = $_GET['RVId'];
}


?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo "แนบไฟล์คืนเงินประกัน"; ?></h4>
        </div>          
        <div class="modal-body">
            <?php 
            $result_attachments = mysqli_query($link, "SELECT * FROM `attachments` WHERE `RefId` = '$RVId' AND `BId` = '$BId' AND `ACType` = 'attachend'");
            while ($attachments = mysqli_fetch_array($result_attachments)){
                $ACName = $attachments['ACName'];
                
                $Date = explode(" ", $attachments['CreateDate']);
            
            ?>
            <div class="row">
                <div class="col-lg-2 text-right"><a href="attachments/<?php echo $Date[0]; ?>/<?php echo $ACName; ?>" target="_blank" ><i class="fa fa-file-image-o" aria-hidden="true"></i></a></div>
                <div class="col-lg-10"><a href="attachments/<?php echo $Date[0]; ?>/<?php echo $ACName; ?>" target="_blank"><?php echo $ACName; ?></a></div>
            </div>
            <?php } ?>
            
            <br>
            <hr>
            <form action="modal_renovateattachend.php" method="post" enctype="multipart/form-data" >
                
                
                กรุณาเลือกไฟล์ที่ต้องการอัพโหลด
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="RVId" value="<?php echo $RVId; ?>">
                <input type="file" id="image-file" name="fileToUpload" id="fileToUpload" accept=".jpg,.pdf,.jpeg,.png" >
                <input type="submit" class="btn btn-info" value="อัพโหลดไฟล์" name="submit">
                
                <script type="text/javascript">
                    $('#image-file').bind('change', function() {
                        var FileSize = this.files[0].size / 1024 / 1024; // in MB
                            if (FileSize > 5) {
                                alert('ไฟล์ขนาดเกิน 5 MB');
                                $('#image-file').val(''); //for clearing with Jquery
                            }
                        
                    });
                </script>
                
            </form>
        </div>
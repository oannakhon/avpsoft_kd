<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
$BId = $_SESSION['BId'];
$imgdir = "upload/";   


if(isset($_POST['save_image'])){
    $IFDate = date('Y-m-d');
    $IAId = $_POST['IAId'];
    $SId = $_POST['SId'];
    
    
    //Check folder exist ถ้าไม่มี folder ให้ make folder
    if (!file_exists("upload/".$IFDate)) {
    mkdir("upload/".$IFDate, 0777);
    }
    
    $name = $_FILES['fileupload']['name'];
    $fn = count($name);
    
    for($i=0;$i<$fn;$i++){
        $IFName = time().$i.".jpg"; 
        $fileupload=$_FILES['fileupload']['tmp_name'][$i];
        $fileupload_name=$_FILES['fileupload']['name'][$i];
        $fileupload_size=$_FILES['fileupload']['size'][$i];
        if($fileupload_size>0){
        $filetemp = "upload/".$fileupload_name;
        copy($fileupload,$filetemp); //อัพโหลดตรงนี้ 
        $filenew = "upload/".$IFDate."/".$IFName; //ชื่อที่อยู่ใหม่                
        $size=GetimageSize($filetemp); // หาขนาด เป็น Array                
        if($size[0]>$size[1]){$width=800;}else{$width=600;} // เช็คแนวตั้งแนวนอน size[0]=นอน, size[1]
        $height=round($width*$size[1]/$size[0]);//หาความสูง                
        $images_orig = ImageCreateFromJPEG($filetemp);
        $photoX = ImagesX($images_orig);
        $photoY = ImagesY($images_orig);
        $images_fin = ImageCreateTrueColor($width, $height);                
        ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);                
        ImageJPEG($images_fin,$filenew); //ได้ไฟล์ใหม่
        ImageDestroy($images_orig);
        ImageDestroy($images_fin);
          
        unlink($filetemp); //ลบ temp    
        
    }// จบ upload    

         mysqli_query($link, "INSERT INTO `imgfile` (`IAId`, `IFName`, `IFDate`, `IFStatus`, `CreateBy`, `CreateDate`) "
                . "VALUES ('$IAId', '$IFName', '$IFDate', '1', '$CreateBy', '$CreateDate')");  
    }
  
    
    
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=service.php?SId=".$SId."\" >"; 
    exit;
}


if(isset($_GET['delete'])) { //ลบ  DELETE   
    $id = $_GET['delete'];
    mysqli_query($link, "UPDATE `imgfile` SET  "
            . "`IFStatus` ='0', "
            . "`UpdateBy`='$UpdateBy', "
            . "`UpdateDate`='$UpdateDate' "
            . "WHERE `id` ='$id '"); 
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=service.php\" >"; 
    exit;
}

if (isset($_GET['SSId'])){
    $SSId = $_GET['SSId']; //RefId
    $SId = $_GET['SId'];
    
    $result_check = mysqli_query($link, "SELECT * FROM `imgalbum` "
            . "WHERE `RefId` = '$SSId' "
            . "AND `BId` = '$BId'");
    
    if(mysqli_num_rows($result_check)==0){    
        $IAId = newid($link, 22);
        $IADate = date("Y-m-d");
        mysqli_query($link, "INSERT INTO `imgalbum` (`IAId` ,`RefId` ,`BId` ,`IADate` ,`CreateBy` ,`CreateDate` )"
                . "VALUES ('$IAId','$SSId','$BId','$IADate','$CreateBy','$CreateDate')");
    }else{
        $ia = mysqli_fetch_array($result_check);
        $IAId = $ia['IAId'];                
    }
}


?>
<head>
    <!--คลิกแล้วภาพใหญ่-->   
<link rel="stylesheet" href="magnific-popup/magnific-popup.css"> 
<script src="magnific-popup/jquery.magnific-popup.js"></script>
<script>
$(document).ready(function() {
	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                },
		image: {
			verticalFit: true
		}		
	});        

        
});
</script>
<!--จบคลิกแล้วภาพใหญ่--> 

    <!--ปุ่มในรูปภาพ-->
<style>        

.buttondel {
    position:absolute;
    bottom:20px;
    right:20px;
}

.rotate {
    position:absolute;
    bottom:20px;
    right:70px;
}


</style><!-- จบปุ่มในรูปภาพ-->
</head>
 <?php 
 
$result_servicesub = mysqli_query($link, "SELECT * FROM `servicesub` "
. "WHERE `SSId` = '$SSId' "
. "AND `BId` = '$BId' "
. "AND `SSStatus` != '0'");
while ($ss = mysqli_fetch_array($result_servicesub)){
$SSName = $ss['SSName'];
}
 
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $SSName;  ?></h4>
        </div>  
        <div class="modal-body">  
            <div class="row">
                    <div class="col-md-12">
                    <div id="showData">
                    <?php
                    $sql_image = "SELECT * FROM `imgfile` WHERE `IAId` LIKE '$IAId' AND `IFStatus` LIKE 1 ORDER BY `id`";
                    $result_image = mysqli_query($link, $sql_image);
                    $sorta = 1; 
                    $sortb = 1; 
                    while($image = mysqli_fetch_array($result_image)){
                        $img_filename = $imgdir.$image['IFDate']."/".$image['IFName']."";
                        
                        if($sortb != 1 ){
                            $sorta = $sortb;
                        }
                        $sortb = $image['id'];
                    ?>
                   
                        <div id="img_container" style="margin: 5px">
                            <div class="col-md-4 text-center">
                            <a class="image-popup-vertical-fit" href="<?php echo $img_filename; ?>?time=<?php echo time(); ?>">
                            <img src="<?php echo $img_filename; ?>?time=<?php echo time(); ?>" class="img-thumbnail img-responsive" style="margin-bottom: 15px;"/>
                            </a>
                                <button type="button" class="buttondel btn-xs btn-danger" value="<?php echo $image['id']; ?>">
                                <span class="glyphicon glyphicon-trash"></span> ลบ</button>
                                
                                <button type="button" class="rotate btn-xs btn-success" value="<?php echo $image['id']; ?>">
                                <span class="glyphicon glyphicon-refresh"></span> หมุน</button>
                            </div>
                        </div>  
                    <?php }?> 
                
                        <br>
                        <script type="text/javascript">

                         $(document).ready(function(){ 

                                $(".rotate").click(function() {
                                    var rotate = $(this).attr("value");
                                    var IAId = "<?php echo $IAId; ?>";

                                    

                                    $.ajax({
                                        type:"post",
                                        url:"rotate_img.php",
                                        data:"rotate="+rotate+"&IAId="+IAId,

                                        success:function(getData){                    
                                            //เมื่อบันทึกรายการย่อย สำเร็จ                   
                                            $("#showData").html(getData);                     
                                        }
                                    });
                                    return false;
                                    });
                                    
                                    $(".buttondel").click(function() {
                                        //confirm
                                    var confirm1 = confirm('คุณต้องการลบรูปนี้หรือไม่?');
                                        if (confirm1) {
                                            var button = $(this).attr("value");
                                            var IAId = "<?php echo $IAId; ?>";



                                            $.ajax({
                                                type:"post",
                                                url:"delete_img.php",
                                                data:"button="+button+"&IAId="+IAId,

                                                success:function(getData){                    
                                                    //เมื่อบันทึกรายการย่อย สำเร็จ                   
                                                    $("#showData").html(getData);                     
                                                }
                                            });
                                            return false;
                                        } else {
                                          return false;
                                        }
                                    
                                    });

                                });
                        </script>
                    </div>
                    </div> 
                </div>
            
            

           
            <br>
            <form method="post" action="modal_imgalbum.php" enctype="multipart/form-data">
                <input type="hidden" name="SId" value="<?php echo $SId; ?> ">
                <div class="fileupload fileupload-new " data-provides="fileupload">
                    <input type="hidden" name="IAId" value="<?php echo $IAId; ?>" 
                    <div>
                        <span class="btn btn-success btn-file btn-primary btn-sm">
                            <span class="fileupload-new"><i class="fa fa-plus" aria-hidden="true"></i> เลือกภาพเพื่ออัพโหลด</span>
                            <input type="file" name="fileupload[]" accept="image/jpeg" multiple> 
                        </span>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-success fileupload-exists col-md-12 btn-lg" name="save_image"><span class="glyphicon glyphicon-upload"> อัพโหลด</span></button>
                    </div>
                <input class="btn btn-primary" style="margin-top: 5px" type="submit" name="save_image" value="บันทึก">
                
            </form>
        </div>


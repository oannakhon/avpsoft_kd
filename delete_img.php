<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include 'mainfn.php';
$IAId = $_POST['IAId'];
$delete = $_POST['button'];
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");

//-----------ลบ----------------------
    $id = $delete;
    mysqli_query($link, "UPDATE `imgfile` SET  "
            . "`IFStatus` ='0', "
            . "`UpdateBy`='$UpdateBy', "
            . "`UpdateDate`='$UpdateDate' "
            . "WHERE `id` ='$id '"); 

//-------------------------------------


$imgdir = "upload/";
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
</head>
                     
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
                    
                
                 
<?php
@session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");
//-------------newsave

//------รับค่ามาจากหน้าหลัก
    $PId = $_GET['PId'];
    $result_product = mysqli_query($link,"SELECT * FROM `product` WHERE `PId` = '$PId'");
    $product = mysqli_fetch_array($result_product);
?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">แสดงรายละเอียดสินค้า <?php echo $PId; ?></h4>
        </div>          
        <div class="modal-body">        
            <div class="col-sm-4"><img src="assets/images/product/noimg.jpg" width="100%"></div>
            <div class="col-sm-8">
                <div class="col-sm-3">บาร์โค้ด</div>
                <div class="col-sm-8"><?php echo $product['PCode']; ?></div><br>
                <div class="col-sm-3">ชื่อสินค้า</div>
                <div class="col-sm-8"><?php echo $product['PName']; ?></div><br>
                <div class="col-sm-3">รุ่น</div>
                <div class="col-sm-8"><?php echo $product['PModel']; ?></div><br>
                <div class="col-sm-3">กลุ่ม</div>
                <div class="col-sm-8"><?php echo PGName($link, $product['PGId']); ?></div><br>
                <div class="col-sm-3">ยี่ห้อ</div>
                <div class="col-sm-8"><?php echo BrandName($link, $product['BrandId']); ?></div><br>
                <div class="col-sm-3">ขนาด</div>
                <div class="col-sm-8"><?php echo $product['PSize']; ?></div><br>
                <div class="col-sm-3">สี</div>
                <div class="col-sm-8"><?php echo $product['PColor']; ?></div><br>
                <div class="col-sm-5">รายละเอียดอื่นๆ</div>
                <div class="col-sm-6"><?php echo $product['PDetail']; ?></div><br>
                <div class="col-sm-3">หมายเหตุ</div>
                <div class="col-sm-8"><?php echo $product['PNote']; ?></div><br>
            </div>
               
              
            &nbsp;           
        </div>
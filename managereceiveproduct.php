<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 3;

if(isset($_POST['savedoc'])){               
    $RPId = $_POST['RPId'];        
    $RPDate = datemysql($_POST['RPDate']); 
    $RPRefId = $_POST['RPRefId']; 
    $POId = $_POST['POId'];  
    $BId = trim($_SESSION['BId']);
    $RPDuedate = datemysql($_POST['RPDuedate']);
    $RPTotal = $_POST['RPTotal'];
    $RPNote = $_POST['RPNote'];
    $RPGrandtotal = $RPTotal;
    
    
    mysqli_query($link,"UPDATE `receiveproduct` SET "
            . "`RPDate` = '$RPDate', "
            . "`RPRefId` = '$RPRefId', "
            . "`POId` = '$POId', "
            . "`BId` = '$BId', "
            . "`RPDuedate` = '$RPDuedate', "
            . "`RPTotal` = '$RPTotal', "
            . "`RPNote` = '$RPNote', "
            . "`RPGrandtotal` = '$RPGrandtotal', "
            . "`RPStatus` = '1' "
            . "WHERE `RPId`= '$RPId' ");
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=receiveproduct.php\">"; 
    exit;
}

if(isset($_GET['del'])){
    $RPId = $_GET['del'];
    $key =  $_GET['key'];
    if(md5($RPId)==$key){
        mysqli_query($link, "UPDATE `receiveproduct` SET `RPStatus` = '0' WHERE `RPId` = '$RPId'");
        echo "<meta http-equiv=refresh CONTENT=\"0; URL=receiveproduct.php\">"; 
        exit; 
    }
}

if(isset($_GET['RPId'])){
    $RPId = $_GET['RPId'];
    //Check exist
    $result_receiveproduct = mysqli_query($link, "SELECT * FROM `receiveproduct` "
        . "WHERE `RPId` = '$RPId' "
            . "AND `RPStatus` NOT LIKE '0'");
    if(mysqli_num_rows($result_receiveproduct)!=1){
        echo "<meta http-equiv=refresh CONTENT=\"0; URL=receiveproduct.php\">"; 
        exit;  
    }else{
        $receiveproduct = mysqli_fetch_array($result_receiveproduct);
        $result_supplier = mysqli_query($link, "SELECT * FROM `supplier` WHERE `SuppId` = '$receiveproduct[SuppId]'");
        $supplier = mysqli_fetch_array($result_supplier);
        $RPDuedate = date ("d/m/Y", strtotime("+".$supplier['SuppCreditTerm']." day", strtotime($receiveproduct['RPDate'])));
        
    }
    
    
}else{
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=receiveproduct.php\">"; 
    exit; 
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>        
       <style>
        .ui-autocomplete-loading {
                background: white url("assets/jqueryui/images/ui-anim_basic_16x16.gif") right center no-repeat;
        }
        </style> 
    </head>
    <body hoe-navigation-type="vertical" hoe-nav-placement="left" theme-layout="wide-layout">

        <!--side navigation start-->
        <div id="hoeapp-wrapper" class="hoe-hide-lpanel" hoe-device-type="desktop">
            <?php include 'header.php'; ?>
            <div id="hoeapp-container" hoe-color-type="lpanel-bg7" hoe-lpanel-effect="shrink">
            <?php include 'menu.php'; ?>    


                <!--start main content-->
                <section id="main-content">
                    <div class="space-30"></div>
                    <div class="container">
                        <!--widget box row-->
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <header class="panel-heading">                                       
                                        <h2 class="panel-title">บันทึกรับสินค้า[<?php echo WName($link, $receiveproduct['WId']); ?>] เลขที่เอกสาร <?php echo $RPId; ?></h2>                                        
                                    </header>
                                    <div class="panel-body">
                                                                                  
                                        <form class="form-horizontal" role="form" method="post" action="managereceiveproduct.php">
                                            <input type="hidden" name="RPId" id="RPId" value="<?php echo $RPId; ?>">
                                            <input type="hidden" name="WId" id="WId" value="<?php echo $receiveproduct['WId']; ?>">
                                            <div class="form-group">
                                                <label class="col-lg-2 control-label">ชื่อซัพพลายเออร์</label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" value="<?php echo $supplier['SuppName']; ?>" readonly>
                                                </div>
                                                <label class="col-lg-2 control-label">วันที่รับสินค้า</label>
                                                <div class="col-lg-2">
                                                    <input type="text" class="form-control dateadd" name="RPDate" value="<?php echo viewdate($receiveproduct['RPDate']); ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 control-label">เลขที่ใบส่งของ</label>
                                                <div class="col-lg-2">
                                                    <input type="text" class="form-control" name="RPRefId"  value="<?php echo $receiveproduct['RPRefId']; ?>">
                                                </div>
                                                <label class="col-lg-2 control-label">เลขที่ใบสั่งซื้อ</label>
                                                <div class="col-lg-2">
                                                    <input type="text" class="form-control" name="POId" value="<?php echo $receiveproduct['POId']; ?>">
                                                </div>
                                                <label class="col-lg-2 control-label">กำหนดชำระเงิน</label>
                                                <div class="col-lg-2">
                                                    <input type="text" class="form-control dateadd" name="RPDuedate" value="<?php echo $RPDuedate; ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-lg-2 control-label">หมายเหตุ</label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" name="RPNote" value="<?php echo $receiveproduct['RPNote']; ?>">
                                                </div>
                                            </div>
                                            
                                            <div id="showData">
                                            <table class="table table-bordered table-hover" style="font-family: tahoma">
                                              <thead>
                                                <tr>
                                                  <th class="text-center" width='15%'>บาร์โค้ด</th>
                                                  <th class="text-center" >ชื่อสินค้า</th>
                                                  <th class="text-center" width='10%'>จำนวน</th>
                                                  <th class="text-center" width='12%'>ราคาต่อหน่วย</th>
                                                  <th class="text-center" width='10%'>จำนวนเงิน</th>
                                                  <th class="text-center" width='15%'>กระทำ</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <?php
                                                $sum=0;
                                                $result_receiveproductsub = mysqli_query($link, "SELECT * FROM `receiveproductsub` "
                                                    . "WHERE `RPId` = '$RPId' "
                                                        . "AND `RPSubStatus` = '1'");
                                                while($rps = mysqli_fetch_array($result_receiveproductsub)){
                                                    $result_product = mysqli_query($link, "SELECT * FROM `product` WHERE `PId` = '$rps[PId]'"); 
                                                    $product = mysqli_fetch_array($result_product);
                                                    
                                                    //Check Price
                                                    $alert = "";
                                                    $CreateDate = $rps['CreateDate'];
                                                    $result_chkprice = mysqli_query($link, "SELECT MAX(RPSubPU), `RPId` FROM `receiveproductsub` "
                                                            . "WHERE `PId` = '$rps[PId]' AND `CreateDate` < '$CreateDate' ");
                                                    $OldPrice = mysqli_fetch_array($result_chkprice);
                                                    if($OldPrice[0]>0){
                                                        
                                                        if($rps['RPSubPU']>$OldPrice[0]){
                                                            $alert = "<i class=\"fa fa-arrow-circle-up btn-danger\" title=\"".$OldPrice[0]." - ".$OldPrice[1]."\"></i> ";
                                                        }else{
                                                            $alert = "";
                                                        }
                                                    }
                                                    
                                                    
                                                    
                                                    //Check SN 
                                                    $result_receiveproductsubsn = mysqli_query($link, "SELECT * FROM `receiveproductsubsn` "
                                                            . "WHERE `RPSubId` = '$rps[RPSubId]' "
                                                            . "AND `RPSubSNName` LIKE '' ");
                                                    if(mysqli_num_rows($result_receiveproductsubsn)==0){
                                                        $sn_class = "btn-success";
                                                        
                                                    }else{
                                                        $sn_class = "btn-danger";
                                                       
                                                    }
                                                    $sum=$sum+$rps['RPSubPrice'];
                                                ?>
                                                  <tr>
                                                      <td class="text-center"><?php echo $product['PCode']; ?></td>
                                                      <td><?php echo $product['PName']; ?></td>
                                                      <td class="text-center"><?php echo $rps['RPSubNum']; ?></td>
                                                      <td class="text-right"><?php echo $alert." ".number_format($rps['RPSubPU'],2); ?></td>
                                                      <td class="text-right"><?php echo number_format($rps['RPSubPrice'],2); ?></td>
                                                      <td>
                                                          <a href="savereceiveproductsub.php?id=<?php echo $rps['id']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('คุณต้องการที่จะลบรายการนี้?')"><i class="fa fa-trash-o"></i></a>
                                                          <a href="modal_receiveproductsubsn.php?RPSubId=<?php echo $rps['RPSubId']; ?>" class="btn <?php echo $sn_class; ?> btn-xs" data-toggle="modal" data-target="#receiveproductsubsnModal">SN=<?php echo mysqli_num_rows($result_receiveproductsubsn); ?></a>
                                                      </td>
                                                  </tr>
                                                <?php } ?>
                                                  <tr>
                                                      
                                                      <td class="text-right" colspan="4">รวมทั้งสิ้น</td>
                                                      <td class="text-right">
                                                          <input type="hidden" name="RPTotal" value="<?php echo $sum; ?>">
                                                          <strong><?php echo number_format($sum); ?></strong></td>
                                                      <td>
                                                          &nbsp;
                                                      </td>
                                                  </tr>
                                                  </tbody>
                                            </table>
                                            <input type="hidden" name="chk_savedoc" id="chk_savedoc" value="<?php echo chkfullsn($link, $RPId); ?>" >     
                                            
                                                
                                            </div>
                                             <table class="table table-bordered" style="font-family: tahoma">
                                                
                                                <tr>
                                                    <td width='15%' class="text-right">เพิ่มรายการใหม่</td>
                                                      <td>
                                                          <input type="hidden" class="form-control" id="PId">
                                                          <input type="text" class="form-control" id="PName" autofocus>
                                                      </td>
                                                      <td width='10%'>
                                                          <input type="text" class="form-control" id="RPSubNum">
                                                      </td>
                                                      <td width='12%'>
                                                          <input type="text" class="form-control" id="RPSubPU">
                                                      </td>
                                                      <td width='10%'>
                                                          <input type="text" class="form-control" id="RPSubPrice" readonly>                                                     
                                                      </td>
                                                      <td width='15%'>
                                                        <button type="button" class="btn btn-primary btn-sm" title="บันทึก" id="save-product" name="save-product" disabled><i class="fa fa-floppy-o"></i></button>
                                                        <button type="button" class="btn btn-warning btn-sm" title="แก้ไข" id="edit-product"><i class="fa fa-pencil"></i></button>
                                                      </td>
                                                  </tr>
                                              </table>
                                            
                                            <div class="form-group">
                                                <div class="col-lg-4 col-lg-offset-8 text-right">
                                                    <?php
                                                    if($sum==0){
                                                        $key = md5($RPId);
                                                        $del = "managereceiveproduct.php?del=".$RPId."&key=".$key;
                                                    }else{
                                                        $del = "#";
                                                    }
                                                    ?>
                                                    <a href="receiveproduct.php" class="btn btn-default"><i class="fa fa-chevron-left" aria-hidden="true"></i> กลับหน้าหลัก</a>&nbsp;
                                                    &nbsp;<input type="submit" class="btn btn-primary" name="savedoc" value="&nbsp;&nbsp;บันทึกเอกสาร&nbsp;&nbsp;" id="savedoc" <?php if(chkfullsn($link, $RPId)==0) echo "disabled"; ?>>
                                                    &nbsp;&nbsp;<a href="<?php echo $del ?>" class="btn btn-danger">ลบเอกสารนี้</a>
                                                </div>                                            
                                            </div>
                                        </form>      
                                       
                                    </div>
                                </div>
                            </div>


                        </div>
                        
                        
                       
                        
                    </div><!--end container-->

                    <!--footer start-->
                    <div class="footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <span>&copy; Copyright 2016. AVP Enterprise Co.Ltd. ติดต่อผู้พัฒนาโปรแกรมได้ที่ Line Id: @avpenterp</span>
                            </div>
                        </div>
                    </div>
                    <!--footer end-->
                </section><!--end main content-->
            </div>
        </div><!--end wrapper-->
        
        <!-- receiveproductsubsnModal -->
  <div class="modal fade" id="receiveproductsubsnModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content"></div>
    </div>
  </div>

            

        
        
        <!--Date -->
        <script type="text/javascript" src="assets/js/moment-with-locales.js?v=1001"></script>
        <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js?v=1001"></script> 
        <script type="text/javascript" src="assets/jqueryui/jquery-ui.min.js?v=1001"></script>
        
        <script type="text/javascript">
        //--Clear Modal cache    
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });   
        $(function () {
            $('.dateadd').datetimepicker({
                format: 'DD/MM/YYYY',locale: 'th',locale: 'th'
            });
            
        });
         
        $(document).ready(function(){       
            //ส่วนของการค้นหาสินค้า
            $(function() { 
                $( "#PName" ).autocomplete({
                    source: "search_product.php",
                    minLength: 2,      
                    select: function( event, ui ) {
                        $( "#PId" ).val(ui.item.id);
                        $( "#RPSubNum" ).val(1); 
                         
                        var Amount = $('#RPSubNum').val();
                        var Price = $('#RPSubPU').val();
                        var total = parseInt(Amount) * parseInt(Price);        
                        $('#RPSubPrice').val(total);  
                        
                        $('#PName,#RPSubPrice').attr("disabled", "disabled");                         
                        $('#save-product').removeAttr('disabled');
                    }
                });
            });   
            
            //คำนวนเมื่อมีการแก้จำนวนหรือราคา
            $("#RPSubPU, #RPSubNum").keyup(function() {
                var Amount = $('#RPSubNum').val();
                var Price = $('#RPSubPU').val();

                var total = parseFloat(Amount) * parseFloat(Price);
                $('#RPSubPrice').val(total); 
            });
            
            
            /* ยกเลิกsubform //Clear form */
            $("#edit-product").click(function() {
                $( "#PId" ).val('');
                $( "#PName" ).val('');
                $( "#ProUnit" ).val('');
                $( "#RPSubNum" ).val('');
                $( "#RPSubPU" ).val('');
                $( "#RPSubPrice" ).val('');
                
                $('#PName,#ProUnit,#RPSubPrice').removeAttr('disabled');
                $("#save-product").attr("disabled", "disabled");
            });   
        });
        
        
        /* สำหรับบันทึกรายการย่อย*/
        $("#save-product").click(function() {
            var RPId = $("#RPId").val();
            var WId = $("#WId").val();
            var PId = $("#PId").val();
            var RPSubNum = $("#RPSubNum").val();
            var RPSubPU = $("#RPSubPU").val();
            var RPSubPrice = $("#RPSubPrice").val();

            $.ajax({
                type:"post",
                url:"savereceiveproductsub.php",
                data:"RPId="+RPId+"&WId="+WId+"&PId="+PId+"&RPSubNum="+RPSubNum+"&RPSubPU="+RPSubPU+"&RPSubPrice="+RPSubPrice,
                
                success:function(getData){                    
                    //เมื่อบันทึกรายการย่อย สำเร็จ                   
                    $("#showData").html(getData); 
                    //Clear form
                    $("#PId,#PName,#RPSubNum,#RPSubPU,#RPSubPrice" ).val('');
                    $('#PName').removeAttr('disabled');
                    $("#save-product").attr("disabled", "disabled"); 
                    //Disable ปุ่ม เพื่อรอให้ใส่ SN
                    $("#savedoc").attr("disabled", "disabled"); 
                    
                }
            });
            return false;
            });
        /* สำหรับบันทึกรายการย่อย*/     


        </script>      
</html>
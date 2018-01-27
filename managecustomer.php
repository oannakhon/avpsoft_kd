<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 4;
$CreateBy = $_SESSION['UserId'];
$CreateDate = date("Y-m-d H:i:s");
$UpdateBy = $_SESSION['UserId'];
$UpdateDate = date("Y-m-d H:i:s");



if(isset($_POST['txtID'])){
    $CCardId = trim($_POST['txtID']);
    //Check in database table customer
    $result_customer = mysqli_query($link,"SELECT `CId` FROM `customer` WHERE `CCardId` = '$CCardId'");
    if(mysqli_num_rows($result_customer)>0){
        //เจอข้อมูล
        $customer = mysqli_fetch_array($result_customer);
        echo "<meta http-equiv=refresh CONTENT=\"0; URL=managecustomer.php?CId=".$customer[0]."\">";
        exit;
    }else{
        //ถ้ายังไม่มี เพิ่มใหม่
        $CId = newid($link, 9);
        mysqli_query($link, "INSERT INTO `customer` (`CId`, `CCardId`, `CreateBy`, `CreateDate`) "
                . "VALUES ('$CId', '$CCardId', '$CreateBy', '$CreateDate')");
        echo "<meta http-equiv=refresh CONTENT=\"0; URL=managecustomer.php?CId=".$CId."\">";
        exit;
    }    
}

if(isset($_POST['general'])){
    $CId = $_GET['CId'];
    $CStatus = $_POST['CStatus'];
    $CCardDateExpire = datemysql($_POST['CCardDateExpire']);
    $CPreName = $_POST['CPreName'];
    $CName = $_POST['CName'];
    $CLastName = $_POST['CLastName'];
    $CNickName = $_POST['CNickName'];
    $CBirthDay = datemysql($_POST['CBirthDay']);
    $CGender = $_POST['CGender'];
    $CMaritalStatus = $_POST['CMaritalStatus'];
    $CMobile = $_POST['CMobile'];
    $CNote = $_POST['CNote'];
    
    mysqli_query($link,"UPDATE `customer` SET "
            . "`CStatus` = '$CStatus', "
            . "`CCardDateExpire` = '$CCardDateExpire', "
            . "`CPreName` = '$CPreName', "
            . "`CName` = '$CName', "
            . "`CLastName` = '$CLastName', "
            . "`CNickName` = '$CNickName', "
            . "`CBirthDay` = '$CBirthDay', "
            . "`CGender` = '$CGender', "
            . "`CMaritalStatus` = '$CMaritalStatus', "
            . "`CMobile` = '$CMobile', "
            . "`CNote` = '$CNote' "
            . "WHERE `CId` = '$CId'");   
    
}

if(isset($_GET['CId'])){
    $CId = $_GET['CId'];
    $result_customer = mysqli_query($link,"SELECT * FROM `customer` WHERE `CId` = '$CId'");
    if(mysqli_num_rows($result_customer)==1){
        $customer = mysqli_fetch_array($result_customer);
    }else{
        //location
        echo "<meta http-equiv=refresh CONTENT=\"0; URL=index.php\">";
        exit;
    }
}else{
    //location 
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=index.php\">";
    exit;    
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>
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
                                        <h2 class="panel-title">จัดการลูกค้า</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div>
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><i class="ion-person"></i> ทั่วไป</a></li>
                                                <li><a href="#address" aria-controls="address" role="tab" data-toggle="tab"><i class="ion-location"></i> ที่อยู่ </a></li>
                                                <li><a href="#work" aria-controls="work" role="tab" data-toggle="tab"><i class="fa fa-building" aria-hidden="true"></i> ที่ทำงาน </a></li>
                                            </ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="general">
                                                    <div class="space-10"></div>  
                                                     
                                                    <form class="form-horizontal" role="form" method="post" action="managecustomer.php?CId=<?php echo $CId; ?>">
                                                   
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">สถานะ </label>
                                                    <div class="col-sm-3">
                                                        <select class="form-control" name="CStatus">
                                                            <option value="1" <?php if($customer['CStatus']==1) echo "selected"; ?>>ปกติ</option>
                                                            <option value="0" <?php if($customer['CStatus']==0) echo "selected"; ?>>แบล็คลิส</option>

                                                        </select>
                                                    </div>  
                                                    <label class="control-label col-sm-2">รหัสบัตรประชาชน </label>
                                                    <div class="col-sm-2">
                                                        <input type="text" class="form-control" value="<?php echo $customer['CCardId']; ?>" readonly="readonly">
                                                    </div> 
                                                    <label class="control-label col-sm-1">หมดอายุ </label>
                                                    <div class="col-sm-2">
                                                        <input type="text" class="form-control dateadd" name="CCardDateExpire" value="<?php echo viewdate($customer['CCardDateExpire']); ?>">
                                                    </div> 
                                                </div>
                                                        <div class="form-group">
                                                    <label class="control-label col-sm-2">ชื่อ </label>
                                                    <div class="col-sm-1">
                                                      <input type="text" class="form-control" name="CPreName" value="<?php echo $customer['CPreName']; ?>">
                                                    </div>
                                                    <div class="col-sm-2">
                                                      <input type="text" class="form-control" name="CName" value="<?php echo $customer['CName']; ?>">
                                                    </div>        
                                                
                                                    <label class="control-label col-sm-2">นามสกุล </label>
                                                    <div class="col-sm-2">
                                                      <input type="text" class="form-control" name="CLastName" value="<?php echo $customer['CLastName']; ?>">
                                                    </div>        
                                                
                                                    <label class="control-label col-sm-1">ชื่อเล่น </label>
                                                    <div class="col-sm-2">
                                                      <input type="text" class="form-control" name="CNickName" value="<?php echo $customer['CNickName']; ?>">
                                                    </div>        
                                                </div>
                                                        <div class="form-group">
                                                    <label class="control-label col-sm-2">วันเดือนปีเกิด </label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control dateadd" name="CBirthDay" value="<?php echo viewdate($customer['CBirthDay']); ?>">
                                                    </div>        
                                               
                                                    <label class="control-label col-sm-2">เพศ </label>
                                                    <div class="col-sm-2">
                                                        <select class="form-control" name="CGender">
                                                            <option value="1" <?php if($customer['CGender']==1) echo "selected"; ?>>ชาย</option>
                                                            <option value="2" <?php if($customer['CGender']==2) echo "selected"; ?>>หญิง</option>

                                                        </select>
                                                    </div>       

                                                    <label class="control-label col-sm-1">สถานภาพ </label>
                                                    <div class="col-sm-2">
                                                        <select class="form-control" name="CMaritalStatus">
                                                            <option value="1" <?php if($customer['CMaritalStatus']==1) echo "selected"; ?>>โสด</option>
                                                            <option value="2" <?php if($customer['CMaritalStatus']==2) echo "selected"; ?>>สมรส</option>

                                                        </select>
                                                    </div>       

                                                  </div> 
                                                        
                                                        
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">เบอร์โทรศัพท์มือถือ </label>
                                                    <div class="col-sm-3">
                                                      <input type="text" class="form-control" name="CMobile" value="<?php echo $customer['CMobile']; ?>">
                                                    </div>        

                                                    <label class="control-label col-sm-2">หมายเหตุ </label>
                                                    <div class="col-sm-5">
                                                      <input type="text" class="form-control" name="CNote" value="<?php echo $customer['CNote']; ?>">
                                                    </div>        
                                                </div> 
                                                        <div class="form-group">
                                                         

                                                  </div> 
                                                  <div class="form-group">
                                                      <div class="col-sm-6 col-sm-offset-2">
                                                          <button type="submit" class="btn btn-primary col-sm-4" name="general">บันทึก</button>      
                                                      </div>                     

                                                  </div>  
                                                </form>  
                                               
                                                
                                                    
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="address">
                                                    <div class="space-10"></div>  
                                                    <div class="row">
                                                    <div class="col-lg-12">
                                                        <a href="modal_customeraddress.php?CId=<?php echo $CId; ?>" class="btn btn-success pull-right" data-toggle="modal" data-target="#customeraddressModal"> <i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่ </a>
                                                        
                                                        <br>
                                                        <div class="space-20"></div>  
                                                        <table class="table table-bordered table-hover" style="font-family: tahoma">
                                                                  <thead>
                                                                    <tr>
                                                                      <th class="text-center" width='10%'>วันที่ลงข้อมูล</th>
                                                                      <th class="text-center">ที่อยู่</th>
                                                                      <th class="text-center" width='12%'>จำนวนปีที่อาศัย</th>
                                                                      <th class="text-center" width='12%'>สถานะภาพที่อยู่</th>
                                                                      <th class="text-center" width='12%'>อยู่กับ</th>
                                                                      <th class="text-center" width='8%'>กระทำ</th>
                                                                    </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                      <?php
                                                                      $result_customeraddress = mysqli_query($link,"SELECT * FROM `customeraddress` WHERE `CId` = '$CId' ORDER BY `id` DESC");
                                                                      while($customeraddress = mysqli_fetch_array($result_customeraddress)){   
                                                                         
                                                                          
                                                                      ?>
                                                                      <tr>                                                                      
                                                                          <td class="text-center"><?php echo viewdate($customeraddress['CreateDate']); ?></td>
                                                                          <td>ชื่อหมู่บ้าน/หอพัก <?php echo $customeraddress['CAApartment']; ?>  (ห้อง <?php echo $customeraddress['CARoom']; ?>, ชั้น <?php echo $customeraddress['CAFloor']; ?>)<br>
                                                                              <?php echo $customeraddress['CANo']; ?> ต.<?php echo $customeraddress['CASubdistrict']; ?> อ.<?php echo $customeraddress['CADistrict']; ?>  จ.<?php echo $customeraddress['CAProvince']; ?> <?php echo $customeraddress['CAPostCode']; ?><br>
                                                                              โทรศัพท์ <?php echo $customeraddress['CATel']; ?>  ต่อ <?php echo $customeraddress['CATelEx']; ?>
                                                                              
                                                                          </td>
                                                                          <td><?php echo $customeraddress['CALiveYear']; ?> ปี
                                                                              <?php echo $customeraddress['CALiveMonth']; ?> เดือน
                                                                              
                                                                          </td>
                                                                          <td class="text-center">
                                                                              <?php
                                                                              $result_livestatus = mysqli_query($link, "SELECT `LSName` FROM `livestatus` WHERE `LSId` = '$customeraddress[CALiveStatus]'");
                                                                              $livestatus = mysqli_fetch_array($result_livestatus);
                                                                              echo $livestatus[0];
                                                                              ?>
                                                                          </td>
                                                                          <td class="text-center">
                                                                              <?php
                                                                              $result_livewith = mysqli_query($link, "SELECT `LWName` FROM `livewith` WHERE `LWId` = '$customeraddress[CALiveWith]'");
                                                                              $livewith = mysqli_fetch_array($result_livewith);
                                                                              echo $livewith[0];
                                                                              echo "<br>".$customeraddress['CALiveWithNum']." คน";
                                                                              ?>
                                                                          </td>
                                                                      <td><a href="modal_customeraddress.php?CAId=<?php echo $customeraddress['CAId']; ?>&CId=<?php echo $CId; ?>"  class="btn btn-xs btn-warning" data-toggle="modal" data-target="#customeraddressModal"><i class="ion-edit"></i> แก้ไข</a></td>
                                                                    </tr>
                                                                      <?php } ?>
                                                                  </tbody>
                                                                </table>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="work">
                                                    <div class="space-10"></div>  
                                                    <div class="row">
                                                    <div class="col-lg-12">
                                                        <a href="modal_customerwork.php?CId=<?php echo $CId; ?>" class="btn btn-success pull-right" data-toggle="modal" data-target="#customeraddressModal"> <i class="fa fa-plus" aria-hidden="true"></i> เพิ่มรายการใหม่ </a>
                                                        
                                                        <br>
                                                        <div class="space-20"></div>  
                                                    
                                                   <table class="table table-bordered table-hover" style="font-family: tahoma">
                                                                  <thead>
                                                                    <tr>
                                                                      <th class="text-center" width='10%'>เริ่มงาน</th>
                                                                      <th class="text-center">สถานที่ทำงาน</th>
                                                                      <th class="text-center" width='15%'>ตำแหน่ง</th>
                                                                      <th class="text-center" width='20%'>รายรับ</th>
                                                                      <th class="text-center" width='8%'>กระทำ</th>
                                                                    </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                      <?php
                                                                      $result_customerwork = mysqli_query($link,"SELECT * FROM `customerwork` WHERE `CId` = '$CId' ORDER BY `id` DESC");
                                                                      while($customerwork = mysqli_fetch_array($result_customerwork)){   
                                                                         
                                                                          
                                                                      ?>
                                                                      <tr>                                                                      
                                                                          <td class="text-center">
                                                                              <?php echo $customerwork['CWWorkStartMonth']; ?>/<?php echo $customerwork['CWWorkStartYear']; ?>
                                                                              <span class="label label-default"><?php
                                                                              if($customerwork['CWType']==1) echo "พนักงานประจำ";
                                                                              if($customerwork['CWType']==2) echo "พนักงานซับ";
                                                                              if($customerwork['CWType']==3) echo "พนักงานสัญญาจ้าง";
                                                                              ?></span>
                                                                          </td>
                                                                          <td><strong><?php echo $customerwork['CWCompany']; ?></strong><br>
                                                                              <?php echo $customerwork['CWAddress']; ?> ตำบล <?php echo $customerwork['CWSubdistrict']; ?> อำเภอ <?php echo $customerwork['CWDistrict']; ?> จังหวัด <?php echo $customerwork['CWProvince']; ?> <?php echo $customerwork['CWPostCode']; ?><br>
                                                                              โทรศัพท์ <?php echo $customerwork['CWTel']; ?> ต่อ <?php echo $customerwork['CWTelEx']; ?> เวลาสะดวกติดต่อ <?php echo $customerwork['CWTimeContact']; ?> 
                                                                              
                                                                          </td>
                                                                          <td><?php echo $customerwork['CWPosition']; ?></td>
                                                                          <td>
                                                                              เงินเดือน : <?php echo number_format($customerwork['CWSalary'],2); ?> บาท<br>
                                                                              โอที : <?php echo number_format($customerwork['CWOT'],2); ?> บาท<br>
                                                                              ออกวันที่ : <?php echo $customerwork['CWSalaryDate']; ?>  
                                                                          </td>
                                                                      <td><a href="modal_customerwork.php?CWId=<?php echo $customerwork['CWId']; ?>&CId=<?php echo $CId; ?>"  class="btn btn-xs btn-warning" data-toggle="modal" data-target="#customerworkModal"><i class="ion-edit"></i> แก้ไข</a></td>
                                                                    </tr>
                                                                      <?php } ?>
                                                                  </tbody>
                                                                </table>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

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
        
        <!-- customeraddressModal -->
  <div class="modal fade" id="customeraddressModal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content"></div>
    </div>
  </div>

        <!-- customerworkModal -->
  <div class="modal fade" id="customerworkModal" role="dialog">
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
        
        $('#productModal').on('shown.bs.modal', function () {
             $('.chosen-select', this).chosen('destroy').chosen();
        });
        
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
        } 

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        })
        
        </script>
        
        <script src="assets/js/chosen.jquery.min.js?v=1001" type="text/javascript"></script>
        <script>
          $(function() {
            $('.chosen-select').chosen({ width: "100%" });
            $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
          });
        </script>
        
</html>
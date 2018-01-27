<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 7;



?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>   
        
           <link href="css/bootstrap-datepicker.css" rel="stylesheet" />
        <script src="assets/js/bootstrap-datepicker-custom.js"></script>
         <script src="assets/js/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
        <style>           
           .datepicker{z-index:1151 !important;}
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
                    <div class="container" style="height: 1000px;">
                        <!--widget box row-->
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <header class="panel-heading">                                       
                                        <h2 class="panel-title">ตั้งหนี้บริการสาธารณะทั้งโครงการ</h2>                                        
                                    </header>
                                    <div class="panel-body">
                                        <div class="space-10"></div> 
                                        <div class="col-lg-6" >
            <form class="form-horizontal" role="form" method="GET" action="debtall2.php">
               
              <div class="form-group">
                <label class="control-label col-sm-3">ชื่อรายการ </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="DebName" required>
                </div>        
              </div>
                
              <div class="form-group">
                <label class="control-label col-sm-3">ระยะเวลาบริการ </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" name="ServiceStart" required>
                </div>  
                <label class="control-label col-sm-1">ถึง </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" name="ServiceEnd"  required>
                </div> 
                
              </div>
              
 
            <div class="form-group">
                <label class="control-label col-sm-3">วันที่ตั้งหนี้</label>
                <div class="col-sm-4">
                    <?php $datenow = date('Y-m-d'); ?>
                    <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" value="<?php echo viewdate($datenow); ?>" name="DebDate" required>
                </div>        
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">กำหนดชำระเงิน</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" data-provide="datepicker" data-date-language="th-th" name="DueDate" value="" required>
                </div>        
            </div>
        
 
   
              <div class="form-group">
                  <div class="col-sm-4 col-sm-offset-3">
                      <button type="submit" class="btn btn-primary">แสดงรายการที่เข้าเงื่อนไข</button>      
                  </div>                     

              </div>  
            </form>  
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
     




        

        <!-- Datatables-->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
        
       
<!--Date -->
        <script type="text/javascript" src="assets/js/moment-with-locales.js?v=1001"></script>
        <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js?v=1001"></script> 
        <script type="text/javascript" src="assets/jqueryui/jquery-ui.min.js?v=1001"></script>
        <script src="assets/plugins/datepickerb2/js/bootstrap-datepicker-thai.js"></script>
        <script src="assets/plugins/datepickerb2/js/locales/bootstrap-datepicker.th.js"></script>
        

        <script type="text/javascript">
       $(document).ready(function () {
           
            $('.datepicker').datepicker(); 
            
            var myDate = $("#datepicker").data("date");
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", myDate);  //กำหนดเป็นวันปัจุบัน
            
            
            var myDate2 = $("#datepicker2").data("date");
            $('.datepicker2').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", myDate);  //กำหนดเป็นวันปัจุบัน
            
            
            var myDate3 = $("#datepicker3").data("date");
            $('.datepicker3').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", myDate);  //กำหนดเป็นวันปัจุบัน
            
        });
    
    //--Clear Modal cache    
        
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });
        
        $(function () {
            $('.dateadd').datetimepicker({
                format: 'DD/MM/YYYY',locale: 'th',locale: 'th'
            });
            
        });
        
        </script>

        
</html>
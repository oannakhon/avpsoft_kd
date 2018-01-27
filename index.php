<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 1;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>
        <link href="css/bootstrap-datepicker.css" rel="stylesheet" />
        <script src="assets/js/bootstrap-datepicker-custom.js"></script>
         <script src="assets/js/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>
        <style type="text/css">
            .flotTip 
            {
              padding: 3px 5px;
              background-color: #000;
              z-index: 100;
              color: #fff;
              box-shadow: 0 0 10px #555;
              opacity: .7;
              filter: alpha(opacity=70);
              border: 2px solid #fff;
              -webkit-border-radius: 4px;
              -moz-border-radius: 4px;
              border-radius: 4px;
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
                        <?php
                           $result_widjet = mysqli_query($link, "SELECT * FROM `widjet_permission` AS `a` JOIN `widjet` AS `b`"
                                   . "ON `a`.`WJId` = `b`.`WJId`"
                                   . "WHERE `a`.`UserId` = '$_SESSION[UserId]'"
                                   . "AND `a`.`WJStatus` = 1 "
                                   . "ORDER BY `a`.`UpdateDate`");

                            while($widjet = mysqli_fetch_array($result_widjet)){
                                include 'widjet/'.$widjet['WJFile'];

                            }
                            
                            
                           
                        ?>
                       
                        
                        
                        
                        
                        
                                
                        <!--widget box row
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel">
                                    <header class="panel-heading">
                                        <div class="panel-actions">
                                            <a href="javascript:void(0)" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                                            <a href="javascript:void(0)" class="panel-action action-link"><i class="ion-refresh"></i></a>
                                            <a href="javascript:void(0)" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
                                            <div class="dropdown pull-left">
                                                <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="ion-more"></i></a>
                                                <ul class="dropdown-menu dropdown-menu-scale">
                                                    <li><a href="javascript:void(0)">Action</a></li>
                                                    <li><a href="javascript:void(0)">Just Action</a></li>
                                                    <li><a href="javascript:void(0)">Action Now</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <h2 class="panel-title">ยอดรายการแจ้งซ่อม <span class="helping-text">2016</span></h2>
                                    </header>
                                    <div class="panel-body">
                                        <div class="flot-chart">
                                            <div class="flot-chart-data" id="flot-line-chart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             
                        </div>


                    </div>end container-->

                    

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

<!-- Modal -->
  <div class="modal fade" id="Modal" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content"></div>
    </div>
  </div>

        
        <!--page scripts-->
        <!-- Flot chart js -->
        <script src="assets/plugins/flot/jquery.flot.js"></script>
        <script src="assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
        <script src="assets/plugins/flot/jquery.flot.resize.js"></script>
        <script src="assets/plugins/flot/jquery.flot.pie.js"></script>
        <script src="assets/plugins/flot/jquery.flot.time.js"></script>
        <script src="assets/plugins/flot/jquery.flot.tooltip.js"></script>
        <!--vector map-->
        <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
        <!-- ChartJS-->
        <script src="assets/plugins/chartJs/Chart.min.js"></script>
        <!--dashboard custom script-->
        

        <!--Date -->
        <script type="text/javascript" src="assets/js/moment-with-locales.js?v=1001"></script>
        <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js?v=1001"></script> 
        <script type="text/javascript" src="assets/jqueryui/jquery-ui.min.js?v=1001"></script>
        <script src="assets/plugins/datepickerb2/js/bootstrap-datepicker-thai.js"></script>
        <script src="assets/plugins/datepickerb2/js/locales/bootstrap-datepicker.th.js"></script>
        


        
        
    <script type="text/javascript">
            $(function () {            
                $('.dateadd').datetimepicker({
                    format: 'DD/MM/YYYY',locale: 'th'
                });            
            });
            
            $(document).ready(function () {
             //--วันที่----------------   
            var myDate = $("#datepicker").data("date");
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", myDate);  //กำหนดเป็นวันปัจุบัน
            
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
            
             


</script>
    </body>
</html>
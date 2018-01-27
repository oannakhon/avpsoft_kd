<?php
session_start();
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 6;
$BId = $_SESSION['BId'];

if(isset($_GET['SGId_L2'])){
    //หา MINDATE
    //หา MAXDATE
    //L0
    //L1
    //L2 = SGId_L2
    //location to projectplan.php?ตัวแปร=ค่า&search=""
   
   // $LV0 = $_GET['LV0'];
    //$LV1 = $_GET['LV1'];
    $DateStart = viewdate(MINDoc($link, "service", "SDate"));
    $DateEnd = viewdate(MAXDoc($link, "service", "SDate"));
    
    $LV2 = $_GET['SGId_L2'];
    
    //หา LV1 --------------------------------------------------
    $result_LV1 = mysqli_query($link, "SELECT `SGParentId` FROM `servicegroup` WHERE `SGId` LIKE '$LV2'");
    $servicegroupLV1 = mysqli_fetch_array($result_LV1);
        
    $LV1 = $servicegroupLV1[0];
    
    $result_LV0 = mysqli_query($link, "SELECT `SGParentId` FROM `servicegroup` WHERE `SGId` LIKE '$LV1' ");
    $servicegroupLV0 = mysqli_fetch_array($result_LV0);
        
    $LV0 = $servicegroupLV0[0];
    
    echo "<meta http-equiv=refresh CONTENT=\"0; URL=projectplan.php?DateStart=$DateStart&DateEnd=$DateEnd&LV0=$LV0&LV1=$LV1&LV2=$LV2&search=\">";  
    exit;        
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>
        <link href="css/bootstrap-datepicker.css" rel="stylesheet" />
        <script src="assets/js/bootstrap-datepicker-custom.js"></script>
         <script src="assets/js/bootstrap-datepicker.th.min.js" charset="UTF-8"></script>

        <script src="svg-pan-zoom-master/dist/svg-pan-zoom.js"></script>
        <style>
            .projectplan {
                max-width: 100vw;
                height:auto;
                width:auto;
                max-height:85vh;
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
                    <div class="space-10"></div>
                        <div class="pull-right">
                            <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="fa fa-search" aria-hidden="true"></i> ค้นหา</a>
                        &nbsp;&nbsp; 
                        </div><br>
                    <div class="container" style="width: 95%; height: 650px;">
                        <!--widget box row-->
                                   
                        <svg id="svg-id" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 3054 2879" style="display: inline; width: inherit; min-width: inherit; max-width: inherit; height: inherit; min-height: inherit; max-height: inherit;" >
                        <defs>
                          <pattern id="img1" patternUnits="userSpaceOnUse" width="100" height="100">
                            <image xlink:href="mapimage/rainbow.jpg"/>
                          </pattern>
                        </defs>        
                        <image width="3054" height="2879" xlink:href="assets/images/projectplan/<?php echo $BId; ?>.jpg"></image>
                        
                        <?php 
                        
                            $str_SGId = "";  
                            if(isset($_GET['search'])){                                        
                                $LV0 = $_GET['LV0'];
                                $LV1 = $_GET['LV1'];
                                $LV2 = $_GET['LV2'];
                                $DateStart = $_GET['DateStart'];  
                                $DateEnd = $_GET['DateEnd'];  
                                $showDateStart = datemysql543($_GET['DateStart']);  
                                $showDateEnd = datemysql543($_GET['DateEnd']); 
                                
                                
                                
                                $str_SGId = "";
                                $arr_SGId = array();
                                $arr_color = array();
                                if($LV0=="%"){
                                    $result = mysqli_query($link, "SELECT * FROM `servicegroup` WHERE `SGLevel` = '0'");
                                    while ($servicegroup = mysqli_fetch_array($result)){
                                        $str_SGId .= "'".$servicegroup['SGId']."', ";
                                        array_push($arr_SGId,$servicegroup['SGId']);
                                    }
                                    $SGIdL = "SGIdL0"; // field สำหรับค้นหา ParNo ใน servicesub

                                }else if(($LV0!="%")&&($LV1=="%")){
                                    $result = mysqli_query($link, "SELECT * FROM `servicegroup` WHERE `SGParentId` = '$LV0'");
                                    while ($servicegroup = mysqli_fetch_array($result)){
                                        $str_SGId .= "'".$servicegroup['SGId']."', ";  
                                        array_push($arr_SGId,$servicegroup['SGId']);
                                    }
                                    $SGIdL = "SGIdL1";

                                }else if(($LV1!="%")&&($LV2=="%")){
                                    $result = mysqli_query($link, "SELECT * FROM `servicegroup` WHERE `SGParentId` = '$LV1'");
                                    while ($servicegroup = mysqli_fetch_array($result)){
                                        $str_SGId .= "'".$servicegroup['SGId']."', ";  
                                        array_push($arr_SGId,$servicegroup['SGId']);
                                    }
                                    $SGIdL = "SGIdL2";

                                }else{
                                    $result = mysqli_query($link, "SELECT * FROM `servicegroup` WHERE `SGId` LIKE '$LV2'");
                                    while ($servicegroup = mysqli_fetch_array($result)){
                                        $str_SGId .= "'".$servicegroup['SGId']."', ";  
                                        array_push($arr_SGId,$servicegroup['SGId']);
                                    }
                                    $SGIdL = "SGIdL2";
                                }

                                $str_SGId = substr($str_SGId,0, -2); //ตัดเครื่องหมาย , ตัวหลังสุดออก 'SG-01','SG-02','SG-03'
                                
                                $num_color = count($arr_SGId);
                                $x = 50;
                                $y = 50;
                                $ytext = 90;
                                $j = 0;
                                $result_color = mysqli_query($link, "SELECT * FROM `color` LIMIT $num_color");
                                while ($color = mysqli_fetch_array($result_color)){
                                    $color1 = $color[1];
                                   
                                    echo "<rect x=\"".$x."\" y=\"".$y."\" width=\"50\" height=\"50\" style=\"fill:#".$color['color']."\" />";
                                    echo "<text x=\"115\" y=\"".$ytext."\" font-family=\"tahoma\" font-size=\"40\" fill=\"black\">".SGName($link, $arr_SGId[$j])."</text>";
                                    $y = $y+60;
                                    $ytext = $ytext+60;
                                    array_push($arr_color,$color1);
                                    $j++;
                                }
                                
                                
                        ?>
                        
                        
                        
<?php

                                    
                                     
                                     //----------หาตำแหน่งในการวาดสี-------------------------------------------------
                                     $result_servicesub = mysqli_query($link, "SELECT `b`.`ParNo`, `a`.`$SGIdL` "
                                            . "FROM `servicesub` AS `a` "
                                            . "JOIN `service` AS `b` "
                                            . "ON `a`.`SId` = `b`.`SId` "
                                            . "WHERE `b`.`BId` = '$BId' "
                                            . "AND `a`.`$SGIdL` IN ($str_SGId) "
                                            . "AND `a`.`SSStatus` != '0' "
                                            . "AND `b`.`SDate` BETWEEN '$showDateStart' AND '$showDateEnd' "
                                            . "AND `b`.`ParNo` != '' "
                                            . "AND `a`.`SId` NOT LIKE 'temp%' "
                                            . "GROUP BY `b`.`ParNo` ");    
                                     //----------------------------------------------------------------------------
                                     
                                if(mysqli_num_rows($result_servicesub)!=0){
                                    $arr_ParNo = array();//เก็บค่า SGId
                                    while($ss = mysqli_fetch_array($result_servicesub)){
                                        $ParNo = $ss[0];
                                        $SGId = $ss[1];
                                        
                                        $i= array_search($SGId,$arr_SGId); //ตำแหน่งที่ค้นเจอ
                                        $show_color = $arr_color[$i];
                                        
                                        
                                        $result_coor = mysqli_query($link, "SELECT `ParCoor` FROM `parcel` "
                                            . "WHERE `BId` = '$BId' AND `ParNo` = '$ParNo' "
                                            . "AND `ParStatus` != '99' ");
                                        
                                        $ParCoor = mysqli_fetch_array($result_coor);   
                                        ?>
                        <a href="unitaftersale.php?id=<?php echo $ParNo; ?>#service"><polygon points="<?php echo $ParCoor[0]; ?>" fill="#<?php echo $show_color; ?>" opacity="0.8"><title><?php echo $ParNo."-".SGName($link, $SGId); ?></title></polygon></a>
                                        <?php   
                                        
                                    array_push($arr_ParNo, $ParNo);//เก็บค่า SGId ที่แสดงผลไปแล้ว ใส่ใน $arr_ParNo
                                    
                                    }
                                    
                                }
                                $DateStart = $showDateStart;
                                $DateEnd = $showDateEnd;
                                        
                            }else{
                                $LV0 = "%";
                                $LV1 = "%";
                                $LV2 = "%";  
                                $DateStart = date('Y-m-01');  
                                $DateEnd = date('Y-m-d');  
                            }
?>
                            </svg>                 
                     
                           
                        
                            
                        </div>
                    
                        <!--widget box row-->


                    </div><!--end container-->

                    

                    <!--footer start-->
                    <div class="footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <span>&copy; Copyright 2016. AVP Enterprise Co.Ltd. ติดต่อผู้พัฒนาโปรแกรมได้ที่ Line Id: @avpenterp </span>
                            </div>
                        </div>
                    </div>
                    <!--footer end-->
                </section><!--end main content-->
            </div>
        </div><!--end wrapper-->

        
        <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ประเภทงานซ่อม</h4>
      </div>
      <div class="modal-body">
          <form method="GET" action="projectplan.php">
                <div class="form-group row">
                    <div class="col-xs-4">
                        <h5 class="text-right" style="margin-top: 9px">ตั้งแต่วันที่</h5>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" data-provide="datepicker" autocomplete=off data-date-language="th-th" value="<?php echo viewdate($DateStart); ?>" name="DateStart">
                </div>   
                </div>
              
                <div class="form-group row">
                    <div class="col-xs-4">
                        <h5 class="text-right" style="margin-top: 9px">ถึงวันที่</h5>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" data-provide="datepicker" autocomplete=off data-date-language="th-th" value="<?php echo viewdate($DateEnd); ?>" name="DateEnd">
                </div>   
                </div>
              
              
                <div class="form-group row">
                    <div class="col-xs-4">
                        <h5 class="text-right" style="margin-top: 9px">Level 1</h5>
                    </div>
                    <div class="col-xs-5">
                        <select class="form-control" id="LV0" name="LV0">
                            <option value="%" <?php if($LV0=="%"){echo "selected";} ?>>ทั้งหมด</option>
                            <?php
                            $result_servicegroup = mysqli_query($link, "SELECT * FROM `servicegroup` WHERE `SGLevel` = '0'");
                            while ($servicegroup = mysqli_fetch_array($result_servicegroup)){
                                if($LV0==$servicegroup['SGId']){$select =  "selected";}
                                else{$select = "";}
                                echo "<option value=\"".$servicegroup['SGId']."\" ".$select.">".$servicegroup['SGName']."</option>";
                            
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-4">
                        <h5 class="text-right" style="margin-top: 9px">Level 2</h5>
                    </div>
                    <div class="col-xs-5">
                        <select class="form-control" id="LV1" name="LV1">
                            <option value="%" <?php if($LV1=="%"){echo "selected";} ?>>ทั้งหมด</option>
                           
                        </select>
                    </div>
                </div>
          
                <div class="form-group row">
                    <div class="col-xs-4">
                        <h5 class="text-right" style="margin-top: 9px">Level 3</h5>
                    </div>
                    <div class="col-xs-5">
                        <select class="form-control" id="LV2" name="LV2">
                            <option value="%">ทั้งหมด</option>
                                
                        </select>
                    </div>
                </div>
          
          
                    <div class="col-xs-offset-4 col-xs-5">
                        <button type="submit" name="search" class="btn btn-primary btn-block btn-lg">ค้นหา</button>
                    </div>
              <br>
          </form>
      </div>
        <br>
    </div>

  </div>
</div>
        <!--Date -->
        <script type="text/javascript" src="assets/js/moment-with-locales.js?v=1001"></script>
        <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js?v=1001"></script> 
        <script type="text/javascript" src="assets/jqueryui/jquery-ui.min.js?v=1001"></script>
        <script src="assets/plugins/datepickerb2/js/bootstrap-datepicker-thai.js"></script>
        <script src="assets/plugins/datepickerb2/js/locales/bootstrap-datepicker.th.js"></script>
        
        <script>
     $(document).ready(function () {              
              $('.datepicker').datepicker();             
        });
    
    
    $(function () {
            $('.dateadd').datetimepicker({
                format: 'DD/MM/YYYY',locale: 'th'
            });
            
        });  
    
    
    // Don't use window.onLoad like this in production, because it can only listen to one function.
      window.onload = function() {
        // Expose to window namespase for testing purposes
        window.panZoomInstance = svgPanZoom('#svg-id', {
          zoomEnabled: true,
          controlIconsEnabled: true,
          fit: true,
          center: true,
          minZoom: 1
        });

        // Zoom out
        panZoomInstance.zoom(0.2);

        
      };
      
$(document).ready(function(){
    
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
            }).datepicker("setDate", myDate2);  //กำหนดเป็นวันปัจุบัน
            
    
    
    $('#LV0').on('change',function(){
        var SGID = $(this).val();
        if(SGID){
            $.ajax({
                type:'POST',
                url:'get_list.php',
                data:'SGId='+SGID,
                success:function(html){
                    $('#LV1').html(html);
                    $('#LV2').html(html);
                }
            }); 
        }
    });    
    
   $('#LV1').on('change',function(){
        var SGID = $(this).val();
        if(SGID){
            $.ajax({
                type:'POST',
                url:'get_list2.php',
                data:'SGId='+SGID,
                success:function(html){
                    $('#LV2').html(html);
                }
            }); 
        }
    });    
    
});


      
      
    </script>
        
    </body>
</html>
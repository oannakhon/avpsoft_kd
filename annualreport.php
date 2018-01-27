<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 6;

if(isset($_POST['Y'])){
    $Year = $_POST['Y'];
}else{
    $Year = date('Y');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>   
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
                    <div class="container">
                        <!--widget box row-->
                        
                        <div class="row">
                            <div class="col-md-12">
                                
                                <div class="panel">
                                    <header class="panel-heading">                                       
                                        <h2 class="panel-title pull-left">รายงานแจ้งซ่อมรายปี <?php echo $Year; ?></h2>      
                                        <div class="pull-right" style="margin-left: 10px"><button class="btn btn-info" id="print_canvas"><i class="fa fa-print"></i> </button></div>
                                        
                                        <div class="pull-right">
                                                   <form method="POST" action="annualreport.php" >
                                                       <select class="form-control" name="Y" onchange='this.form.submit()'>
                                                       <?php
                                                       $result_Y = mysqli_query($link, "SELECT MIN(`SDate`),MAX(`SDate`) FROM `service` WHERE `SStatus` != '0'");
                                                       $Y = mysqli_fetch_array($result_Y);
                                                       
                                                       $datemin=date_create($Y[0]);
                                                       $datemax=date_create($Y[1]);
                                                       $MinY = date_format($datemin,"Y");
                                                       $MaxY = date_format($datemax,"Y");
                                                       
                                                       for($i=$MinY;$i<=$MaxY;$i++){
                                                           if($Year == $i){
                                                               $selected = "selected";
                                                           }else{
                                                               $selected = "";
                                                           }
                                                       ?>
                                                           <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                                                       <?php } ?>
                                                   </select>
                                                   </form>
                                            
                                            </div>
                                        
                                    </header>
                                    <div class="panel-body">
                                        
                                        <div id="tocanvas">                                          
                                   <div class="col-md-7"> 
                                            <div class="panel">
                                    <header class="panel-heading">
                                        <div class="panel-actions">
                                            <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                                            <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
                                        </div>
                                        <h2 class="panel-title">งานแจ้งซ่อมทั้งหมด</h2>

                                    </header>
                                    <div class="panel-body">
                                        <div class="morris-chart-data">
                                            <div id="morris-one-line-chart"></div>
                                        </div>

                                    </div>
                                </div>
                                                </div>  
                                            
<?php
    
    $result = mysqli_query($link, "SELECT `b`.`SGName`, COUNT(`a`.`id`) AS `c` , `a`.`SGIdL2` "
            . "FROM `servicesub` AS `a` "
            . "LEFT JOIN `servicegroup` AS `b` "
            . "ON `a`.`SGIdL2` = `b`.`SGId` "
            . "WHERE `a`.`SSStatus` != '0' "
            . "AND `a`.`SGIdL2` != '' "
            . "AND YEAR(`a`.`CreateDate`) = '$Year' "
            . "GROUP BY `a`.`SGIdL2` "
            . "ORDER BY `c` DESC "
            . "LIMIT 10");

?>


<div class="col-md-5">
        <div class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                    <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
                </div>
                <h2 class="panel-title">รายการแจ้งซ่อม Top 10</h2>

            </header>
            <div class="panel-body">
                <div class="flot-chart">
                    <div class="flot-chart-data" id="flot-pie-chart2"></div>
                </div>

            </div>
        </div>
       </div>

        <script type="text/javascript">
             function labelFormatter2(label,  series) {
     return "<a href='projectplan.php?SGId_L2="+ series.label2 +"'><div style='font-size:12pt; text-align:center; padding:2px; color:white;'>" + series.data[0][1] + "</div></a>";

    }
        //Flot Pie Chart
$(function () {
    var data = [
        <?php
        $i = 0;
        $num = mysqli_num_rows($result);
        $color_arr = array();
        $result_color = mysqli_query($link, "SELECT * FROM `color`");
        while ($color = mysqli_fetch_array($result_color)){
            array_push($color_arr, $color['color']);
        }
        while ($service = mysqli_fetch_array($result)){
            echo "{";
            echo "label: \"".$service[0]."\",";
            echo "label2: \"".$service[2]."\",";
            echo "data: ".$service[1].",";
            echo "color: \"#".$color_arr[$i]."\"";
            echo "}";
            
            if($i<$num){
                echo ",";
            }
            $i++;
        }
        ?>
        
        
    ];

    var plotObj = $.plot($("#flot-pie-chart2"), data, {
        series: {
            
        pie: {
            show: true,
            
             label: {
                    show: true,
                    radius: 2/4,
                    formatter: labelFormatter2,
                    background: {
                        opacity: 0.5
                }
            }
        }
    },
    legend: {
        show: true
    },
        grid: {
            hoverable: true
        },
        
        tooltip: {
        show: true,
        content: "%p.0%, %s, จำนวน %n รายการ", // show percentages, rounding to 2 decimal places
        shifts: {
          x: 20,
          y: 0
        },
        defaultTheme: false
      }
    });

});

//-Flot Line -------------------------------------------------------------------

$(function() {
   
    //one line chart
       Morris.Line({
        element: 'morris-one-line-chart',
            data: [
                
                <?php
                
                for($i=1;$i<=12;$i++){
                    $result_servicesubmonth = mysqli_query($link, "SELECT COUNT(`a`.`id`) "
                        . "FROM `servicesub` AS `a` "
                        . "LEFT JOIN `service` AS `b` "
                        . "ON `a`.`SId` = `b`.`SId` AND `a`.`BId` = `b`.`BId`  "
                        . "WHERE YEAR(`b`.`SDate`) = '$Year' "
                        . "AND MONTH(`b`.`SDate`) = '$i' "
                        . "AND `a`.`SSStatus` != '0' "
                        . "AND `b`.`SStatus` != '0' "
                        . "AND `b`.`SId` NOT LIKE 'temp%'");
                    
                    $servicesubmonth = mysqli_fetch_array($result_servicesubmonth);
                    echo "{ year: '".$Year."-".$i."' , value: ".$servicesubmonth[0]."}";
                    if($i!=12) echo ",";
                }
                ?>
            ],
        xkey: 'year',
        ykeys: ['value'],
        resize: true,
        lineWidth:4,
        labels: ['Value'],
        lineColors: ['#36c6d3'],
        pointSize:5
    });
});

        </script>
                                               
                                               
                                                    
                                                <br>
                                                    <div class="space-10"></div> 
                                               
                                                 
                                                    
                                                <table class="table table-hover table-bordered" width="100%" style="font-family: tahoma;font-size: 10px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: 14px"  rowspan="2" style=""><center>Project <?php echo $Year; ?></center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>Jan</center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>Feb</center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>Mar</center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>Apr</center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>May</center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>Jun</center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>Jul</center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>Apr</center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>Sep</center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>Oct</center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>Dec</center></th>
                                                            <th style="font-size: 14px" colspan="2"><center>Nov</center></th>                                                     
                                                        </tr>
                                                        <tr >
                                                            
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            <th class="text-center">A</th>
                                                            <th class="text-center">F</th>
                                                            
                                                            
                                                            
                                                        </tr>
                                                        
                                                    </thead>

                                                    <tbody class="text-center">
                                                        <?php
                                                        
                                                        $result_BId = mysqli_query($link, "SELECT * FROM `branch` WHERE `BStatus` = '1'");
                                                        while ($BId = mysqli_fetch_array($result_BId)){
                                                        ?>
                                                        <tr>
                                                            
                                                            
                                                            <td style="font-size: 14px"><?php echo $BId['BId']; ?></td>
                                                            <?php
                                                            for($i=1;$i<=12;$i++){
                                                                $DateStart = $Year."-".$i."-01"; //วันที่ 1 ของเดือน
                                                                $DateEnd = date('Y-m-t',strtotime($DateStart)); //วันที่สิ้นเดือน
                                                                
                                                                $result_servicesub = mysqli_query($link, "SELECT COUNT(`a`.`id`) FROM `servicesub` AS `a` LEFT JOIN `service` AS `b`"
                                                                        . "ON `a`.`SId` = `b`.`SId` AND `a`.`BId` = `b`.`BId`"
                                                                        . " WHERE `a`.`BId` = '$BId[BId]' "
                                                                        . "AND `b`.`BId` = '$BId[BId]' "
                                                                        . "AND `b`.`SDate` BETWEEN '$DateStart' AND '$DateEnd' "
                                                                        . "AND `a`.`SSStatus` != '0' "
                                                                        . "AND `b`.`SStatus` != '0' "
                                                                        . "AND `b`.`SId` NOT LIKE 'temp%'");
                                                                $A = mysqli_fetch_array($result_servicesub);
                                                                
                                                                
                                                                
                                                                $result_servicesub2 = mysqli_query($link, "SELECT COUNT(`a`.`id`) FROM `servicesub` AS `a` LEFT JOIN `service` AS `b`"
                                                                        . "ON `a`.`SId` = `b`.`SId` AND `a`.`BId` = `b`.`BId`"
                                                                        . " WHERE `a`.`BId` = '$BId[BId]' "
                                                                        . "AND `b`.`BId` = '$BId[BId]' "
                                                                        . "AND `a`.`SSDateFinish` BETWEEN '$DateStart' AND '$DateEnd' "
                                                                        . "AND `a`.`SSStatus` != '0' "
                                                                        . "AND `b`.`SStatus` != '0' "
                                                                        . "AND `b`.`SId` NOT LIKE 'temp%'");
                                                                $B = mysqli_fetch_array($result_servicesub2);
                                                            ?>
                                                            <td><?php if($A[0]!=0){ echo $A[0];}else{echo "-";} ?></td>
                                                            <td><?php if($B[0]!=0){ echo $B[0];}else{echo "-";} ?></td>
                                                            <?php } ?>                                                            
                                                        </tr>                                                     
                                                        <?php } ?>
                                                    </tbody>
                                                </table>         

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
     
<!-- myModal -->
  <div class="modal fade" id="myModal" role="dialog">
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
        
        
        <script src="assets/plugins/morris/raphael-2.1.0.min.js"></script>
        <script src="assets/plugins/morris/morris.js"></script>

        <!--Date -->
        <script type="text/javascript" src="assets/js/moment-with-locales.js?v=1001"></script>
        <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js?v=1001"></script> 
        <script type="text/javascript" src="assets/jqueryui/jquery-ui.min.js?v=1001"></script>
        
        <!--vector map-->
        <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
        <!-- ChartJS-->
        <script src="assets/plugins/chartJs/Chart.min.js"></script>
        <!--dashboard custom script-->
        
        <!--HTML2CANVAS -->
        <script src="assets/js/html2canvas.min.js"></script>
        <script src="assets/js/FileSaver.min.js"></script>
        <script type="text/javascript">
            /**
 * Convert a base64 string in a Blob according to the data and contentType.
 * 
 * @param b64Data {String} Pure base64 string without contentType
 * @param contentType {String} the content type of the file i.e (image/jpeg - image/png - text/plain)
 * @param sliceSize {Int} SliceSize to process the byteCharacters
 * @see http://stackoverflow.com/questions/16245767/creating-a-blob-from-a-base64-string-in-javascript
 * @return Blob
 */
function b64toBlob(b64Data, contentType, sliceSize) {
        contentType = contentType || '';
        sliceSize = sliceSize || 512;

        var byteCharacters = atob(b64Data);
        var byteArrays = [];

        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);

            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            var byteArray = new Uint8Array(byteNumbers);

            byteArrays.push(byteArray);
        }

      var blob = new Blob(byteArrays, {type: contentType});
      return blob;
}


            
            $(document).ready(function() {
                $("#print_canvas").click(function(){
                    var element = document.getElementById("tocanvas");
                    html2canvas(element,{
                        width: 2000,
                        height:1200,
                    }).then(function(canvas) {
                        // Export the canvas to its data URI representation
                        var base64image = canvas.toDataURL("image/png");

                        // Split the base64 string in data and contentType
                        var block = base64image.split(";");
                        // Get the content type
                        var mimeType = block[0].split(":")[1];// In this case "image/png"
                        // get the real base64 content of the file
                        var realData = block[1].split(",")[1];// For example:  iVBORw0KGgouqw23....

                        // Convert b64 to blob and store it into a variable (with real base64 as value)
                        var canvasBlob = b64toBlob(realData, mimeType);

                        // Generate file download
                        saveAs(canvasBlob, "screenshot.png");
                        
                        
                    });

                });
              });


        
        </script>

        
        
        
</html>
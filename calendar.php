<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
checklogin();//Check Login
$menuactive = 6;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?> 
        <link href='assets/plugins/fullcalendar/fullcalendar.css' rel="stylesheet">
        
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
                                        <h2 class="panel-title pull-left">ปฏิทินนัดหมาย</h2> 
                                        
                                    </header>
                                    <div class="panel-body">
                                        <div id='calendar'></div>

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
     

<div id="calendarModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div id="modalBody" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


        
        <!--Date -->
        <script type="text/javascript" src="assets/js/moment-with-locales.js?v=1001"></script>
        <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js?v=1001"></script> 
        <script type="text/javascript" src="assets/jqueryui/jquery-ui.min.js?v=1001"></script>
        
       
        <script src="assets/plugins/momentJs/moment.min.js"></script>
        <script src="assets/plugins/fullcalendar/fullcalendar.min.js"></script>
        <script type="text/javascript" src="assets/plugins/fullcalendar/th.js"></script>
        <script>
            $(document).ready(function () {

                /* initialize the calendar
                 -----------------------------------------------------------------*/

                $('#calendar').fullCalendar({
                    lang: 'th',
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: ''
                        
                    },
                    events: [
                      <?php
                                                    $result = mysqli_query($link, "SELECT * FROM `appointment` "
                                                            . "WHERE `BId` = '$_SESSION[BId]' "
                                                            . "AND `APStatus` = '1' "
                                                            . "ORDER BY `id` DESC");
                                                    while ($appointment = mysqli_fetch_array($result)){
                                                ?>
				{
                                          "title":"<?php echo $appointment['APDetail']; ?>",
                                          "allday":"false",
                                          "color":"#8bc34a",
                                          "description":"<h4>แปลงที่ <?php echo $appointment['ParNo']; ?></h4><br><p><?php echo $appointment['APDetail']; ?></p><br><p>นัดหมายโดย <?php echo showUserFullName($link, $appointment['CreateBy']); ?></p>",
                                          "start":"<?php echo $appointment['APDate']; ?>",
                                          "end":"<?php echo $appointment['APDate']; ?>",
                                          "url":""
				},
                                        
                                                    <?php } ?>              
                               
                    ],
                
                    eventClick:  function(event, jsEvent, view) {
                        $('#modalTitle').html(event.title);
                        $('#modalBody').html(event.description);
                        $('#eventUrl').attr('href',event.url);
                        $('#calendarModal').modal();
                    },

                });


            });

        </script>
</html>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">       
        <meta name="viewport" content="width=device-width, initial-scale=1">     

        <title>
        <?php 
            //Check Key Exist
            if(isset($_COOKIE['AVPKey'])){
                echo "[KEY] ";
            }
            echo CValue($link,"title"); 
            ?>
        </title>
        <link rel="icon" type="image/png" href="assets/images/favicon.png?v=1002"/>
        <link href="font-google/kanit/css.css?v=1001" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="assets/plugins/bootstrap/css/bootstrap.min.css?v=1001" rel="stylesheet">
        <!--side menu plugin-->
        <link href="assets/plugins/hoe-nav/hoe.css?v=1001" rel="stylesheet">
        <!-- icons-->
        <link href="assets/plugins/ionicons/css/ionicons.min.css?v=1001" rel="stylesheet">
        <link href="assets/plugins/font-awesome/css/font-awesome.min.css?v=1001" rel="stylesheet">
        <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css?v=1001" rel="stylesheet" />
        <link href="assets/plugins/morris/morris-0.4.3.min.css?v=1001" rel="stylesheet">
        <!-- dataTables -->
        <link href="assets/plugins/datatables/jquery.dataTables.min.css?v=1001" rel="stylesheet" type="text/css">
        <link href="assets/plugins/datatables/responsive.bootstrap.min.css?v=1001" rel="stylesheet" type="text/css">
        
        <!--template custom css file-->        
        <link href="assets/css/style.css?v=1001" rel="stylesheet">
        
        <!-- date-->        
        <link href="assets/css/bootstrap-datetimepicker.css?v=1001" rel="stylesheet">
     
        <link href="assets/jqueryui/jquery-ui.min.css?v=1001" rel="stylesheet">        
        
        <!-- icheck --> 
        <link href="assets/plugins/icheck/custom.css?v=1001" rel="stylesheet">
        
          
        
        <!--Common plugins-->
        <script src="assets/plugins/jquery/dist/jquery.min.js?v=1001"></script>        
        <script src="assets/js/jquery.maskedinput.min.js?v=1001"></script>
        
        <script src="assets/js/modernizr-custom.js?v=1001"></script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->  

<script src="assets/plugins/bootstrap/js/bootstrap.min.js?v=1001"></script>
<script src="assets/plugins/hoe-nav/hoe.js?v=1001"></script>
<script src="assets/plugins/pace/pace.min.js?v=1001"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js?v=1001"></script>
<script src="assets/js/app.js?v=1001"></script>
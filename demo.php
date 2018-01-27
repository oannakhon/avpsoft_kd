<!DOCTYPE html>
<html dir="ltr" lang="en-US">
  <head>
    <meta charset="utf-8">
    <title>bootstrap-datepicker-thai demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="assets/plugins/bootstrap/css/bootstrap.min.css?v=1001" rel="stylesheet">
    <link href="css/datepicker.css" rel="stylesheet" media="screen">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  </head>

  <body>
    <div class="container-fluid">
      
      <div class="row-fluid">
        <div class="span4">
          <h3>Demo</h3>
          <div id="example_html">
              
              <label>th-th</label>
              <input class="input-medium" type="text" 
                     data-provide="datepicker" data-date-language="th-th" value="11/11/2560">

            
          </div>
        </div>
        
      </div>
      
    </div>
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/plugins/jquery/dist/jquery.min.js?v=1001"></script>  

    <script src="assets/plugins/datepickerb2/js/bootstrap-datepicker.js"></script>
    <script src="assets/plugins/datepickerb2/js/bootstrap-datepicker-thai.js"></script>
    <script src="assets/plugins/datepickerb2/js/locales/bootstrap-datepicker.th.js"></script>

    <script id="example_script"  type="text/javascript">
      function demo() {
        $('.datepicker').datepicker();
      }
    </script>

   
    
</body>
</html>

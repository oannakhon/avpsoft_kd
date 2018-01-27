<?php
session_start(); ini_set('session.gc_maxlifetime', 14400);
include_once 'mainfn.php';
include_once 'backup.php';
$expire=time()+60*60*24*365;
$company = explode('/', $_SERVER['REQUEST_URI']);//รหัสบริษัทของลูกค้า จาก url 
$browser = $_SERVER['HTTP_USER_AGENT'];

// check Random Id ในเครื่อง 
if(!isset($_COOKIE['RandomId'])){        
    setcookie("RandomId", RandomId(), $expire);
    header('Location: login.php'); //เพื่อป้องกัน bug ที่ไม่เจอ $COOKIES
    exit;
}else{
    //ต่ออายุให้ COOKIES อีก = $expire นาที
    setcookie("RandomId", $_COOKIE['RandomId'], $expire);   
}


//reset password by email
if(isset($_POST['resetemail'])){
    $email = (trim($_POST['resetemail']));
    //check exist email
    $result_checkemail = mysqli_query($link, "SELECT * FROM `ad_user` "
            . "WHERE `UEmail` = '$email' "
            . "AND `UserStatus` = '1'");
    if(mysqli_num_rows($result_checkemail)==1){  
    
        $emailmd5 = md5($email); 
        //sendmail by sendgrid    
        $url = 'https://api.sendgrid.com/';
        $user = 'avpenterp';
        $pass = 'ts232527'; 
        $appurl = "http://www.avpsoftware.com/app/future/resetpassword.php?id=".$emailmd5;
        $message  = "ตั้งรหัสผ่านใหม่ได้ที่ <a href=\"".$appurl."\" >".$appurl."</a>";

        $params = array(
        'api_user'  => $user,
        'api_key'   => $pass,
        'category' => 'test_category',
        'subject' => 'FUTURE มีคำขอเปลี่ยนรหัสผ่าน',    
        'to' => $email,
        'html' => $message ,
        'from'      => 'no-reply@avp.co.th',
        );

        $request =  $url.'api/mail.send.json';

        // Generate curl request
        $session = curl_init($request);
        // Tell curl to use HTTP POST
        curl_setopt ($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        // obtain response
        $response = curl_exec($session);
        curl_close($session);    
    }
}

//Check Cookies Login อยู่ในระบบตลอดไป
if(isset($_COOKIE["UserId"])){
    $_SESSION['UserId'] = $_COOKIE["UserId"];
    $_SESSION['UserFullName'] = $_COOKIE["UserFullName"];
    $_SESSION['UEmail'] = $_COOKIE["UEmail"];   
    $_SESSION['BId'] = $_COOKIE["BId"];
    $_SESSION['UserAction'] = $_COOKIE["UserAction"]; 
}

//Check Session Login ถ้าล็อกอินแล้วให้วิ่งไปหน้าหลัก
if(isset($_SESSION['UserId'])){
    header('Location: index.php');
    exit;
}

//login
if(isset($_POST['email'])){
    $email   = trim($_POST['email']);
    $password   = md5(trim($_POST['password']));
    $BId = trim($_POST['BId']);  
    
    $result = mysqli_query($link, "SELECT * FROM `ad_user` "
            . "WHERE `UEmail` LIKE '$email' "
            . "AND `Password` LIKE '$password'");   
    
    if(mysqli_num_rows($result)==1){        
        $user = mysqli_fetch_array($result);
        //Check branch permission
        $result_branch_permission = mysqli_query($link,"SELECT * FROM `branch_permission` "
                . "WHERE `UserId` = '$user[UserId]' "
                . "AND `BId` = '$BId' "
                . "AND `PerStatus` = '1'");
        if(mysqli_num_rows($result_branch_permission)==0){
            log_login($link,$email,$password,0,$BId,$_COOKIE['RandomId'],$browser);
            echo "<meta http-equiv=refresh CONTENT=\"0; URL=login.php\">"; 
            exit;
        }else{
            log_login($link,$email,$password,1,$BId,$_COOKIE['RandomId'],$browser);
            $_SESSION['UserId'] = $user['UserId'];
            $_SESSION['UserFullName'] = $user['UserFullName'];
            $_SESSION['UEmail'] = $user['UEmail'];
            $_SESSION['BId'] = $BId;
            $_SESSION['UserAction'] = $user['UserAction'];
            //OPTION REMEMBER
            if(isset($_POST['remember'])){
                $expire=time()+60*60*24*30;
                setcookie("UserId", $user['UserId'], $expire);
                setcookie("UserFullName", $user['UserFullName'], $expire);
                setcookie("UEmail", $user['UEmail'], $expire); 
                setcookie("BId", $BId, $expire); 
                setcookie("UserAction", $user['UserAction'], $expire); 
            } 
            echo "<meta http-equiv=refresh CONTENT=\"0; URL=index.php\">"; 
            exit;
        }    
    }else{
        log_login($link,$email,$password,0,$BId,$_COOKIE['RandomId'],$browser);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once 'head.php'; ?>
    </head>
    <body>
        

        <div class="page-center">
            <div class="page-center-in">
                <form class="sign-box" method="post" action="login.php">
                    <div class="sign-avatar">
                        <img src="assets/images/logoprogram.jpg" alt="" class="img-circle">
                    </div>
                    <header class="sign-title">เข้าสู่ระบบ</header>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" name="email">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                    </div>
                    <div class="form-group">                        
                        <select class="form-control" name="BId">
                            <?php
                            //สาขา
                            $result_branch = mysqli_query($link, "SELECT * FROM `branch` WHERE `BStatus` = '1' ORDER BY `BName`");
                            while($branch = mysqli_fetch_array($result_branch)){
                                echo "<option value=\"".$branch['BId']."\">".$branch['BName']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group clearfix">
                        <div class="checkbox pull-left">
                            <input type="checkbox" id="myCheckbox" name="myCheckbox" class="i-checks" name="remember">
                            <label for="myCheckbox">อยู่ในระบบตลอดไป</label>
                        </div>
                        <div class="pull-right">
                            <p><a data-toggle="modal" data-target="#myModal">ลืมรหัสผ่าน</a></p>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success rounded btn-lg">Sign in</button>                    
                </form>
                <p class="text-center"><small >รหัสเครื่อง : <?php echo $_COOKIE['RandomId']; ?></small></p>
            </div><!--page center in-->
        </div><!--page center-->
        
              <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ลืมรหัสผ่าน</h4>
        </div>          
        <div class="modal-body">            
            <form class="form-horizontal" role="form" method="post" action="login.php">
              <div class="form-group">
                <label class="control-label col-sm-3" for="email">กรอกอีเมลล์:</label>
                <div class="col-sm-6">
                  <input type="email" class="form-control" id="email" placeholder="Enter email" name="resetemail">
                </div>
                <button type="submit" class="btn btn-primary col-sm-2">ส่ง</button>             

              </div>
            </form>
            
        </div>
        
      </div>
      
    </div>
  </div>
         
              
    <!-- iCheck -->
    <script src="assets/plugins/icheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'icheckbox_flat-blue'
            });
        });
    </script>
    </body>
</html>

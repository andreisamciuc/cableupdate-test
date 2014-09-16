<?php
#############################
#	 Serpent's Code Dev 	#
#	www.SerpentsCode.com    #
# *Project Cable Guardian	#
# *Started on 17.05.2014    #
# *Version 1.0.0(17.05.2014)#
# (c) SerpentsCode.com		#
############################# 
 
// Require config file
require "includes/config.php";

// Require functions file
require "includes/functions.php";

session_start();

if(isset($_COOKIE["user_id"])){
header("Location: overview.php");
exit;
}
/*   Login   */
if(isset($_POST["username"]) and isset($_POST["password"])){
sleep(1);
//Filter post data
foreach($_POST as $key => $value) {
	$data[$key] = filter($value); // post variables are filtered
}
$user = $data["username"];
$pass_login= $data["password"];
$result= mysql_query("SELECT * FROM `admins` WHERE name='".$user."' AND password='".$pass_login."'");

	  if(mysql_num_rows($result)==0){ 
			$return_arr["status"]=0;		
			echo json_encode($return_arr); // return value 
	  }else{
	  $row=mysql_fetch_assoc($result);
	  $username = $row['name'];
	  $id = $row['admin_id'];
		// this sets session and logs user in  
       session_start();
	   session_regenerate_id (true); //prevent against session fixation attacks.
		
	   // this sets variables in the session 
		$_SESSION['user_id']= $id;  
		$_SESSION['user_name'] = $username;
		$_SESSION['user_level'] = 1;
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		
		//update the timestamp and key for cookie
		$stamp = time();
		$ckey = GenKey();
		mysql_query("update `admins` set `ctime`='$stamp', `ckey` = '$ckey' where `admin_id`='$id'") or die(mysql_error());
		
		//set a cookie 
		
	   if(isset($_POST['remember'])){
				  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				  mysql_query("update `admins` set `remember`='1' where `admin_id`='$id'") or die(mysql_error());
		
				   }else{
				  setcookie("user_id", $_SESSION['user_id'], time()+60*60*COOKIE_TIME_OUT_DEFAULT, "/");
				  setcookie("user_key", sha1($ckey), time()+60*60*COOKIE_TIME_OUT_DEFAULT, "/");
				  setcookie("user_name",$_SESSION['user_name'], time()+60*60*COOKIE_TIME_OUT_DEFAULT, "/");
				  mysql_query("update `admins` set `remember`='0' where `admin_id`='$id'") or die(mysql_error());
				   }
			$return_arr["status"]=1;		 
			echo json_encode($return_arr); // return value 
			
}  // end else
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
        <meta charset="utf-8">
        <title><?php echo $system_name;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- Link shortcut icon-->
        <link rel="shortcut icon" type="image/ico" href="images/favicon.ico"/> 
        
        <!--[if lt IE 9]>
          <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
        <link type="text/css" rel="stylesheet" href="components/bootstrap/bootstrap.css" />
        <link type="text/css" rel="stylesheet" href="css/style.css"/>
        <style type="text/css">
        html {
            background-image: none;
        }
		body{
			background-position:0 0;
			}
        label.iPhoneCheckLabelOn span {
            padding-left:0px;
        }
        #versionBar {
            background-color:#212121;
            position:fixed;
            width:100%;
            height:35px;
            bottom:0;
            left:0;
            text-align:center;
            line-height:35px;
            z-index:11;
            -webkit-box-shadow: black 0px 10px 10px -10px inset;
            -moz-box-shadow: black 0px 10px 10px -10px inset;
            box-shadow: black 0px 10px 10px -10px inset;
        }
        .copyright{
            text-align:center; font-size:10px; color:#CCC;
        }
        .copyright a{
            color:#c2cd23; text-decoration:none;
        }    
        </style>
        </head>
        <body>
         
        <div id="successLogin"></div>
        <div class="text_success"><img src="images/loader/loader_blue.gif" alt="Serpent's Code" /><span>Please wait</span></div>
        
        <div id="login">
          <div class="ribbon"></div>
          <div class="inner clearfix">
          <div class="logo"><img src="images/logo/logo_login.png" alt="Serpent's Code" /></div>
          <div class="formLogin">
         <form name="formLogin" id="formLogin" method="post">
      
                <div class="tip">
                      <input name="username" type="text" id="username_id" title="Username" />
                </div>
                <div class="tip">
                      <input name="password" type="password" id="password" title="Password" />
                </div>
      
                <div class="loginButton">
                  <div style="float:left; margin-left:-9px;">
                      <input type="checkbox" id="on_off" name="remember" class="on_off_checkbox" value="1" />
                      <span class="f_help">Remember Me</span>
                  </div>
                  <div class="pull-right" style="margin-right:-8px;">
                      <div class="btn-group">
                        <button type="button" class="btn" id="but_login">Login</button>
                      </div>
                     
                  </div>
                  <div class="clear"></div>
                </div>
      
          </form>
          </div>
        </div>
          <div class="shadow"></div>
        </div>
        
        <!--Login div-->
        <div class="clear"></div>
	     <div id="versionBar">
		
<?php echo $system_name." v".$system_version;?>
   </div>
        <!-- Link JScript-->
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="components/ui/jquery.ui.min.js"></script>
        <script type="text/javascript" src="components/form/form.js"></script>
        <script type="text/javascript" src="js/login_php.js"></script>
        </body>
        </html>
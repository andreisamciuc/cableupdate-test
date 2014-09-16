<?php
#############################
#	 Serpent's Code Dev 	#
#	www.SerpentsCode.com    #
# *Project Cable Guardian	#
# *Started on 17.05.2014    #
# *Version 1.0.0(17.05.2014)#
# (c) SerpentsCode.com		#
############################# 


/* Cable Guardian Related Functions */

//Get config settings
function get_config($config){
global $connect_db;
$sql_config = mysql_query("SELECT * FROM `settings` where `setting_name`='$config'");
$row = mysql_fetch_array($sql_config);
return $row['setting_value'];
}

//Update Settings
function update_settings($name,$value){
global $connect_db;
//$q = mysql_query("UPDATE `settings` set `setting_value`='$value' where `setting_name`='$name'");
$q = mysql_query("INSERT INTO `settings`(`setting_name`,`setting_value`) VALUES ('$name','$value') ON DUPLICATE KEY UPDATE `setting_value` = '$value'");
return $q;
}


/* Core Related */

//Login Secure Functions

function page_protect() {
session_start();

global $connect_db;


/* Secure against Session Hijacking by checking user agent */
if (isset($_SESSION['HTTP_USER_AGENT']))
{
    if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
    {
        logout();
        exit;
    }
}

// log out if cookies are deleted
	if(!isset($_COOKIE['user_id']) && !isset($_COOKIE['user_key'])){
	logout();
	}
	// log out if cookies expired!
	if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])){
	/* we double check cookie expiry time against stored in database */
	
	$cookie_user_id  = filter($_COOKIE['user_id']);
	$rs_ctime = mysql_query("select `ckey`,`ctime`,`remember` from `admins` where `admin_id` ='$cookie_user_id'") or die(mysql_error());
	list($ckey,$ctime,$remember) = mysql_fetch_row($rs_ctime);
	// coookie expiry
	//with remember me
	if($remember){
	if( (time() - $ctime) > 60*60*24*COOKIE_TIME_OUT) {

		logout();
		}
		}else{
	//without remember me
	// coookie expiry
	
	if( (time() - $ctime) > 60*60*COOKIE_TIME_OUT_DEFAULT) {

		logout();
		}
		}	
		}
/* If session not set, check for cookies set by Remember me */
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) ) 
{
	if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])){
	/* we double check cookie expiry time against stored in database */
	
	$cookie_user_id  = filter($_COOKIE['user_id']);
	$rs_ctime = mysql_query("select `ckey`,`ctime`,`remember` from `admins` where `admin_id` ='$cookie_user_id'") or die(mysql_error());
	list($ckey,$ctime,$remember) = mysql_fetch_row($rs_ctime);
	// coookie expiry
	//with remember me
	if($remember){
	if( (time() - $ctime) > 60*60*24*COOKIE_TIME_OUT) {

		logout();
		}
		}else{
	//without remember me
	// coookie expiry
	
	if( (time() - $ctime) > 60*60*COOKIE_TIME_OUT_DEFAULT) {

		logout();
		}
		}		
/* Security check with untrusted cookies - dont trust value stored in cookie. 		
/* We also do authentication check of the `ckey` stored in cookie matches that stored in database during login*/

	 if( !empty($ckey) && is_numeric($_COOKIE['user_id']) && isUserID($_COOKIE['user_name']) && $_COOKIE['user_key'] == sha1($ckey)  ) {
	 	  session_regenerate_id(); //against session fixation attacks.
	
		  $_SESSION['user_id'] = $_COOKIE['user_id'];
		  $_SESSION['user_name'] = $_COOKIE['user_name'];
		  $_SESSION['user_level'] = 1;
		  $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
	
	   } else {
	   logout();
	   }

  } else {
	header("Location: login.php");
	exit();
	}
}
}


//Log out function
function logout()
{
global $connect_db;
global $table_prefix;
session_start();

if(isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {
mysql_query("update `admins` 
			set `ckey`= '', `ctime`= '' 
			where `admin_id`='$_SESSION[user_id]' OR  `admin_id` = '$_COOKIE[user_id]'") or die(mysql_error());
}
/************ Delete the sessions****************/
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_level']);
unset($_SESSION['HTTP_USER_AGENT']);
session_unset();
session_destroy(); 

/* Delete the cookies*******************/
setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_name", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_key", '', time()-60*60*24*COOKIE_TIME_OUT, "/");

header("Location: login.php");
exit;
}			

//Check if is userid
function isUserID($username)
{
	if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) {
		return true;
	} else {
		return false;
	}
 }
//Gen ckey
function GenKey($length = 7)
{
  $password = "";
  $possible = "0123456789abcdefghijkmnopqrstuvwxyz"; 
  
  $i = 0; 
    
  while ($i < $length) { 

    
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       
    
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  return $password;

}


//Filter POST function
function filter($string) {
$string = stripslashes($string);
$string = strip_tags($string);
$string = mysql_real_escape_string($string);
return $string;
} 



?>
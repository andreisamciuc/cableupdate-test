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
require "../includes/config.php";

// Require functions file
require "../includes/functions.php";
page_protect();

$cable = filter($_POST['cable']);

if($_POST['submit'] == 'Stop'){
$q = mysql_query("DELETE FROM `settings` where `setting_name`='calibrate'");

if($q){ 
$status_msg = '<div class="alertMessage inline success"><center>Cable calibrated successfully!</center></div>';
header("refresh: 5,url=calibrate.php");
}else{
$status_msg = '<a href="calibrate.php"><div class="alertMessage inline error"><center>Error: Error at db query, click here to try again!'.mysql_error().'</center></div></a>';
}
$delete = 1;
}


if(is_numeric($cable) && !$delete){
if($_POST['submit'] == 'Start'){
$q = update_settings('calibrate',$cable);
}



if($q){ 
$status_msg = '<div class="alertMessage inline success"><center>Cable calibrated successfully!</center></div>';
header("refresh: 5,url=calibrate.php");
}else{
$status_msg = '<a href="calibrate.php"><div class="alertMessage inline error"><center>Error: Error at db query, click here to try again!'.mysql_error().'</center></div></a>';
}
}elseif(!$delete){
//Fail Red
$status_msg = '<a href="calibrate.php"><div class="alertMessage inline error"><center>Error: Cable must be a number, click here to try again!</center></div></a>';
}
echo '<!-- CSS Stylesheet-->
<link type="text/css" rel="stylesheet" href="../components/bootstrap/bootstrap.css" />
<link type="text/css" rel="stylesheet" href="../components/bootstrap/bootstrap-responsive.css" />
<link type="text/css" rel="stylesheet" href="../css/style.css"/>';
echo $status_msg;
?>

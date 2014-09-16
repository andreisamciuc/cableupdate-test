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
page_protect();

//Set pagetitle
$pagetitle = $system_name;

/*Overview Module Related */

$sql_alarms = mysql_query("SELECT `alarm_id` FROM `alarms` where `status`='New'");
$status = mysql_num_rows($sql_alarms);

if(!$status){
//Green OK
$status_msg = '<div class="alertMessage inline success"><center>System Status - OK</center></div> ';
}else{
//Fail Red
$status_msg = '<a href="mate/alarms.php"><div class="alertMessage inline error"><center>System Status - FAIL ('.$status.' new alarms, click here for details)</center></div></a>';
}
?>

<!-- Header -->
<?php include "theme/header.php";?>
<!-- End Header -->

<!-- left_menu -->
<?php include "theme/menu.php";?>
<!-- End left_menu -->

<div id="content" style="padding:20px;">
<div class="inner"><br>
<iframe name="content" src="mate/overview.php" width="100%" height="800px" frameBorder="0">Browser not compatible with iframes, please try another browser</iframe>


                    
                    
<!-- Footer -->                  
<?php include 'theme/footer.php'; ?>
<!-- End Footer -->                    

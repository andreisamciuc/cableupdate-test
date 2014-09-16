<?php
#############################
#	 Serpent's Code Dev 	#
#	www.SerpentsCode.com    #
# *Project Cable Guardian	#
# *Started on 17.05.2014    #
# *Version 1.0.0(17.05.2014)#
# (c) SerpentsCode.com		#
#############################  

/*  MySQL Database Configuration   */
//MySQL Server
$db_server = "localhost";
//MySQL Server User
$db_user = "cable";
//MySQL Password
$db_password = "akses";
//MySQL Database
$db_name = "cable_guardian";

/*  MySQL Database Connection    */
//Database Connect
$connect_db = mysql_connect($db_server,$db_user,$db_password) or die('Database connection failed!<br> Check database configuration in includes/config.php');
$db_select = mysql_select_db($db_name,$connect_db) or die('Database not found!');

/* Settings */
//Disable notifications from php
error_reporting(0);

//System Name
$system_name = "Cable Guardian";

//System Version
$system_version = "1.0.0";

/* Specify user levels */
define ("ADMIN_LEVEL", 1);

/* Cookie Settings */
define ("COOKIE_TIME_OUT", 3); //specify cookie timeout in days (with remember me checked)
define ("COOKIE_TIME_OUT_DEFAULT", 5); //specify cookie timeout in hours (without remember me)

?>
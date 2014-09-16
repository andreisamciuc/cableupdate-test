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


/*Overview Module Related */

$sql_alarms = mysql_query("SELECT `alarm_id` FROM `alarms` where `status`='New'");
$status = mysql_num_rows($sql_alarms);

if(!$status){
//Green OK
$status_msg = '<div class="alertMessage inline success"><center>System Status - OK</center></div> ';
}else{
//Fail Red
$status_msg = '<a href="alarms.php"><div class="alertMessage inline error"><center>System Status - FAIL ('.$status.' new alarms, click here for details)</center></div></a>';
}



if($_POST['submit']=='Save'){

//Get data from post
$ip_type = filter($_POST['ip_type']);
$address = filter($_POST['ip_address']);
$mask = filter($_POST['mask']);
$hostname = filter($_POST['hostname']);
$SystemAdmin = filter($_POST['SystemAdmin']);
$telnumber = filter($_POST['telnumber']);
$dns = filter($_POST['dns']);
$gateway = filter($_POST['gateway']);

$pop_port = filter($_POST['pop_port']);
$pop_user = filter($_POST['pop_user']);
$pop_password = filter($_POST['pop_password']);
$pop_socket = filter($_POST['pop_socket']);
$pop_host = filter($_POST['pop_host']);
$smtp_host = filter($_POST['smtp_host']);
$smtp_starttls = filter($_POST['smtp_starttls']);
$smtp_user = filter($_POST['smtp_user']);
$smtp_password = filter($_POST['smtp_password']);
$smtp_port = filter($_POST['smtp_port']);

        update_settings('pop_port',$pop_port);
        update_settings('pop_user',$pop_user);
        update_settings('pop_password',$pop_password);
        update_settings('pop_socket',$pop_socket);
        update_settings('pop_host',$pop_host);
        update_settings('smtp_host',$smtp_host);
        update_settings('smtp_starttls',$smtp_starttls);
        update_settings('smtp_user',$smtp_user);
        update_settings('smtp_password',$smtp_password);
        update_settings('smtp_port',$smtp_port);
        
	//Update and check the form
	if($ip_type == 'static'){
	//Update IP Static True / Dynamic False
	update_settings('ip_static',1);
	update_settings('ip_dynamic',0);
	
	}elseif($ip_type == 'dynamic'){
	//Update IP Dynamic True / Static False
	update_settings('ip_static',0);
	update_settings('ip_dynamic',1);
	
	}

        update_settings('hostname',$hostname);
        update_settings('SystemAdmin',$SystemAdmin);
        update_settings('telnumber',$telnumber);

	if(filter_var($address, FILTER_VALIDATE_IP)) {
	update_settings('ip_address',$address);
	}else{
	$error[] = "IP Address Invalid";
	}

	if(filter_var($mask, FILTER_VALIDATE_IP)) {
	update_settings('ip_netmask',$mask);
	}else{
	$error[] = "IP Mask Invalid";
	}

	if(filter_var($gateway, FILTER_VALIDATE_IP)) {
	update_settings('ip_gateway',$gateway);
	}else{
	$error[] = "IP Gateway Invalid";
	}

	if(filter_var($dns, FILTER_VALIDATE_IP)) {
	update_settings('ip_dns',$dns);
	}else{
	$error[] = "IP DNS Invalid";
	}

        //Exec bash file
        $exec_file = shell_exec("sudo -u root /var/www/setip.sh $address $mask $gateway $dns");
        //$exec_file = shell_exec("sudo -u root /var/www/setip.sh $address $mask $gateway $dns");
	//Show the message
	if(!$error){
	$msg = '<div class="alertMessage inline success"><center>Changes done!</center></div>';
	}else{
	foreach($error as $error){
	$msg .= '<div class="alertMessage inline error"><center>'.$error.'</center></div>';
	}
	
	}

} //End Post
?>
<html>
<head>
        <!-- CSS Stylesheet-->
        <link type="text/css" rel="stylesheet" href="../components/bootstrap/bootstrap.css" />
        <link type="text/css" rel="stylesheet" href="../components/bootstrap/bootstrap-responsive.css" />
        <link type="text/css" rel="stylesheet" href="../css/style.css"/>

		
        <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="components/flot/excanvas.min.js"></script><![endif]-->  
		
		<script type="text/javascript" src="../js/jquery.min.js"></script>
        <script type="text/javascript" src="../components/ui/jquery.ui.min.js"></script> 
		<script type="text/javascript" src="../components/bootstrap/bootstrap.min.js"></script>
		</head>
		<body>

<h3>System Overview <br /><?php echo $status_msg;?></h3>


<br>
<h3>Network Configuration</h3>
<form action="" method="post">
<div class="section">
<!--
<label>Static IP address</label>
<div>
<input id="ip_type" name="ip_type" type="radio" value="static" <?php if(get_config('ip_static')){ echo 'checked';};?>/>
</div>
</div>

<div class="section">
<label>Dynamic IP address</label>
<div>
<input id="ip_type" name="ip_type" type="radio" value="dynamic" <?php if(get_config('ip_dynamic')){ echo 'checked';};?>/>
</div>
</div>
-->

<div class="section">
<label>IP Address</label>
<div>
<input id="ip_address" name="ip_address" type="text" value="<?php echo get_config('ip_address');?>" />
</div>
</div>

<div class="section">
<label>Netmask</label>
<div>
<input id="mask" name="mask" type="text" value="<?php echo get_config('ip_netmask');?>" />
</div>
</div>

<div class="section">
<label>Default Gateway</label>
<div>
<input id="gateway" name="gateway" type="text" value="<?php echo get_config('ip_gateway');?>" />
</div>
</div>

<div class="section">
<label>DNS Server</label>
<div>
<input id="dns" name="dns" type="text" value="<?php echo get_config('ip_dns');?>" />
</div>
</div>

<div class="section">
<label>HostName</label>
<div>
<input id="hostname" name="hostname" type="text" value="<?php echo get_config('hostname');?>" />
</div>
</div>

<div class="section">
<label>Admins Email</label>
<div>
<input id="SystemAdmin" name="SystemAdmin" type="text" value="<?php echo get_config('SystemAdmin');?>" />
</div>
</div>

<div class="section">
<label>Local Telephone Number (this device)</label>
<div>
<input id="telnumber" name="telnumber" type="text" value="<?php echo get_config('telnumber');?>" />
</div>
</div>

<br>
<h3>Email Configuration</h3>
<br>
<h4>Incoming</h4>
<br>
<div class="section">
<label>POP3 user</label>
<div>
<input id="pop_user" name="pop_user" type="text" value="<?php echo get_config('pop_user');?>" />
</div>
</div>
<div class="section">
<label>POP3 Password</label>
<div>
<input id="pop_password" name="pop_password" type="text" value="<?php echo get_config('pop_password');?>" />
</div>
</div>
<div class="section">
<label>POP3 port</label>
<div>
<input id="pop_port" name="pop_port" type="text" value="<?php echo get_config('pop_port');?>" />
</div>
</div>
<!--
<div class="section">
<label>POP3 Socket</label>
<div>
<input id="pop_socket" name="pop_socket" type="text" value="<?php echo get_config('pop_socket');?>" />
</div>
</div> -->
<div class="section">
<label>POP3 Server</label>
<div>
<input id="pop_host" name="pop_host" type="text" value="<?php echo get_config('pop_host');?>" />
</div>
</div>
<br>
<h4>Outgoing</h4>
<br>
<div class="section">
<label>SMTP user</label>
<div>
<input id="smtp_user" name="smtp_user" type="text" value="<?php echo get_config('smtp_user');?>" />
</div>
</div>
<!--
<div class="section">
<label>SMTP ttls</label>
<div>
<input id="smtp_starttls" name="smtp_starttls" type="text" value="<?php echo get_config('smtp_starttls');?>" />
</div>
</div>-->
<div class="section">
<label>SMTP Password</label>
<div>
<input id="smtp_password" name="smtp_password" type="text" value="<?php echo get_config('smtp_password');?>" />
</div>
</div>
<div class="section">
<label>SMTP Port</label>
<div>
<input id="smtp_port" name="smtp_port" type="text" value="<?php echo get_config('smtp_port');?>" />
</div>
</div>
<div class="section">
<label>SMTP Server</label>
<div>
<input id="smtp_host" name="smtp_host" type="text" value="<?php echo get_config('smtp_host');?>" />
</div>
</div>

<div class="section last">
<div>
<input id="submit" name="submit" type="submit" value="Save" class="btn btn-success" />
</div>
</div>
</form>
<center><?php if($_POST){ echo $msg; } ?></center>


</body>
</html>

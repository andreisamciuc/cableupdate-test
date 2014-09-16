<?php
#############################
#	 Serpent's Code Dev 	#
#	www.SerpentsCode.com    #
# *Project Cable Guardian	#
# *Started on 17.05.2014    #
# *Version 1.0.0(17.05.2014)#
# (c) SerpentsCode.com		#
#############################  
?>
<!DOCTYPE html>
<html lang="en">
  <head>
        <meta charset="utf-8">
        <title><?php echo "$pagetitle - $system_name v$system_version";?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Link shortcut icon-->
        <link rel="shortcut icon" type="image/ico" href="images/favicon.ico"/> 

        <!-- CSS Stylesheet-->
        <link type="text/css" rel="stylesheet" href="components/bootstrap/bootstrap.css" />
        <link type="text/css" rel="stylesheet" href="components/bootstrap/bootstrap-responsive.css" />
        <link type="text/css" rel="stylesheet" href="css/style.css"/>

		
        <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="components/flot/excanvas.min.js"></script><![endif]-->  
		
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="components/ui/jquery.ui.min.js"></script> 
		<script type="text/javascript" src="components/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="components/ui/timepicker.js"></script>
        <script type="text/javascript" src="components/colorpicker/js/colorpicker.js"></script>
        <script type="text/javascript" src="components/form/form.js"></script>
        <script type="text/javascript" src="components/elfinder/js/elfinder.full.js"></script>
        <script type="text/javascript" src="components/datatables/dataTables.min.js"></script>
        <script type="text/javascript" src="components/fancybox/jquery.fancybox.js"></script>
        <script type="text/javascript" src="components/jscrollpane/jscrollpane.min.js"></script>
        <script type="text/javascript" src="components/editor/jquery.cleditor.js"></script>
        <script type="text/javascript" src="components/chosen/chosen.js"></script>
        <script type="text/javascript" src="components/validationEngine/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="components/validationEngine/jquery.validationEngine-en.js"></script>
        <script type="text/javascript" src="components/fullcalendar/fullcalendar.js"></script>
        <script type="text/javascript" src="components/flot/flot.js"></script>
        <script type="text/javascript" src="components/uploadify/uploadify.js"></script>       
		<script type="text/javascript" src="components/Jcrop/jquery.Jcrop.js"></script>
		<script type="text/javascript" src="components/smartWizard/jquery.smartWizard.min.js"></script>
        <script type="text/javascript" src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>

</head>        
        <body>        
		
<script type="text/javascript">
  $(document).ready(function(){
var handler= window.setInterval(function(){
    $('#servertime').load('includes/servertime.php');
},1000);
});
 </script>
 <div id="header">

                <ul id="account_info" class="pull-right">
                     <li class="setting" > <div id="servertime"><span class="icon-time"></span><img src="images/loader/loader_blue.gif"/> Loading..</div></li>
					
					<li class="setting">
				<span class="icon-user"></span>
                        Logged in as <b class="red"><?php echo $_SESSION['user_name']; ?></a></b>
                      
                    </li>
					
				
                  <li class="logout"><a href="#"><span class="icon-signout"></span> Logout</a></li>
                </ul>
				
				
            </div>
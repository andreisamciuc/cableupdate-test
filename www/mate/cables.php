<?php
/*
 * Mysql Ajax Table Editor
 *
 * Copyright (c) 2008 Chris Kitchen <info@mysqlajaxtableeditor.com>
 * All rights reserved.
 *
 * See COPYING file for license information.
 *
 * Download the latest version from
 * http://www.mysqlajaxtableeditor.com
 */
require_once('Common.php');
require_once('php/lang/LangVars-en.php');
require_once('php/AjaxTableEditor.php');
class Example1 extends Common
{
	var $Editor;
	
	function displayHtml()
	{
		?>
			<br />
	
			<div align="left" style="position: relative;"><div id="ajaxLoader1"><img src="images/ajax_loader.gif" alt="Loading..." /></div></div>
			
			<br />
			
			<div id="historyButtonsLayer" align="left">
			</div>
	
			<div id="historyContainer">
				<div id="information">
				</div>
		
				<div id="titleLayer" style="padding: 2px; font-weight: bold; font-size: 18px; text-align: center;">
				</div>
		
				<div id="tableLayer" align="center">
				</div>
				
				<div id="recordLayer" align="center">
				</div>		
				
				<div id="searchButtonsLayer" align="center">
				</div>
			</div>
			
			<script type="text/javascript">
				trackHistory = false;
				var ajaxUrl = '<?php echo $this->getAjaxUrl(); ?>';
				toAjaxTableEditor('update_html','');
			</script>
		<?php
	}
	
	function initiateEditor()
	{	
		$tableColumns['cable_id'] = array('display_text' => 'ID', 'perms' => 'EVQSXO','hidden_edit' => true ); //Added the id in the edit form but it's hidden!
		//$tableColumns['cable_number'] = array('display_text' => 'Number', 'perms' => 'ATVQSXOE');
        $tableColumns['cable_number'] = array('display_text' => 'Number', 'perms' => 'ATVQSXOE', 'val_fun' => array(&$this,'validateFun'));
		$tableColumns['group_id']     = array('display_text' => 'Admin Group', 'perms' => 'ATVQSXOE','join' => array('table' => 'groups', 'column' => 'group_id', 'display_mask' => "concat(groups.name,' ')", 'type' => 'left'));
		$tableColumns['tolerance']    = array('display_text' => 'Tolerance %', 'perms' => 'ATVQSXOE', 'default' => '10');
		//$tableColumns['average']    = array('display_text' => 'Averege', 'display_mask' => 'select avg(new_length)from (select new_length from tests where cable_id = 1 order by test_time desc limit 5)t', 'perms' => 'ATVQSXO');
		$tableColumns['cable_type']   = array('display_text' => 'Cable Type', 'perms' => 'ATVQSXOE','select_array' => array('Cable' => 'Cable', 'Switch' => 'Switch'), 'default' => 'Cable');
                $tableColumns['test_enabled'] = array('display_text' => 'Test Enabled', 'perms' => 'ATVQSXOE','select_array' => array('Enabled' => 'Enabled', 'Disabled' => 'Disabled'), 'default' => 'Enabled');
		$tableColumns['alarm_enabled']= array('display_text' => 'Alarm Enabled', 'perms' => 'ATVQSXOE','select_array' => array('Enabled' => 'Enabled', 'Disabled' => 'Disabled'), 'default' => 'Enabled');
		$tableColumns['description']  = array('display_text' => 'Description', 'perms' => 'ATVQSXOE');

		$tableName = 'cables';
		$primaryCol = 'cable_number';
		$errorFun = array(&$this,'logError');
		$permissions = 'EADM';
		
		$this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
		$this->Editor->setConfig('tableInfo','cellpadding="1" width="1000" class="mateTable"');

//		$userColumns[] = array('call_back_fun' => array(&$this,'getFullName'), 'title' => 'Full Name');
//        $this->Editor->setConfig('userColumns',$userColumns);
//        function getFullName($row){
//             $html = $row['cable_id'].' '.$row['last_name'];
//             return $html;
//        }

	}



	function validateFun($col,$val,$row){
		/* After 2 hours of research on official support found a way to do the trick */
		
        $query = "select * from cables where cable_number = '$val'\n";
        $result = mysql_query($query) or die("/n/n/nerror is".$query."<br/><br/>".mysql_error());
        $num_results = mysql_num_rows($result);
		$row_db = mysql_fetch_array($result);
		
		//Check the mode of the editor
		//If it's "update" compare the cable_id from the form(hidden) with the one from the database
		if($row['cable_id'] == $row_db['cable_id']){
		
		return true;
		}else{
			
		//Check for new insert in db
		if ($num_results > 0){
			return false;
		}else{
			return true;
		}
		
		}
    }

	function Example1()
	{
		if(isset($_POST['json']))
		{
			session_start();
			// Initiating lang vars here is only necessary for the logError, and mysqlConnect functions in Common.php. 
			// If you are not using Common.php or you are using your own functions you can remove the following line of code.
			$this->langVars = new LangVars();
			$this->mysqlConnect();
			if(ini_get('magic_quotes_gpc'))
			{
				$_POST['json'] = stripslashes($_POST['json']);
			}
			if(function_exists('json_decode'))
			{
				$data = json_decode($_POST['json']);
			}
			else
			{
				require_once('php/JSON.php');
				$js = new Services_JSON();
				$data = $js->decode($_POST['json']);
			}
			if(empty($data->info) && strlen(trim($data->info)) == 0)
			{
				$data->info = '';
			}
			$this->initiateEditor();
			$this->Editor->main($data->action,$data->info);
			if(function_exists('json_encode'))
			{
				echo json_encode($this->Editor->retArr);
			}
			else
			{
				echo $js->encode($this->Editor->retArr);
			}
		}
		else if(isset($_GET['mate_export']))
		{
            session_start();
            ob_start();
            $this->mysqlConnect();
            $this->initiateEditor();
            echo $this->Editor->exportInfo();
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-type: application/x-msexcel");
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$this->Editor->tableName.'.csv"');
            exit();
        }
		else
		{
			$this->displayHeaderHtml();
			$this->displayHtml();
			$this->displayFooterHtml();
		}
	}
}
$lte = new Example1();
?>
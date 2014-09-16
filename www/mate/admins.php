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
	        $tableColumns['group_id'] = array('display_text' => 'Id', 'perms' => '');
		$tableColumns['name']     = array('display_text' => 'Name', 'perms' => 'ATVQSXOE');
		$tableColumns['key_holder']   = array('display_text' => 'Key Holder', 'perms' => 'ATVQSXOE','join' => array('table' => 'admins', 'column' => 'admin_id', 'display_mask' => "concat(admins.name,' ')", 'type' => 'left'));
		$tableColumns['contact_1']    = array('display_text' => 'Contact 1', 'perms' => 'ATVQSXOE','join' => array('table' => 'admins', 'column' => 'admin_id', 'display_mask' => "concat(admins.name,' ')", 'type' => 'left'));
		$tableColumns['contact_2']    = array('display_text' => 'Contact 2', 'perms' => 'ATVQSXOE','join' => array('table' => 'admins', 'column' => 'admin_id', 'display_mask' => "concat(admins.name,' ')", 'type' => 'left'));
		$tableColumns['contact_3']    = array('display_text' => 'Contact 3', 'perms' => 'ATVQSXOE','join' => array('table' => 'admins', 'column' => 'admin_id', 'display_mask' => "concat(admins.name,' ')", 'type' => 'left'));
		$tableColumns['contact_4']    = array('display_text' => 'Contact 4', 'perms' => 'ATVQSXOE','join' => array('table' => 'admins', 'column' => 'admin_id', 'display_mask' => "concat(admins.name,' ')", 'type' => 'left'));
		$tableColumns['contact_5']    = array('display_text' => 'Contact 5', 'perms' => 'ATVQSXOE','join' => array('table' => 'admins', 'column' => 'admin_id', 'display_mask' => "concat(admins.name,' ')", 'type' => 'left'));
		$tableColumns['contact_6']    = array('display_text' => 'Contact 6', 'perms' => 'ATVQSXOE','join' => array('table' => 'admins', 'column' => 'admin_id', 'display_mask' => "concat(admins.name,' ')", 'type' => 'left'));
		


		$tableName = 'groups';
		$primaryCol = 'group_id';
		$errorFun = array(&$this,'logError');
		$permissions = 'EADM';

		require_once('php/AjaxTableEditor.php');
		$this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
		$this->Editor->setConfig('tableInfo','cellpadding="1" width="800" class="mateTable"');
		//$this->Editor->tableName = 'admin_id';
		$this->Editor->setConfig('tableTitle','Administrator Groups');
	}
	
		
	function Example1()
	{
		if(isset($_POST['json']))
		{
			session_start();
			// Initiating lang vars here is only necessary for the logError, and mysqlConnect functions in Common.php. 
			// If you are not using Common.php or you are using your own functions you can remove the following line of code.
			$this->langVars = new LangVars();

			$this->tableName = 'pidsfdf';

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

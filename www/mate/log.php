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
	        <meta http-equiv="refresh" content="10">
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
	    $tableColumns['log_id']     = array('display_text' => 'Log Id','perms' => '');
		$tableColumns['time']       = array('display_text' => 'Time',  'perms' => 'VCTXQ');
		$tableColumns['value']      = array('display_text' => 'Value', 'perms' => 'VCTXQ');
		
		$tableName = 'log';
		$primaryCol = 'log_id';
		$errorFun = array(&$this,'logError');
		$permissions = 'DM';
		
		require_once('php/AjaxTableEditor.php');
		$this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
		$this->Editor->setConfig('tableInfo','cellpadding="1" width="800" class="mateTable"');
		$this->Editor->setConfig('orderByColumn','log_id');
		$this->Editor->setConfig('ascOrDesc','desc');
		$this->Editor->setConfig('modifyRowSets',array(&$this,'changeBgColor'));
	}

    function changeBgColor($rowSets,$rowInfo,$rowNum)
    {
       if (strpos($rowInfo['value'], 'sms sent') !== false){
          $rowSets['bgcolor']= '#99ccff';
       }else if (strpos($rowInfo['value'], 'sms received:') !== false){
          $rowSets['bgcolor']= '#6699ff';
       }else if (strpos($rowInfo['value'], 'tolerance exceeded') !== false){
          $rowSets['bgcolor']= '#ff6633';
       }
       return $rowSets;
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

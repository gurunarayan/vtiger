<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Documents_Settings_Action extends Vtiger_Save_Action {

	public function process(Vtiger_Request $request) {
		global $docsSettings,$log;
$configText = <<< EOD


EOD;
		$moduleName = $request->get('srcmodule');
		$docsSettings[$moduleName]  = $request->get('settings');
		 foreach($docsSettings as $key=>$value){
			if ($value == 'true')  $docsSettings[$key] = true;
			if ($value == 'false') $docsSettings[$key] = false;
		} 
		$existing = $this->checkModule($moduleName,$request->get('settings'));
		//manasa modified on 04th feb 2016
		
		$db = PearDatabase::getInstance();
		//$db->setDebug(true);
		$tabid =getTabid($moduleName);
		$folderid = $docsSettings[$moduleName]['folderslist'];
		$db->pquery("update vtiger_documentmodulesrel set defaultfolder=? where tabid=?",Array($folderid,$tabid));
		//manasa code ended here.
		$response = new Vtiger_Response();
		$response->setResult("Settings saved for Docs ++");
		$response->emit();
	
	}
	public function checkModule($moduleName,$valuess){
		require_once 'modules/Documents/config.php';
		$flagExists=false;
		if($vtDocsSettings=='' || $vtDocsSettings == null){
			$docsSettings =array();
		}
		else{
		$docsSettings = array_merge(array(), $vtDocsSettings);
		}
		
	 if(is_array($vtDocsSettings) && count($vtDocsSettings)>1){
		 
		 foreach($docsSettings as $key => $val)
			{
				if ($key == $moduleName){
					$docsSettings[$key] = $valuess;
					$flagExists=true;
				}
			}
			if($flagExists==false){
				$docsSettings[$moduleName]=$valuess;
			}
			
	 }
	 else{
		  $docsSettings[$moduleName]  =$valuess;
		 }
		 if(is_array($docsSettings)){
		  $userRecordModel = Users_Record_Model::getCurrentUserModel();
		  $docsSettingsContent = "<?php\n\n$configText\n\n// Settings saved on ".date("M d Y H:i:s")." by ".$userRecordModel->getDisplayName()."\n\n\$vtDocsSettings = ".var_export($docsSettings,true).";\n?>";
		file_put_contents('modules/Documents/config.php', $docsSettingsContent);
		
		 }
return true;

	}
}
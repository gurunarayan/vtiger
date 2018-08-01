<?php

/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_OS2UserSettings_ConfigUserSettingsEditorSaveAjax_Action extends Settings_Vtiger_Basic_Action {

	public function process(Vtiger_Request $request) {
		global $adb;
		$response = new Vtiger_Response();
		$qualifiedModuleName = $request->getModule(false);
		$updatedFields = $request->get('updatedFields');
		$moduleModel = Settings_OS2UserSettings_ConfigUserSettingsModule_Model::getInstance();
		//print_r($updatedFields); exit;
		/*if ($updatedFields) {
			$moduleModel->set('updatedFields', $updatedFields);
			$status = $moduleModel->save();

			if ($status === true) {
				$response->setResult(array($status));
			} else {
				$response->setError(vtranslate($status, $qualifiedModuleName));
			}
		} else {
			$response->setError(vtranslate('LBL_FIELDS_INFO_IS_EMPTY', $qualifiedModuleName));
		}*/
		
		if(!empty($updatedFields))
		{
			$failed_logins_criteria = $updatedFields['failed_logins_criteria'];	
			$max_login_attempts = $updatedFields['max_login_attempts'];	
			$UC_NAME_ONE = $updatedFields['UC_NAME_ONE'];	
			$UC_EMAIL_ID_ONE = $updatedFields['UC_EMAIL_ID_ONE'];	
			$UC_NAME_TWO = $updatedFields['UC_NAME_TWO'];	
			$UC_EMAIL_ID_TWO = $updatedFields['UC_EMAIL_ID_TWO'];	
			$Working_Hours_start = $updatedFields['Working_Hours_start'];	
			$Working_Hours_end = $updatedFields['Working_Hours_end'];	
			$totaldays = $updatedFields['totaldays'];	
			$itemcount = $updatedFields['itemcount'];	
			$weeks = array();
			$holiday_lbl_val = array();
			
			if($totaldays != "")
			{
				$totaldays = (int)$totaldays;
				for($w=0; $w<=$totaldays; $w++)
				{
					$days_cnt = "week_".$w;
					$day = $updatedFields[$days_cnt];
					if(isset($day))
					{
						$weeks[$w] = $day;
					}
				}
			}
			
			if($itemcount != "")
			{
				$itemcount = (int)$itemcount;
				for($h=0; $h<=$itemcount; $h++)
				{
					$holiday_lbl_cnt = "holiday_lbl_".$h;
					$holiday_val_cnt = "holiday_val_".$h;
					$holiday_lbl = $updatedFields[$holiday_lbl_cnt];
					$holiday_val = $updatedFields[$holiday_val_cnt];
					
					if(isset($holiday_lbl))
					{
						$holiday_lbl_val[$holiday_lbl] = $holiday_val;
					}
				}
			}
			
			$weeks = json_encode($weeks);
			$holiday_lbl_val = json_encode($holiday_lbl_val);
			
			$adb->pquery("UPDATE vtiger_user_config SET status = '0'");
			
			$adb->pquery("INSERT INTO vtiger_user_config (failed_logins_criteria,max_login_attempts,UC_NAME_ONE,UC_EMAIL_ID_ONE,UC_NAME_TWO, UC_EMAIL_ID_TWO, Working_Hours_start, Working_Hours_end, weeks, holiday_lbl_val) VALUES ('$failed_logins_criteria','$max_login_attempts','$UC_NAME_ONE', '$UC_EMAIL_ID_ONE', '$UC_NAME_TWO', '$UC_EMAIL_ID_TWO','$Working_Hours_start','$Working_Hours_end', '$weeks', '$holiday_lbl_val')");
			
			$result = $adb->getLastInsertID();
			$responseData = array("message"=>"Success.", 'success'=>true, 'data'=>json_encode($result));
			$response->setResult($responseData);
			
			
		} else {
			$response->setError(vtranslate('LBL_FIELDS_INFO_IS_EMPTY', $qualifiedModuleName));
		}
		
		$response->emit();
	}
        
    public function validateRequest(Vtiger_Request $request) { 
            $request->validateWriteAccess(); 
        }
}

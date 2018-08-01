<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

 
class Settings_OS2UserSettings_ConfigUserSettingsModule_Model extends Settings_Vtiger_Module_Model {

	var $fileName = 'config.user.php';
	var $completeData;
	var $data;

	/**
	 * Function to read config file
	 * @return <Array> The data of config file
	 */
	public function readFile() {
		if (!$this->completeData) {
			//$this->completeData = file_get_contents($this->fileName);
		}
		return $this->completeData;
	}
	
	/**
	 * Function to get CompanyDetails Menu item
	 * @return menu item Model
	 */
	public function getMenuItem() {
		$menuItem = Settings_Vtiger_MenuItem_Model::getInstance('OS2 User Settings');
		return $menuItem;
	}
    
	/**
	 * Function to get Edit view Url
	 * @return <String> Url
	 */
	public function getEditViewUrl() {
		$menuItem = $this->getMenuItem(); 
		return '?module=OS2UserSettings&parent=Settings&view=ConfigUserSettingsEditorEdit&block='.$menuItem->get('blockid').'&fieldid='.$menuItem->get('fieldid');
	}

	/**
	 * Function to get Detail view Url
	 * @return <String> Url
	 */
	public function getDetailViewUrl() {
		$menuItem = $this->getMenuItem();
		return '?module=OS2UserSettings&parent=Settings&view=ConfigUserSettingsEditorDetail&block='.$menuItem->get('blockid').'&fieldid='.$menuItem->get('fieldid');
	}

	/**
	 * Function to get Viewable data of config details
	 * @return <Array>
	 */
	public function getViewableData() {
		if (!$this->getData()) {
			$fileContent = $this->readFile();
			$pattern = '/\$([^=]+)=([^;]+);/';
			$matches = null;
			$matchesFound = preg_match_all($pattern, $fileContent, $matches);
			$configContents = array();
			if ($matchesFound) {
				$configContents = $matches[0];
			}
			$data = array();
			$editableFileds = $this->getEditableFields();			 
			
			foreach ($editableFileds as $fieldName => $fieldDetails) {
				foreach ($configContents as $configContent) {
					if (strpos($configContent, $fieldName)) {
						$fieldValue = explode(' = ', $configContent);
						$fieldValue = $fieldValue[1];
						 
						$data[$fieldName] = str_replace(";", '', str_replace("'", '', $fieldValue));
						break;
					}
				}
			}
			$this->setData($data);
		}
		return $this->getData();
	}
	

	/**
	 * Function to get editable fields
	 * @return <Array> list of field names
	 */
	public function getEditableFields() {
		return array(			
			'failed_logins_criteria'	=> array('label' => 'Failed Logins Criteria',	'fieldType' => 'picklist'),			
			'max_login_attempts'		=> array('label' => 'Max Login Attempts',	'fieldType' => 'input'),			
			'UC_NAME_ONE'				=> array('label' => 'Security Observer Name 1',			'fieldType' => 'input'),
			'UC_EMAIL_ID_ONE'			=> array('label' => 'Security Observer Email 1',		'fieldType' => 'input'),			
			'UC_NAME_TWO'				=> array('label' => 'Security Observer Name 2',			'fieldType' => 'input'),
			'UC_EMAIL_ID_TWO'			=> array('label' => 'Security Observer Email 2',		'fieldType' => 'input'),			
			/*'TERMS_AND_CONDITIONS'		=> array('label' => 'LBL_TERMS_AND_CONDITIONS',	'fieldType' => 'textarea'),	*/		
			'Working_Hours_start'		=> array('label' => 'Working Hours Start',	'fieldType' => 'input'),			
			'Working_Hours_end'			=> array('label' => 'Working Hours End',	'fieldType' => 'input'),			
			'working_week_days'			=> array('label' => 'Working Week Days',	'fieldType' => 'checkbox')		
		);
	}
	
	/**
	 * Function to save the data
	 */
	public function save() {
		global $log;
		$fileContent = $this->completeData;
		$updatedFields = $this->get('updatedFields');	
		  		
		#Start: Modified By : anji ; date : 15-12-2016 ; Desc : convert holidays list to array and updated to config file */		 
		$holidays = array();
		for($i=0;$i<11;$i++){
			if(!empty($updatedFields['holiday_lbl_'.$i])){$holidays[$updatedFields['holiday_lbl_'.$i]] = $updatedFields['holiday_val_'.$i];}
			unset($updatedFields['holiday_lbl_'.$i]);unset($updatedFields['holiday_val_'.$i]);
		}	
				
		#holidays array updated to config file 
		$holidays = json_encode($holidays);
		$fieldName = 'Holidays';
		$patternString = "\$%s = '%s';";
		$pattern = '/\$' . $fieldName . '[\s]+=([^;]+);/';
		$replacement = sprintf($patternString, $fieldName, $holidays);
		$fileContent = preg_replace($pattern, $replacement, $fileContent);
		
		#working_week_days
		$working_week_days = array();
		for($i=0;$i<7;$i++){
			if(!empty($updatedFields['week_'.$i])){
				$working_week_days[] = $updatedFields['week_'.$i];}
			unset($updatedFields['week_'.$i]);
		}
		$updatedFields['working_week_days'] = implode(',',$working_week_days);
			 
		/* End */		 
		 
		$validationInfo = $this->validateFieldValues($updatedFields);
		if ($validationInfo === true) {
			foreach ($updatedFields as $fieldName => $fieldValue) {
			 
				$pattern = '/\$' . $fieldName . '[\s]+=([^;]+);/';
				$patternString = "\$%s = '%s';";	
				if($fieldName == 'Working_Hours_start') {
					$replacement = sprintf($patternString, $fieldName, $fieldValue);
				}else
				{
					$replacement = sprintf($patternString, $fieldName, ltrim($fieldValue, '0'));
				}
				
				$fileContent = preg_replace($pattern, $replacement, $fileContent);
			} 
		 
			$filePointer = fopen($this->fileName, 'w');
			fwrite($filePointer, $fileContent);
			fclose($filePointer);
		}
		return $validationInfo;
	}

	/**
	 * Function to validate the field values
	 * @param <Array> $updatedFields
	 * @return <String> True/Error message
	 */
	public function validateFieldValues($updatedFields){
		if (!filter_var($updatedFields['UC_EMAIL_ID_ONE'], FILTER_VALIDATE_EMAIL) || !filter_var($updatedFields['UC_EMAIL_ID_TWO'], FILTER_VALIDATE_EMAIL)) {
			return "LBL_INVALID_EMAILID";
		} else if(preg_match ('/[\'";?><]/', $updatedFields['UC_NAME_ONE']) || preg_match ('/[\'";?><]/', $updatedFields['UC_NAME_TWO'])) {
			return "LBL_INVALID_SUPPORT_NAME";
		} 
		return true;
	}

	/**
	 * Function to get the instance of Config module model
	 * @return <Settings_Vtiger_ConfigModule_Model> $moduleModel
	 */
	public static function getInstance() {
		$moduleModel = new self();
		$moduleModel->getViewableData();
		return $moduleModel;
	}
	
	
	/* Start: Created By : anji ; date : 15-12-2016 ; Desc : functions for add and update  holidays list in config.user.php file */		 
	/**
	 * Function to get editable fields for holidays
	 * @return <Array> list of field names
	 */
	public function getEditableHolidayFields(){		 
		 	$holidayFields = array(	 
				'0'	=> array('label' => 'lbl_holiday_0',	'fieldType' => 'input'),
				'1'	=> array('label' => 'lbl_holiday_1',	'fieldType' => 'input'),
				'2'	=> array('label' => 'lbl_holiday_2',	'fieldType' => 'input'),
				'3'	=> array('label' => 'lbl_holiday_3',	'fieldType' => 'input'),
				'4'	=> array('label' => 'lbl_holiday_4',	'fieldType' => 'input'),
				'5'	=> array('label' => 'lbl_holiday_5',	'fieldType' => 'input'),
				'6'	=> array('label' => 'lbl_holiday_6',	'fieldType' => 'input'),
				'7'	=> array('label' => 'lbl_holiday_7',	'fieldType' => 'input'),
				'8'	=> array('label' => 'lbl_holiday_8',	'fieldType' => 'input'),
				'9'	=> array('label' => 'lbl_holiday_9',	'fieldType' => 'input'),
				'10'=> array('label' => 'lbl_holiday_10',	'fieldType' => 'input')
			);
		 
		return $holidayFields;
	}
	/**
	 * Function to get Holidays data in config.user.php file
	 * @return <Array> list of holidays and dates 
	 */ 
	public function getViewableHolidayData()
	{
		include_once('config.user.php');
		$data = array();
		$Holidays = json_decode($Holidays);
		foreach($Holidays as $holiday_name => $holiday_date){							 
				$data['lbl'][] = $holiday_name;
				$data['val'][]= $holiday_date;		 
			}	 		
		return $data;
	}
	/**
	 * Function to get picklist values
	 * @param <String> $fieldName
	 * @return <Array> list of module names
	 */
	public function getPicklistValues($fieldName) {
		if($fieldName === 'failed_logins_criteria') { 
				$folders =  array(	'0' => 'No check for failed login',
									'1' => 'IP Check',
									'2' => 'Calendar Check',
									'3' => 'Calendar and IP Check',
									'4' => 'Password Check',
									'5' => 'PW and IP Check',
									'6' => 'PW and Calendar Check',
									'7' => 'PW, Calendar and IP Check'
									);
				return $folders;
        }
		if($fieldName === 'week') { 
			$folders=array(	'0' => 'Sunday',
							'1' => 'Monday',
							'2' => 'Tuesday',
							'3' => 'Wednesday',
							'4' => 'Thursday',
							'5' => 'Friday',
							'6' => 'Saturday'
						);
				return $folders;
		}		
		return array('true', 'false');
	}
	
	
	 
	
	
}

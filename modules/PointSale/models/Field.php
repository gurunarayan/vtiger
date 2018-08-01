<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
include_once 'vtlib/Vtiger/Field.php';

/**
 * Vtiger Field Model Class
 */
class PointSale_Field_Model extends Vtiger_Field_Model {

	public function getFieldInfo() {
		$currentUser = Users_Record_Model::getCurrentUserModel();
        $fieldDataType = $this->getFieldDataType();
		$fieluitype=$this->get('uitype');
		$this->fieldInfo['mandatory'] = $this->isMandatory();
		$this->fieldInfo['presence'] = $this->isActiveField();
		$this->fieldInfo['quickcreate'] = $this->isQuickCreateEnabled();
		$this->fieldInfo['masseditable'] = $this->isMassEditable();
		$this->fieldInfo['defaultvalue'] = $this->hasDefaultValue();
		$this->fieldInfo['type'] = $fieldDataType;
		$this->fieldInfo['name'] = $this->get('name');
		$this->fieldInfo['label'] = vtranslate($this->get('label'), $this->getModuleName());

        if($fieldDataType == 'picklist' || $fieldDataType == 'multipicklist' || $fieldDataType == 'multiowner') {
            $pickListValues = $this->getPicklistValues();
            if(!empty($pickListValues)) {
                $this->fieldInfo['picklistvalues'] = $pickListValues;
            } else {
				$this->fieldInfo['picklistvalues'] = array();
			}
        }

		if($this->getFieldDataType() == 'date' || $this->getFieldDataType() == 'datetime'){
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$this->fieldInfo['date-format'] = $currentUser->get('date_format');
		}

		if($this->getFieldDataType() == 'time') {
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$this->fieldInfo['time-format'] = $currentUser->get('hour_format');
		}

		if($this->getFieldDataType() == 'currency') {
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$this->fieldInfo['currency_symbol'] = $currentUser->get('currency_symbol');
			$this->fieldInfo['decimal_seperator'] = $currentUser->get('currency_decimal_separator');
			$this->fieldInfo['group_seperator'] = $currentUser->get('currency_grouping_separator');
		}

		if($this->getFieldDataType() == 'owner') {
			$userList = $currentUser->getAccessibleUsers();
			$groupList = $currentUser->getAccessibleGroups();
			$pickListValues = array();
			$pickListValues[vtranslate('LBL_USERS', $this->getModuleName())] = $userList;
			$pickListValues[vtranslate('LBL_GROUPS', $this->getModuleName())] = $groupList;
			$this->fieldInfo['picklistvalues'] = $pickListValues;
		}
			$this->fieldInfo['fielduitype'] =$fieluitype;
		return $this->fieldInfo;
	}

	public function isAjaxEditable() {
		$ajaxRestrictedFields = array('4','72','515','514','537','538','545','546','516','540','19','12','175');
		if(!$this->isEditable() || in_array($this->get('uitype'), $ajaxRestrictedFields)) {
			return false;
		}
		return true;
	}
}

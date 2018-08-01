<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
require_once ('modules/Contacts/Contacts.php');
require_once 'include/Webservices/Create.php';	
class PointSale_Create_Action extends Vtiger_Action_Controller {
	
	public function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');

		if(!Users_Privileges_Model::isPermitted($moduleName, 'Save', $record)) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}
	
	function process(Vtiger_Request $request) {
		
		global $adb,$current_user;
		$info = array();
		
		$name = $request->get('name');
		$mobile = $request->get('mobile');
		$state = $request->get('state');
		$city = $request->get('city');
		$country = $request->get('country');
		$currenuserid = $_SESSION['authenticated_user_id'];
		$currentUser = $current_user->retrievecurrentuserinfofromfile($currenuserid);
	
		$contact = new Contacts();
        $contact->column_fields['lastname'] = $name;
		$contact->column_fields['mobile'] = $mobile;
		$contact->column_fields['mailingstate'] = $state;
		$contact->column_fields['mailingcity'] = $city;
		$contact->column_fields['mailingcountry'] = $country;
		$contact->column_fields['assigned_user_id'] = 1;
		//$contact->column_fields['description'] = $description;
		$save  = $contact->save('Contacts');
		$id = $contact->id;
		echo json_encode(array("data1"=> $id));
		
	}
	
}
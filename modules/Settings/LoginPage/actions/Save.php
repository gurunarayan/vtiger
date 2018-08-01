<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_LoginPage_Save_Action extends Settings_Vtiger_Basic_Action {
	public function process(Vtiger_Request $request) {
		global $adb;
		$rows	=	$request->get('row');
		$columns	=	$request->get('column');
		header("Location:index.php?module=LoginPage&parent=Settings&view=List&block=2&fieldid=33&x=$rows&y=$columns");
	}
}

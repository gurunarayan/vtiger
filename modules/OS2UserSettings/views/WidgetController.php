<?php
/* ********************************************************************************
 * The content of this file is subject to the Global Search ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */

class OS2UserSettings_WidgetController_View extends Vtiger_Index_View {

    function __construct() {
        parent::__construct();        
    }
   
    function checkPermission(Vtiger_Request $request) {
        return true;
    }

    function process (Vtiger_Request $request) {
      $mode = $request->getMode();
		if(!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
		return;
        
    }

  
}
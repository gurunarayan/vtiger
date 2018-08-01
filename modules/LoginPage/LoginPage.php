<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *******************************************************************************/

class LoginPage {

 	 
	 var $log;

     function __construct() {
		$this->copiedFiles = Array();
		$this->failedCopies = Array();
		$this->ignoredFiles = Array();
		$this->failedDirectories = Array();
		$this->savedFiles = Array();
		if (isset($_SERVER['COMSPEC']) || isset($SERVER['WINDIR']))
			$hostOSType='Windows';
		else
			$hostOSType='Linux';
		$this->log = LoggerManager::getLogger('account');
	}

    function vtlib_handler($moduleName, $eventType)
	{
		 
        global $adb, $log;
        $log->debug("start of loginpage installation"); 
		if($eventType == 'module.preinstall'){
			 
		}
		if($eventType == 'module.postinstall')
		{
            #insert phylos link  vtiger_settings_field 
            $fieldid = $adb->getUniqueID('vtiger_settings_field');
            $seq_res = $adb->query("SELECT max(sequence) AS max_seq FROM vtiger_settings_field WHERE blockid =1");
            $seq = 1;
            if ($adb->num_rows($seq_res) > 0) {
                $cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
                if ($cur_seq != null)	$seq = $cur_seq + 1;
            }
            $entryExists = $adb->num_rows($adb->query("SELECT name FROM vtiger_settings_field where name = 'VTLogin Page Themes'"));
            if ( $entryExists == 0){
            $adb->pquery('INSERT INTO vtiger_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence,active, pinned)
                VALUES (?,?,?,?,?,?,?,?,?)', array($fieldid, 1, 'VTLogin Page Themes', 'portal_icon.png', 'Allows you to configure the Login Page', 'index.php?module=LoginPage&parent=Settings&view=List', $seq, 0, 1));
            }  
		}

 		if($eventType == 'module.preupdate') {

		}

 		if($eventType == 'module.postupdate') {
             #insert phylos link  vtiger_settings_field 
            $fieldid = $adb->getUniqueID('vtiger_settings_field');
            $seq_res = $adb->query("SELECT max(sequence) AS max_seq FROM vtiger_settings_field WHERE blockid =1");
            $seq = 1;
            if ($adb->num_rows($seq_res) > 0) {
                $cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
                if ($cur_seq != null)	$seq = $cur_seq + 1;
            }
            $entryExists = $adb->num_rows($adb->query("SELECT name FROM vtiger_settings_field where name = 'VTLogin Page Themes'"));
            if ( $entryExists == 0){
            $adb->pquery('INSERT INTO vtiger_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence,active, pinned)
                VALUES (?,?,?,?,?,?,?,?,?)', array($fieldid, 1, 'VTLogin Page Themes', 'portal_icon.png', 'Allows you to configure the Login Page', 'index.php?module=LoginPage&parent=Settings&view=List', $seq, 0, 1));
            }  
		}
	}
}
?>

<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *******************************************************************************/

class OS2TimeTracker {

	 var $log;

     function __construct() {
		$this->copiedFiles = Array();
		$this->failedCopies = Array();
		$this->ignoredFiles = Array();
		$this->failedDirectories = Array();
		$this->savedFiles = Array();
		$this->log = LoggerManager::getLogger('account');
	}

 	function vtlib_handler($moduleName, $eventType)
	{
		require_once('include/utils/utils.php');
		require_once('include/utils/VtlibUtils.php');
		require_once('vtlib/Vtiger/Module.php');
		global $adb;

		if($eventType == 'module.postinstall')
		{
			$tabid = getTabid($moduleName);
			$fieldid = $adb->getUniqueID('vtiger_settings_field');
			
			$count = $adb->pquery("SELECT * FROM vtiger_settings_field WHERE name =?", array('OS2 Time Tracker'));
			if ($adb->num_rows($count) == 0) {
				$fieldid = $adb->getUniqueID('vtiger_settings_field');
				$blockid = getSettingsBlockId('LBL_OTHER_SETTINGS');
				
				$seq_res = $adb->query("SELECT max(sequence)+1 AS max_seq FROM vtiger_settings_field WHERE blockid = ?",array($blockid));
				$seq = 1;
				if ($adb->num_rows($seq_res) > 0) {
					$cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
					if ($cur_seq != null)	$seq = $cur_seq + 1;
				}
				$adb->pquery('INSERT INTO vtiger_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence,active, pinned)
					VALUES (?,?,?,?,?,?,?,?,?)', array($fieldid, $blockid, 'OS2 Time Tracker', 'portal_icon.png', 'TimeTracker', 'index.php?module=OS2TimeTracker&parent=Settings&view=Settings', $seq, 0, 1));
					
				$adb->pquery("UPDATE vtiger_settings_field_seq SET id=?",array($fieldid));
			
			}
			
			//To Add Link to calculate Respective Fields
			$count_link = $adb->pquery("SELECT * FROM vtiger_links WHERE linklabel =?", array('TimeTracker'));
			if ($adb->num_rows($count_link) == 0) {
				$link_count = $adb->query("SELECT MAX(linkid) AS max_linkid FROM vtiger_links");
				if ($adb->num_rows($link_count) > 0) {
					$linkid = $adb->query_result($link_count, 0, 'max_linkid');
					if ($linkid != null){
						$maxlinkid = $linkid + 1;
					}	
				}
				
				$adb->pquery('INSERT INTO vtiger_links(linkid, tabid, linktype, linklabel, linkurl, linkicon, sequence, handler_path, handler_class, handler)
					VALUES (?,?,?,?,?,?,?,?,?,?)', array($maxlinkid, 0, 'HEADERSCRIPT', 'TimeTracker', 'layouts/v7/modules/Settings/OS2TimeTracker/resources/OS2TimeTracker.js', '', '', '', '',''));

				$adb->pquery("UPDATE vtiger_links_seq SET id=?",array($maxlinkid));
			}
			
			$adb->pquery('CREATE TABLE IF NOT EXISTS vtiger_timetracker_settings (
			  modules text NOT NULL,
			  fields text NOT NULL
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1',array());
			
			$adb->pquery('CREATE TABLE IF NOT EXISTS timetracker_data (
			  userid int(11) NOT NULL,
			  recordid int(10) NOT NULL,
			  form_data text NOT NULL,
			  status varchar(100) NOT NULL,
			  record_name varchar(100) NOT NULL
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1',array());
			
		}
		

 		if($eventType == 'module.preupdate') {

		}

 		if($eventType == 'module.postupdate') {
			
		}
	}
}
?>

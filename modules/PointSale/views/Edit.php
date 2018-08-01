<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

Class PointSale_Edit_View extends Vtiger_Edit_View {

	public function process(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');
        $recordModel = $this->record;
        if(!$recordModel){
            if (!empty($recordId)) {
                $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
            } else {
                $recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
            }
        }

		global $adb;
		/*$pickBlocks = Array();
		$pickListsBlocks = Array();
		$pbResult = $adb->pquery("SELECT DISTINCT picklistfieldname FROM vtiger_pickblocks, vtiger_tab where vtiger_tab.tabid=vtiger_pickblocks.tabid and name= ?", Array($moduleName));
		while ($plrow = $adb->fetch_row($pbResult)){
			$picklistfieldname = $plrow["picklistfieldname"];
			//select vtiger_pickblocks.*, vtiger_blocks.blocklabel from vtiger_pickblocks, vtiger_blocks where vtiger_pickblocks.blockid = vtiger_blocks.blockid
			$plbResult = $adb->pquery("SELECT vtiger_pickblocks.*, vtiger_blocks.blocklabel FROM vtiger_pickblocks, vtiger_tab, vtiger_blocks where vtiger_tab.tabid=vtiger_pickblocks.tabid and name= ? and picklistfieldname=? and vtiger_pickblocks.blockid = vtiger_blocks.blockid", Array($moduleName, $picklistfieldname));
			while ($plbrow = $adb->fetch_row($plbResult)){
				$pickListsBlocks[$plbrow["picklistitem"]] = Array($plbrow["blockid"], $plbrow["blocklabel"]);
			}
			$pickBlocks[$picklistfieldname] = $pickListsBlocks;
			$pickListsBlocks = Array();
		}
		$pbResult = $adb->pquery("SELECT * from vtiger_dvpanels,vtiger_tab where vtiger_tab.tabid=vtiger_dvpanels.tabid and name= ?", Array($moduleName));
		$panelTabs = Array();
		while ($row = $adb->fetch_row($pbResult)){
			$panelTabs[$row["panellabel"]] = $row["blockids"];
		}*/

		$viewer = $this->getViewer($request);
		parent::process($request);
	}

	/**
	 * Function to get the list of Script models to be included
	 * @param Vtiger_Request $request
	 * @return <Array> - List of Vtiger_JsScript_Model instances
	 */
	function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);

		$jsFileNames = array(
				'libraries.jquery.multiplefileupload.jquery_MultiFile',
				"libraries.jquery.ckeditor.ckeditor",
				"libraries.jquery.ckeditor.adapters.jquery",
				'modules.Vtiger.resources.CkEditor',
				'libraries.vtDZinerSupport',
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}
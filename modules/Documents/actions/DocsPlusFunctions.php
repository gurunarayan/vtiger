<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Documents_DocsPlusFunctions_Action extends Vtiger_Action_Controller {

	function __construct() {
		$this->exposeMethod('searchUnlinkedEntities');
		$this->exposeMethod('createDetailViewWidget');
		$this->exposeMethod('deleteDetailViewWidget');
		$this->exposeMethod('getLinkedModulesList');
		$this->exposeMethod('saveModuleSettings');
		$this->exposeMethod('getTreeStructureJSON');
		$this->exposeMethod('updateTreeStructure');
		$this->exposeMethod('updateTreeElement');
	}

	function process(Vtiger_Request $request) {
		$mode = $request->getMode();
		if($mode){
			$this->invokeExposedMethod($mode,$request);
		}
	}

	public function validateRequest(Vtiger_Request $request) {
		$request->validateWriteAccess();
	}

	function searchUnlinkedEntities(Vtiger_Request $request){
		// returns array of modules found, null array for none
		// Ajax in calling script will handle emitted result
		$adb = PearDatabase::getInstance();
		$query="SELECT * FROM vtiger_tab where isentitytype=1 and tabid NOT IN (8,9,10,16,28,35,39,40) AND tabid NOT IN (SELECT tabid from vtiger_documentmodulesrel)";
		$params= array();
		$qresult=$adb->pquery($query, $params);
		$unlinkedModules = Array();
		while ($row=$adb->fetch_array($qresult)){
			$unlinkedModules[] = $row;
		}

		$result = array();
		$result['success'] = TRUE;
		$result['num_rows'] = $adb->num_rows($qresult);
		$result['modulesList'] = $unlinkedModules;

		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}

	function createDetailViewWidget(Vtiger_Request $request){
		$adb = PearDatabase::getInstance();
		$tabid = $request->get('tabid');
		$query="INSERT vtiger_links SET linkid=(SELECT id+1 FROM vtiger_links_seq), tabid=?, linktype='DETAILVIEWSIDEBARWIDGET', linklabel='Document Attachments', linkurl='module=Documents&view=AttachDocuments'";
		$params= array($tabid);
		$qresult=$adb->pquery($query, $params);
		$query="UPDATE vtiger_links_seq SET id=(SELECT MAX(linkid) FROM vtiger_links)";
		$qresult=$adb->pquery($query, $params);

		$result = array();
		$result['success'] = TRUE;

		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}

	function deleteDetailViewWidget(Vtiger_Request $request){
		$adb = PearDatabase::getInstance();
		$tabid = $request->get('tabid');
		$params= array($tabid);
		$query="DELETE FROM vtiger_links WHERE linktype='DETAILVIEWSIDEBARWIDGET' and tabid=?";
		$qresult=$adb->pquery($query, $params);

		$result = array();
		$result['success'] = TRUE;

		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}

	function getLinkedModulesList(){
	}

	function saveModuleSettings(){
	// UPDATE vtiger_documentmodulesrel SET
	}

	function getTreeStructureJSON(){
	}

	function updateTreeStructure(){
	}

	function updateTreeElement(){
	}
}

/*
	public function oprocess(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$userRecordModel = Users_Record_Model::getCurrentUserModel();
		$reportSettings = $request->get('settings');
		foreach($reportSettings as $key=>$value){
			if ($value == 'true')  $reportSettings[$key] = true;
			if ($value == 'false') $reportSettings[$key] = false;
		}
		$reportSettingsContent = "<?php\n\n$configText\n\n// Settings saved on ".date("M d Y H:i:s")." by ".$userRecordModel->getDisplayName()."\n\n\$vtReportSettings = ".var_export($reportSettings,true).";\n?>";
		file_put_contents('modules/Reports/config.php', $reportSettingsContent);
		$response = new Vtiger_Response();
		$response->setResult("Settings saved for Docs++");
		$response->emit();
	}

	function NoteBookCreate(Vtiger_Request $request){
		$adb = PearDatabase::getInstance();

		$userModel = Users_Record_Model::getCurrentUserModel();
		$linkId = $request->get('linkId');
		$noteBookName = $request->get('notePadName');
		$noteBookContent = $request->get('notePadContent');

		$date_var = date("Y-m-d H:i:s");
		$date = $adb->formatDate($date_var, true);

		$dataValue = array();
		$dataValue['contents'] = $noteBookContent;
		$dataValue['lastSavedOn'] = $date;

		$data = Zend_Json::encode((object) $dataValue);

		$query="INSERT INTO vtiger_module_dashboard_widgets(linkid, userid, filterid, title, data) VALUES(?,?,?,?,?)";
		$params= array($linkId,$userModel->getId(),0,$noteBookName,$data);
		$adb->pquery($query, $params);
		$id = $adb->getLastInsertID();

		$result = array();
		$result['success'] = TRUE;
		$result['widgetId'] = $id;
		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();

	}
*/
<?php
class Documents_Settings_View extends Vtiger_Index_View {
	function preProcess(Vtiger_Request $request) {
		parent::preProcess($request);
	}
	function process(Vtiger_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = 'Documents';
		$viewer->assign('QUALIFIED_MODULE', "Settings");
		$moduleList = Documents_Module_Model::getAllModuleAssociations();
		$vtDocsSettings = Documents_Module_Model::getDDSettings();
		$viewer->assign('DOCSSETTINGS', $vtDocsSettings);
		$viewer->assign('ASSOCIATED_MODULES', $moduleList);
		$tree = $this->getVirtualTreeDocs();
		$viewer->assign('FOLDERS',$tree);   // ---Vivek
		$viewer->view('DocumentsSettings.tpl', $moduleName);
	}

    /**
	 * Function to get the list of Script models to be included
	 * @param Vtiger_Request $request
	 * @return <Array> - List of Vtiger_JsScript_Model instances
	 */

	public function getHeaderScripts(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$headerScriptInstances = parent::getHeaderScripts($request);
		$jsFileNames = array(
			"modules.$moduleName.resources.Settings",
			"modules.Vtiger.resources.List",
			"modules.$moduleName.resources.List",
		);
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
	public function getVirtualTreeDocs(){
	$db = PearDatabase::getInstance();
	$floderquery = "SELECT folderid, foldername FROM vtiger_attachmentsfolder order by foldername ASC";
	$fres = $db->query($floderquery);
	$numRows = $db->num_rows($fres);
	$folders = array();
	for ($i = 0; $i < $numRows; $i++) {
		$folderid = $db->query_result($fres, $i, "folderid");
		$foldername = $db->query_result($fres, $i, "foldername");
		$folders[]= array($folderid,$foldername);
	}
	return $folders;
}

}
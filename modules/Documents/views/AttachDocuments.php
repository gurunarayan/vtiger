<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */


class Documents_AttachDocuments_View extends Vtiger_PopupAjax_View
{
    
    protected $noRecords = false;
    
    public function __construct()
    {
        
    }
    
    function process(Vtiger_Request $request)
    {
        switch ($request->get('operation')) {
            default:
                $this->renderWidgetUI($request);
                break;
        }
    }
    
    function renderWidgetUI(Vtiger_Request $request)
    {
        require_once 'modules/Documents/config.php';  
        $sourceModule = $request->get('source_module');
        $viewer       = $this->getViewer($request);
        $viewer->assign('MODULE_NAME', $request->getModule());
        
        $selectedfolder = $vtDocsSettings[$sourceModule]['folderslist'];
        $tree           = $this->getVirtualTreeDocs();
        $viewer->assign('FOLDERS', $tree);  
        $viewer->assign('SOURCEMODULE', $sourceModule);
        $viewer->assign('MULTIPLE_RECORDS', 'checked=checked');
        $viewer->assign('SELECTEDFOLDER', $selectedfolder);
        $viewer->assign('SCANALLOWED', 0);
        $viewer->assign('RECORDID', $request->get('record'));
        
       
        $foldersdata  = Documents_Module_Model::getAllFolders();
        $array_folder = array();
        $i            = 0;
        
        foreach ($foldersdata as $fdata) {
            
            $array_folder[$i]['id']     = $fdata->get('folderid');
            $array_folder[$i]['text']   = $fdata->get('foldername');
            $array_folder[$i]['parent'] = ($fdata->get('parentid') == 0 || $fdata->get('parentid') == NULL) ? '#' : $fdata->get('parentid');
            $array_folder[$i]['type']   = 'default';
            $i++;
        }
        $new = array();
        foreach ($array_folder as $a) {
            $new[$a['parent']][] = $a;
        }
        
        $viewer->assign('FOLDERS_DATA', json_encode($array_folder));
       
        $viewer->view('AttachDocuments.tpl', $request->getModule());
    }
    /**
     * Function to get the list of Script models to be included
     * @param Vtiger_Request $request
     * @return <Array> - List of Vtiger_JsScript_Model instances
     */
    public function getHeaderScripts(Vtiger_Request $request)
    {
        $moduleName = $request->getModule();
        return $this->checkAndConvertJsScripts(array(
            "~libraries/bootstrap/js/bootstrap-popover.js",
            "~/libraries/jstree/jstree.js"
        ));
        
    }
    
    public function validateRequest(Vtiger_Request $request)
    {
        
    }
   
    public function getVirtualTreeDocs()
    {
        $db          = PearDatabase::getInstance();
        $floderquery = "SELECT folderid, foldername FROM vtiger_attachmentsfolder order by foldername ASC";
        $fres        = $db->query($floderquery);
        $numRows     = $db->num_rows($fres);
        $folders     = array();
        for ($i = 0; $i < $numRows; $i++) {
            $folderid   = $db->query_result($fres, $i, "folderid");
            $foldername = $db->query_result($fres, $i, "foldername");
            $folders[]  = array(
                $folderid,
                $foldername
            );
        }
        return $folders;
    }
}

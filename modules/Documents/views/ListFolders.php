<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class Documents_ListFolders_View extends Vtiger_PopupAjax_View
{
    
    protected $noRecords = false;
    
    public function __construct()
    {
     
    }
    
    function process(Vtiger_Request $request)
    {
        
        $viewer       = $this->getViewer($request);
        $filesdata    = $this->getVirtualTreeDocumentsDetails();
        $foldersdata  = Documents_Module_Model::getAllFolders();
        $array_folder = array();
        $i            = 0;
        
        foreach ($foldersdata as $fdata) {
            
            $array_folder[$i]['id']     = $fdata->get('folderid');
            $array_folder[$i]['text']   = $fdata->get('foldername');
            $array_folder[$i]['parent'] = ($fdata->get('parentid') == 0 || $fdata->get('parentid') == NULL) ? '#' : $fdata->get('parentid');
            $array_folder[$i]['type']   = 'default';            
            
            $files_count = $filesdata[$fdata->get('folderid')];
            if ($files_count > 0) {
                foreach ($filesdata[$fdata->get('folderid')] as $files) {
                    $i++;
                    $array_folder[$i]['id']     = 'f-' . $files['notesid'];
                    $array_folder[$i]['text']   = $files['filename'];  
                    $array_folder[$i]['parent'] = $fdata->get('folderid');
                    $array_folder[$i]['type']   = 'file';
                }
            }
            
            $i++;
        }
        $new = array();
        foreach ($array_folder as $a) {
            $new[$a['parent']][] = $a;
        }
        
        $viewer->assign('FOLDERS_DATA', json_encode($array_folder));
        
        $moduleName = $request->getModule();
        
        $viewer->assign('FIRSTTIME', $firstime);
        $viewer->assign('STATE', 'home');
        $viewer->assign('SOURCEMODULE', $request->get('sourcemodule'));
        $viewer->assign('MODULE_NAME', $moduleName);
        $viewer->assign('SCRIPTS', $this->getHeaderScripts($request));
        $viewer->assign('CURRENT_USER', Users_Record_Model::getCurrentUserModel());
        $viewer->view('ListFolders.tpl', $moduleName);
    }
    
    function getHeaderScripts(Vtiger_Request $request)
    {
        
        $jsFileNames           = array(
            '~/libraries/jstree/jstree.js'
        );
        $headerScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        return $headerScriptInstances;
    }
    public function validateRequest(Vtiger_Request $request)
    {
         
    }
    public function getVirtualTreeDocumentsDetails()
    {
        $db            = PearDatabase::getInstance();
        $floderquery   = "SELECT snrel.attachmentsid,notesid,folderid,filename,modifiedtime,smownerid,title FROM vtiger_seattachmentsrel as snrel left join vtiger_crmentity  on snrel.crmid = vtiger_crmentity.crmid left join vtiger_notes on vtiger_notes.notesid=snrel.crmid where vtiger_crmentity.deleted=0 and filename != '' order by folderid ASC";
        $fres          = $db->query($floderquery);
        $numRows       = $db->num_rows($fres);
        $folderdetails = array();
        for ($i = 0; $i < $numRows; $i++) {
            $folderid      = $db->query_result($fres, $i, "folderid");
            $notesid       = $db->query_result($fres, $i, "notesid");
            $attachmentsid = $db->query_result($fres, $i, "attachmentsid");
            $filename      = $db->query_result($fres, $i, "filename");
            
            $link = '<a title="Download File" href="index.php?module=Documents&action=DownloadFile&record=' . $notesid . '&fileid=' . $attachmentsid . '">Download</a>';
            
            $view_link = '<a title="View" href="index.php?module=Documents&view=Detail&record=' . $notesid . '">View</a>';
            
            $modifiedtime = $db->query_result($fres, $i, "modifiedtime");
            $smownerid    = $db->query_result($fres, $i, "smownerid");
            $title        = $db->query_result($fres, $i, "title");
            
            $folderdetails[$folderid][] = array(
                'notesid' => $notesid,
                'attachmentsid' => $attachmentsid,
                'filename' => $filename,
                'download' => $link,
                'smownerid' => $smownerid,
                'modifiedtime' => $modifiedtime,
                'view_link' => $view_link,
                'title' => $title
            );
        }
        return $folderdetails;
    } 
    
}

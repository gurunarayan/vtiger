<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include('modules/Vtiger/uitypes/Owner.php');
class Documents_Folders_View extends Vtiger_Index_View
{
    
    public function checkPermission(Vtiger_Request $request)
    {
        $moduleName = $request->getModule();
        
        if (!Users_Privileges_Model::isPermitted($moduleName, 'EditView')) {
            throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $moduleName));
        }
    }
    
    function process(Vtiger_Request $request)
    {
        
        $viewer       = $this->getViewer($request);
        $filesdata    = $this->getVirtualTreeDocumentsDetails();
        $foldersdata  = Documents_Module_Model::getAllFolders();
        $array_folder = array();
        $i            = 0;
        
        foreach ($foldersdata as $fdata) {
            
            $files_count = $filesdata[$fdata->get('folderid')];
            
            $array_folder[$i]['id']     = $fdata->get('folderid');
            $array_folder[$i]['text']   = $fdata->get('foldername');
            $array_folder[$i]['parent'] = ($fdata->get('parentid') == 0 || $fdata->get('parentid') == NULL) ? '#' : $fdata->get('parentid');
            $array_folder[$i]['type']   = 'default';
            $delete = '';
            if ($files_count == 0) {  
                $delete = '| <a href="#" class="clsDeleteFolder"  data-action="dataDeleteFolder" data-id="' . $fdata->get('folderid') . '"><i title="Delete" class="icon-trash alignMiddle"></i></a> '; }  

            $array_folder[$i]['data']   = array(
                'tools' => '<a data-action="dataEditFolder" href="javascript:"  data-id="' . $fdata->get('folderid') . '"><i title="Edit" class="icon-pencil alignMiddle"></i></a> | <a href="#" class="clsAddFolder"  data-action="dataAddFolder" data-id="' . $fdata->get('folderid') . '"><i title="Add Folder" class="icon-plus alignMiddle"></i> </a> '. $delete .''
            );
             
           
            if ($files_count > 0) {
                foreach ($filesdata[$fdata->get('folderid')] as $files) {
                    
                    $i++;
                    $array_folder[$i]['id']            = 'f' . $files['notesid'];
                    $array_folder[$i]['text']          = $files['filename']; // link
                    $array_folder[$i]['parent']        = $fdata->get('folderid');
                    $array_folder[$i]['type']          = 'file';
                    $array_folder[$i]['attachmentsid'] = $files['attachmentsid'];
                    $array_folder[$i]['notesid']       = $files['notesid'];
                    $array_folder[$i]['data']          = array(
                        'modifiedtime' => $files['modifiedtime'],
                        'smownerid' => $files['smownerid'],
                        'tools' => $files['view_link'] . ' | ' . $files['download'] . ' | '. $files['delete'] ,
                        'title' => $files['title'],
                        'module' => $files['module'],
                        'user' => $files['user']
                    );
                }
            }
            
            $i++;
        }
        $new = array();
        foreach ($array_folder as $a) {
            $new[$a['parent']][] = $a;
        }
      // echo '<pre>'; print_r($array_folder);die;
        $viewer->assign('FOLDERS_DATA', json_encode($array_folder));
        
        $moduleName = $request->getModule();
        $viewer->assign('MODULE_NAME', $moduleName);
        $viewer->assign('SCRIPTS', $this->getHeaderScripts($request));
        $viewer->assign('CURRENT_USER', Users_Record_Model::getCurrentUserModel());
        $viewer->view('Folders.tpl', $moduleName);
    }
    
    function getHeaderScripts(Vtiger_Request $request)
    { 
        $jsFileNames = array(
           /* '~/libraries/jquery/jquery-1.8.0.js',
            '~/libraries/jquery/jquery.dataAttr.min.js?v=6.4.0',*/
            'libraries.jstree.jstree',           
            'libraries.jstree.jstreegrid_new' 
        );
        $headerScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        return $headerScriptInstances;
    }
    public function getVirtualTreeDocumentsDetails()
    {  
        $db            = PearDatabase::getInstance();
         
        $floderquery   = "SELECT  DISTINCT vs.attachmentsid, vn.notesid,vn.folderid,vn.filename,vc.modifiedtime,vc.smcreatorid,vc.smownerid,vn.title,vsn.crmid,vcn.setype as module, concat(vu.first_name,vu.last_name) as user
		FROM vtiger_seattachmentsrel as vs
		left join vtiger_crmentity vc  on vs.crmid = vc.crmid
		left join vtiger_notes vn on vn.notesid=vs.crmid
		left join vtiger_senotesrel vsn on vsn.notesid=vn.notesid
		left join vtiger_crmentity vcn on vcn.crmid=vsn.crmid
		left join vtiger_users vu on vc.smcreatorid = vu.id
		where vc.deleted=0 and vn.filename != ''  group by vs.attachmentsid";
        $fres          = $db->query($floderquery);
        $numRows       = $db->num_rows($fres);
        $folderdetails = array();
        for ($i = 0; $i < $numRows; $i++) {
            $folderid      = $db->query_result($fres, $i, "folderid");
            $notesid       = $db->query_result($fres, $i, "notesid");
            $attachmentsid = $db->query_result($fres, $i, "attachmentsid");
            $filename      = $db->query_result($fres, $i, "filename");
            
            $link = '<a title="Download File" href="index.php?module=Documents&action=DownloadFile&record=' . $notesid . '&fileid=' . $attachmentsid . '"><i title="Download" class="icon-download-alt alignMiddle"></i></a>';
            
            $view_link = '<a title="View" href="index.php?module=Documents&view=Detail&record=' . $notesid . '"><i title="Complete Details" class="icon-th-list alignMiddle"></i></a>';

            $delete = '<a href="#" class="clsDeleteFile"  data-action="dataDeleteFile" data-id="' . $notesid . '"><i title="Delete" class="icon-trash alignMiddle"></i></a>';
            
            $modifiedtime = $db->query_result($fres, $i, "modifiedtime");
            $smownerid    = $db->query_result($fres, $i, "smownerid");
            
            $smownerid = Vtiger_Owner_UIType::getDisplayValue($smownerid);
            
            $title  = $db->query_result($fres, $i, "title");
            $module = $db->query_result($fres, $i, "module");
            $user   = $db->query_result($fres, $i, "user");
            if (trim($module) == '') {
                $module = 'Documents';
            }
            
            $folderdetails[$folderid][] = array(
                'notesid' => $notesid,
                'attachmentsid' => $attachmentsid,
                'filename' => $filename,
                'download' => $link,
                'smownerid' => $smownerid,
                'modifiedtime' => $modifiedtime,
                'view_link' => $view_link,
                'title' => $title,
                'module' => $module,
                'user' => $user,
                'delete' => $delete
            );
        }
        return $folderdetails;
    }
    
}
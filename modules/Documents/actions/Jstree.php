<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once  'modules/Users/Users.php';
include_once  'include/Webservices/Create.php';
include_once 'modules/Vtiger/models/Record.php';

class Documents_Jstree_Action extends Vtiger_SaveAjax_Action {
	 
	
	public function process(Vtiger_Request $request) {
		
		global $adb;
		$adb = PearDatabase::getInstance();
		$mode = $request->get('mode'); 
		$response = new Vtiger_Response();
		 
		switch ($mode) {
			case "rename":
				try{
				 
					$fid = $request->get('fid');
					$ftext = $request->get('ftext');
					$fparent = $request->get('fparent');
					$ftype = $request->get('ftype');

					
					if(is_numeric($fid))
					{
						if($ftype == 'default')
						{						 
							$adb->pquery("UPDATE `vtiger_attachmentsfolder` SET  `foldername` =  ? WHERE  `folderid` = ?;", array($ftext, $fid));	
							
							$responseData = array("message"=>"Renamed to $ftext.\n", 'success'=>true,'type' => 'rename');
							
						} else { 
							$responseData = array("message"=>"File Name Cant Change.\n", 'success'=>true,'type' => 'rename');
						}
						
					}else{
						$responseData = $this->create($request);
					}
					$response->setResult($responseData);
				}catch(Exception $e) {
					$response->setError($e->getCode(), "Please study the errorcode is ".$e->getCode());
				}
				$response->emit();				
				break;
				
			case "create":
				try{
					$responseData = $this->create($request);					
					$response->setResult($responseData);
				}catch(Exception $e) {
					$response->setError($e->getCode(), "Please study the errorcode is ".$e->getCode());
				}
				$response->emit();				
				break;

			case "delete":
				try{
					$folder_id = $request->get('folder_id');
					$adb->pquery("DELETE FROM `vtiger_attachmentsfolder`  WHERE  `folderid` = ?;", array($folder_id));	
					$responseData = array('success'=>true, 'message'=>'Folder Deleted Successfully.');
					$response->setResult($responseData);

				}catch(Exception $e) {
					$response->setError($e->getCode(), "Please study the errorcode is ".$e->getCode());
				}
				$response->emit();				
				break;

				
			case "deleteFile":
				try{
					$file_id = $request->get('file_id');
					//$adb->setDebug(true);
					$moduleName = 'Documents';
					$recordModel = Vtiger_Record_Model::getInstanceById($file_id, $moduleName);
					$moduleModel = $recordModel->getModule();
					$recordModel->delete();	

					$responseData = array('success'=>true, 'message'=>'File Deleted Successfully.');
					$response->setResult($responseData);

				}catch(Exception $e) {
					$response->setError($e->getCode(), "Please study the errorcode is ".$e->getCode());
				}
				$response->emit();				
				break;
				
			case "dndnode":
				try{
					$fid = $request->get('fid');
					$ftext = $request->get('ftext');
					$fparent = $request->get('fparent');
					$ftype = $request->get('ftype');				 
					$old_parent = $request->get('old_parent');	
					 
					if($ftype == 'default'){	
						if($fparent =='#' ){$fparent = 0;}
						$responseData = array("message"=>"Folder Moved. \n", 'success'=>true);						 
						$adb->pquery("UPDATE `vtiger_attachmentsfolder` SET  `parentid` =  ? WHERE  `folderid` = ?;", array($fparent, $fid));	
						
					} else { 
						 
						$fid = substr($fid, 1);							 
						$adb->pquery("UPDATE `vtiger_notes` SET  `folderid` =  ? WHERE  `notesid` = ?;", array($fparent, $fid));
					
						$responseData = array("message"=>"File Moved. \n", 'success'=>true);
					}
				 
					$response->setResult($responseData);					
					
				}catch(Exception $e) {
					$response->setError($e->getCode(), "Please study the errorcode is ".$e->getCode());
				}
				$response->emit();				
				break; 
			
			default:
				$response->setError($e->getCode(), "Please study the errorcode is ".$e->getCode());
				$response->emit();
		}
	}
	
	public function create($request){
		$fid = $request->get('fid');
		$folderName = $request->get('ftext');
		$fparent = $request->get('fparent');
		$ftype = $request->get('ftype');				 
		$folderDesc = ' ';				 
		
		if($ftype == 'default')
		{						 
			$folderModel = Documents_Folder_Model::getInstance();
			$folderModel->set('foldername', $folderName);
			$folderModel->set('description', $folderDesc);
			$folderModel->set('parentid', $fparent);

			if ($folderModel->checkDuplicate()) { 
				$responseData = array("message"=>"Folder Name already exists.\n", 'success'=>true,'type' => 'new');
			}else{
				$folderModel->save();
				$responseData = array('success'=>true, 'message'=>vtranslate('LBL_FOLDER_SAVED', $moduleName), 'info'=>$folderModel->getInfoArray(),'type' => 'new');
			}			
		} else { 
			$responseData = array("message"=>"File Cont create.\n", 'success'=>true,'type' => 'new');
		}		
		return $responseData;		
	} 
   
}
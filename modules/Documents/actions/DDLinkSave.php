<?php
////Function added by SL for DD files enable :28th July '15:start
class Documents_DDLinkSave_Action extends Vtiger_SaveAjax_Action {
       public function process(Vtiger_Request $request) {
		 $db = PearDatabase::getInstance();
		 $currentUserModel = Users_Record_Model::getCurrentUserModel();
		 $moduleName = $request->getModule();
		 $relatedRecordId = $request->get('srcrecordid');
		 $fileurl = $request->get('fileurl');
 
			require_once 'data/CRMEntity.php';
 		
				 
			
		$document = CRMEntity::getInstance('Documents');
			$document = new Documents();
			//print_r($document);
			$filename='url'.rand();
			$document->column_fields['notes_title']      =   $filename;

			$document->column_fields['fileurl']		 = decode_html($fileurl);
			$document->column_fields['filestatus']       = 1;
			$document->column_fields['filelocationtype'] = 'K';
			$document->column_fields['assigned_user_id'] = $currentUserModel->getId();
			$document->parent_id = $relatedRecordId;
 			$document->save('Documents');
			  $query = "UPDATE vtiger_notes  SET filename = ?,filelocationtype = ? ,fileurl=? WHERE  notesid = ?";
			$db->pquery($query,array(decode_html($filename),'K',decode_html($fileurl),$document->id));
			// Link file attached to document
			
			$act_doc = 'insert into vtiger_senotesrel values(?,?)';
			$res = $db->pquery($act_doc,array($relatedRecordId,$document->id));
			
		$response = new Vtiger_Response();
		$response->setResult(array('success' => true ));
		$response->emit();
	 }
}

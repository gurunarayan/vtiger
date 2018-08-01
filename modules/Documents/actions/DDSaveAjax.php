<?php
/*Class for saving drag and drop images by SL */
class Documents_DDSaveAjax_Action extends Vtiger_SaveAjax_Action {
	 public function process(Vtiger_Request $request) {
		 $db = PearDatabase::getInstance();
		 $currentUserModel = Users_Record_Model::getCurrentUserModel();
		 $moduleName = $request->getModule();
		 $relatedRecordId = $request->get('srcrecordid');
		 $folderid = $request->get('folderid'); 
			require_once 'data/CRMEntity.php';
 		for($i=0; $i<count($_FILES['file']['name']); $i++){
			$result= '';
			
			$filename=$_FILES['file']['name'][$i];
			$filesize=$_FILES['file']['size'][$i];
			$filetmp=$_FILES['file']['tmp_name'][$i];
			$filetype=$_FILES['file']['type'][$i];
			$attachmentId = $this->saveAttachment($filename,$filetmp,$filetype);
 
			if($attachmentId > 0) {
				 
			
		$document = CRMEntity::getInstance('Documents');
			$document = new Documents();
			//print_r($document);
			$document->column_fields['notes_title']      =   preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);;
			$document->column_fields['filename']         = $filename;
			$document->column_fields['filestatus']       = 1;
			$document->column_fields['filelocationtype'] = 'i';
			$document->column_fields['filedownloadcount']= 0;
			$document->column_fields['folderid']         = $folderid;
			$document->column_fields['filesize']		 = $filesize;
			$document->column_fields['filetype']		 = $filetype;
			$document->column_fields['assigned_user_id'] = $currentUserModel->getId();
			$document->parent_id = $relatedRecordId;
 			$document->save('Documents');
 			
			$query = "UPDATE vtiger_notes SET filename = ? ,filesize = ?, filetype = ? , filelocationtype = ? , filedownloadcount = ?,folderid=? WHERE notesid = ?";
			$db->pquery($query,array(decode_html($filename),$filesize,$filetype,'I',0,$folderid,$document->id));
			// Link file attached to document
			$db->pquery("INSERT INTO vtiger_seattachmentsrel(crmid, attachmentsid) VALUES(?,?)",
			Array($document->id, $attachmentId));
			$act_doc = 'insert into vtiger_senotesrel values(?,?)';
			$res = $db->pquery($act_doc,array($relatedRecordId,$document->id));
			}
		 } 
		$response = new Vtiger_Response();
		$response->setResult(array('success' => true ));
		$response->emit();
	 }
	 /**
	 * Save an attachment
	 * @global PearDataBase $db
	 * @global Array $upload_badext
	 * @global Users $current_user
	 * @return Integer
	 */
	public function saveAttachment($fName,$fileTmpName,$fileType) {
		$db = PearDatabase::getInstance();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();

		$uploadPath = decideFilePath();
		$fileName = $fName;

		if(!empty($fileName)) {
			$attachid = $db->getUniqueId('vtiger_crmentity');

			//sanitize the filename
			$binFile = sanitizeUploadFileName($fileName, vglobal('upload_badext'));
			$fileName = ltrim(basename(" ".$binFile));

			$saveAttchment = move_uploaded_file($fileTmpName , $uploadPath.$attachid . "_" . $binFile);
			if($saveAttchment) {
				$description = $fileName;
				$date_var = $db->formatDate(date('YmdHis'), true);
				$usetime = $db->formatDate($date_var, true);

				$db->pquery("INSERT INTO vtiger_crmentity(crmid, smcreatorid, smownerid,
				modifiedby, setype, description, createdtime, modifiedtime, presence, deleted)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
						Array($attachid, $currentUserModel->getId(), $currentUserModel->getId(), $currentUserModel->getId(), "Documents Attachment", $description, $usetime, $usetime, 1, 0));

				 ;
				$db->pquery("INSERT INTO vtiger_attachments SET attachmentsid=?, name=?, description=?, type=?, path=?",
						Array($attachid, $fileName, $description, $fileType, $uploadPath));

				return $attachid;
			}
		}
		return false;
	}
}
?>
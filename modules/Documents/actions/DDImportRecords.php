<?php
////Function added by SL for DD files enable :28th July '15:start
class Documents_DDImportRecords_Action extends Vtiger_SaveAjax_Action {
       public function process(Vtiger_Request $request) {
		 $db = PearDatabase::getInstance();
		 $accountid=0;
		 $accountname='';
		 $Assignedto=0;
		 $currentUserModel = Users_Record_Model::getCurrentUserModel();
		 $moduleName = $request->getModule();
		 $operation = $request->get('operation');
		 $vtigerdb = mysql_connect('localhost', 'root','w3lcom3@123');//connection for 5.4
		    
if ($vtigerdb){

 $db_selected = mysql_select_db('c1lopa0504dev', $vtigerdb);//connection for 5.4 dbname
 
 

 $querylp54="SELECT vtiger_notes.notesid as 'id',vtiger_notes.title as 'Title',vtiger_notes.note_no as 'Document No',case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as 'AssignedTo',vtiger_crmentity.createdtime as 'Created Time',vtiger_crmentity.modifiedtime as 'Modified Time',vtiger_notes.nameoforganizationrelatedtothisinfo as 'Nameorg',vtiger_notes.fileversion as 'Version',vtiger_notes.notecontent as 'Note',vtiger_notes.pictures as 'Pictures',vtiger_notes.filename as 'filename',vtiger_notes.filetype as 'filetype',vtiger_notes.filelocationtype as 'filelocation type',vtiger_notes.filedownloadcount as 'count',vtiger_notes.filestatus as 'status',vtiger_notes.filesize as 'size',vtiger_notes.folderid as 'folder', case when (vtiger_users.user_name not like '') then CONCAT(vtiger_users.first_name,' ',vtiger_users.last_name) else vtiger_groups.groupname end as user_name FROM vtiger_notes
				inner join vtiger_crmentity
					on vtiger_crmentity.crmid=vtiger_notes.notesid
				LEFT JOIN vtiger_attachmentsfolder on vtiger_notes.folderid=vtiger_attachmentsfolder.folderid
				LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid=vtiger_users.id  LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid=vtiger_groups.groupid    WHERE  vtiger_crmentity.deleted=0 and notesid=50710";
 
 $result = mysql_query($querylp54);
 
   $count = mysql_num_rows($result);
  

while ($row = mysql_fetch_array($result)) {   

     $row['id'];
    
   $row['Title'];
 	
    echo $row['AssignedTo'];
	
    $row['Nameorg'];
	  $row['Nameorg'];

	if($row['Nameorg']>0){
	 $organisationquery = "select accountname from vtiger_account where accountid='".$row['Nameorg']."'";
$orgresult = mysql_query($organisationquery);
  $orgcount = mysql_num_rows($orgresult);
while ($orgrow = mysql_fetch_array($orgresult)) {
     $accountname = $orgrow['accountname'];
	}
	}
	//echo "select notesid from vtiger_notes,vtiger_crmentity where crmid=notesid and title='". $row['Title']."' and filename= '".$row['filename'] ."' and filetype='". $row['filetype']."' and vtiger_crmentity.deleted=0";
   $loparex63query= "select notesid from vtiger_notes,vtiger_crmentity where crmid=notesid and title= ? and filename= ? and filetype= ? and vtiger_crmentity.deleted=0 ";
	$download_count = $db->pquery($loparex63query,array($row['Title'],$row['filename'],$row['filetype'])) ;
	  $rowCount = $db->num_rows($download_count);
	if($rowCount >0){
	  $notesid = $db->query_result($download_count, 0, 'notesid');
	  $lporgquery="select accountid from vtiger_account where accountname=?";
	  $res = $db->pquery($lporgquery,array($accountname));
	  if(isset($res)){
			$rowCount = $db->num_rows($res);
			if($rowCount > 0){
				$accountid = $db->query_result($res,0,'accountid');
			}
		}
		  $grpquery="select id from vtiger_users where CONCAT( first_name,' ', last_name)=?";
	  $gres = $db->pquery($grpquery,array($row['AssignedTo']));
	  if(isset($gres)){
			$rowCount = $db->num_rows($gres);
			if($rowCount > 0){
				$Assignedto = $db->query_result($gres,0,'id');
			}
			else{
			$grrpquery="select groupid from  vtiger_groups where groupname=?";
		  $grres = $db->pquery($grrpquery,array($row['AssignedTo']));
		  if(isset($grres)){
			$rowgCount = $db->num_rows($grres);
			if($rowgCount > 0){
				$Assignedto = $db->query_result($grres,0,'groupid');
			}
			}
		}
	  }
require_once 'data/CRMEntity.php';
		$document = CRMEntity::getInstance('Documents');

		$attachid = $this->saveAttachment($row['id']);
				 $currentUserModel = Users_Record_Model::getCurrentUserModel();
			
		$document = CRMEntity::getInstance('Documents');
			$document = new Documents();
			 
		echo "docsid".$notesid."-- Atachid".$attachid."<br>";
		//echo "t:".$row['Title'].",n:".$row['filename'].",s:".$row['status'].",t:".$row['filetype'].",sz:".$row['size'].",f:".$row['folder'].",c:".$row['count'].",v:".$row['Version'].",n:".$row['Note'].",p:".$row['Pictures'];
			$document->id      =    $notesid;
			$document->column_fields['notes_title']      =    $row['Title'];

			 $document->column_fields['filename']		 =  $row['filename'];
			$document->column_fields['filestatus']       =  $row['status'];
			$document->column_fields['filetype']       =  $row['filetype'];
			$document->column_fields['filesize']       =  $row['size'];
			$document->column_fields['folderid']       =  $row['folder'];
			$document->column_fields['filedownloadcount']       =  $row['count'];
			$document->column_fields['fileversion']       =  $row['Version'];
			$document->column_fields['notecontent']       =  $row['Note'];
			$document->column_fields['cf_2150']       =  $row['Pictures'];
			$document->column_fields['cf_2170'] =  $accountid; 
			$document->column_fields['assigned_user_id'] = $Assignedto;
			$document->mode ='edit';
 			$document->save('Documents');
			 // $query = "UPDATE vtiger_notes vn ,vtiger_notescf vcf SET filename = ?,filelocationtype = ? ,cf_782=? WHERE vn.notesid=vcf.notesid and vcf.notesid = ?";
			//$db->pquery($query,array(decode_html($filename),'K',decode_html($fileurl),$document->id));
			// Link file attached to document
			echo " edit docsid".$document->id."-- Atachid".$attachid."<br>";
			// Link file attached to document
			$db->pquery("INSERT INTO vtiger_seattachmentsrel(crmid, attachmentsid) VALUES(?,?)",
					Array($document->id, $attachid));
				
	}
	else{
		$lporgquery="select accountid from vtiger_account where accountname=?";
	  $res = $db->pquery($lporgquery,array($accountname));
	  if(isset($res)){
			$rowCount = $db->num_rows($res);
			if($rowCount > 0){
				$accountid = $db->query_result($res,0,'accountid');
			}
		}
		
		  $grpquery="select id from vtiger_users where CONCAT( first_name,' ', last_name)=?";
	  $gres = $db->pquery($grpquery,array($row['AssignedTo']));
	  if(isset($gres)){
			$rowCount = $db->num_rows($gres);
			if($rowCount > 0){
				$Assignedto = $db->query_result($gres,0,'id');
			}
			else{
			$grrpquery="select groupid from  vtiger_groups where groupname=?";
		  $grres = $db->pquery($grrpquery,array($row['AssignedTo']));
		  if(isset($grres)){
			$rowCount = $db->num_rows($grres);
			if($rowCount > 0){
				$Assignedto = $db->query_result($grres,0,'groupid');
			}
			}
		}
	  }
		require_once 'data/CRMEntity.php';
		$document = CRMEntity::getInstance('Documents');

		$attachid = $this->saveAttachment();
				 $currentUserModel = Users_Record_Model::getCurrentUserModel();
			
		$document = CRMEntity::getInstance('Documents');
			$document = new Documents();
			//print_r($document);
			
			//echo "docsid".$document->id."-- Atachid".$attachid."<br>";
			$document->column_fields['notes_title']      =    $row['Title'];
			 $document->column_fields['filename']		 =  $row['filename'];
			$document->column_fields['filestatus']       =  $row['status'];
			$document->column_fields['filetype']       =  $row['filetype'];
			$document->column_fields['filesize']       =  $row['size'];
			$document->column_fields['folderid']       =  $row['folder'];
			$document->column_fields['filedownloadcount']       =  $row['count'];
			$document->column_fields['fileversion']       =  $row['Version'];
			$document->column_fields['notecontent']       =  $row['Note'];
			$document->column_fields['cf_2150']       =  $row['Pictures'];
			$document->column_fields['cf_2170'] =  $accountid; 
			
			$document->column_fields['filelocationtype'] = 'I';
			$document->column_fields['assigned_user_id'] = $Assignedto;
			
 			$document->save('Documents');
			 // $query = "UPDATE vtiger_notes vn ,vtiger_notescf vcf SET filename = ?,filelocationtype = ? ,cf_782=? WHERE vn.notesid=vcf.notesid and vcf.notesid = ?";
			//$db->pquery($query,array(decode_html($filename),'K',decode_html($fileurl),$document->id));
			// Link file attached to document
			echo " save docsid".$document->id."-- Atachid".$attachid."<br>";
			// Link file attached to document
			$db->pquery("INSERT INTO vtiger_seattachmentsrel(crmid, attachmentsid) VALUES(?,?)",
					Array($document->id, $attachid));

	}
}
	   }
	   //select path from vtiger_attachments where attachmentsid in (select attachmentsid from vtiger_seattachmentsrel  where crmid in (select notesid from vtiger_notes))
		
	 	
		
		$response = new Vtiger_Response();
		$response->setResult(array('success' => true ));
		$response->emit();
	 }
	 public function saveAttachment($rowid=0) {
		$db = PearDatabase::getInstance();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();

		$uploadPath = decideFilePath();
	  $attachemntqry = "select attachmentsid,path,name,description,type from vtiger_attachments va where attachmentsid in (select attachmentsid from vtiger_seattachmentsrel  where crmid in (select notesid from vtiger_notes where notesid = ".$rowid."))";
	$attachresult = mysql_query($attachemntqry);
  $attachcount = mysql_num_rows($attachresult);
if($attachcount > 0){
while ($attachrow = mysql_fetch_array($attachresult)) {
    $path= $attachrow['path'];
    $attachementid = $attachrow['attachmentsid'];
    $fileName =$attachrow['name'];
    $description =$attachrow['description'];
    $type =$attachrow['type'];
}
		if(!empty($fileName)) {
			$attachid = $db->getUniqueId('vtiger_crmentity');
				$date_var = $db->formatDate(date('YmdHis'), true);
				$usetime = $db->formatDate($date_var, true);
			//sanitize the filename
			$binFile = sanitizeUploadFileName($fileName, vglobal('upload_badext'));
			$fileName = ltrim(basename(" ".$binFile));
			//echo "path:".$path.",attachid:". $attachementid.",fileName".$fileName;
			copy('old'.$path.$attachementid . "_" . $fileName,$uploadPath. $attachid . "_" . $binFile);
				$db->pquery("INSERT INTO vtiger_crmentity(crmid, smcreatorid, smownerid,
				modifiedby, setype, description, createdtime, modifiedtime, presence, deleted)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
						Array($attachid, $currentUserModel->getId(), $currentUserModel->getId(), $currentUserModel->getId(), "Documents Attachment", $description, $usetime, $usetime, 1, 0));

				//$mimetype = MailAttachmentMIME::detect($uploadPath.$attachid."_".$fileName);
				$db->pquery("INSERT INTO vtiger_attachments SET attachmentsid=?, name=?, description=?,   path=?,type=?",
						Array($attachid, $fileName, $description,  $uploadPath,$type));

				return $attachid;
			}
		}
		return false;
	}
	 }


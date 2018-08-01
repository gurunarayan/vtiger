<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

// Note is used to store customer information.
class Documents extends CRMEntity {

	var $log;
	var $db;
	var $table_name = "vtiger_notes";
	var $table_index= 'notesid';
	var $default_note_name_dom = array('Meeting vtiger_notes', 'Reminder');

	var $tab_name = Array('vtiger_crmentity','vtiger_notes','vtiger_notescf');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_notes'=>'notesid','vtiger_senotesrel'=>'notesid','vtiger_notescf'=>'notesid');

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_notescf', 'notesid');

	var $column_fields = Array();

    var $sortby_fields = Array('title','modifiedtime','filename','createdtime','lastname','filedownloadcount','smownerid');

	// This is used to retrieve related vtiger_fields from form posts.
	var $additional_column_fields = Array('', '', '', '');

	// This is the list of vtiger_fields that are in the lists.
	var $list_fields = Array(
				'Title'=>Array('notes'=>'title'),
				'File Name'=>Array('notes'=>'filename'),
				'Modified Time'=>Array('crmentity'=>'modifiedtime'),
				'Assigned To' => Array('crmentity'=>'smownerid'),
				'Folder Name' => Array('attachmentsfolder'=>'folderid')
				);
	var $list_fields_name = Array(
					'Title'=>'notes_title',
					'File Name'=>'filename',
					'Modified Time'=>'modifiedtime',
					'Assigned To'=>'assigned_user_id',
					'Folder Name' => 'folderid'
				     );

	var $search_fields = Array(
					'Title' => Array('notes'=>'notes_title'),
					'File Name' => Array('notes'=>'filename'),
					'Assigned To' => Array('crmentity'=>'smownerid'),
					'Folder Name' => Array('attachmentsfolder'=>'foldername')
		);

	var $search_fields_name = Array(
					'Title' => 'notes_title',
					'File Name' => 'filename',
					'Assigned To' => 'assigned_user_id',
					'Folder Name' => 'folderid'
	);
	var $list_link_field= 'notes_title';
	var $old_filename = '';
	//var $groupTable = Array('vtiger_notegrouprelation','notesid');

	var $mandatory_fields = Array('notes_title','createdtime' ,'modifiedtime','filename','filesize','filetype','filedownloadcount','assigned_user_id');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'title';
	var $default_sort_order = 'ASC';
	function Documents() {
		$this->log = LoggerManager::getLogger('notes');
		$this->log->debug("Entering Documents() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Documents');
		$this->log->debug("Exiting Documents method ...");
	}

	function save_module($module)
	{
		global $log,$adb,$upload_badext;
		$insertion_mode = $this->mode;
		if(isset($this->parentid) && $this->parentid != '')
			$relid =  $this->parentid;
		//inserting into vtiger_senotesrel
		if(isset($relid) && $relid != '')
		{
			$this->insertintonotesrel($relid,$this->id);
		}
		$filetype_fieldname = $this->getFileTypeFieldName();
		$filename_fieldname = $this->getFile_FieldName();
		if($this->column_fields[$filetype_fieldname] == 'I' ){
			if($_FILES[$filename_fieldname]['name'] != ''){
				$errCode=$_FILES[$filename_fieldname]['error'];
					if($errCode == 0){
						foreach($_FILES as $fileindex => $files)
						{
							if($files['name'] != '' && $files['size'] > 0){
								$filename = $_FILES[$filename_fieldname]['name'];
								$filename = from_html(preg_replace('/\s+/', '_', $filename));
								$filetype = $_FILES[$filename_fieldname]['type'];
								$filesize = $_FILES[$filename_fieldname]['size'];
								$filelocationtype = 'I';
								$binFile = sanitizeUploadFileName($filename, $upload_badext);
								$filename = ltrim(basename(" ".$binFile)); //allowed filename like UTF-8 characters
							}
						}

					}
			}elseif($this->mode == 'edit') {
				$fileres = $adb->pquery("select filetype, filesize,filename,filedownloadcount,filelocationtype from vtiger_notes where notesid=?", array($this->id));
				if ($adb->num_rows($fileres) > 0) {
					$filename = $adb->query_result($fileres, 0, 'filename');
					$filetype = $adb->query_result($fileres, 0, 'filetype');
					$filesize = $adb->query_result($fileres, 0, 'filesize');
					$filedownloadcount = $adb->query_result($fileres, 0, 'filedownloadcount');
					$filelocationtype = $adb->query_result($fileres, 0, 'filelocationtype');
				}
			}elseif($this->column_fields[$filename_fieldname]) {
				$filename = $this->column_fields[$filename_fieldname];
				$filesize = $this->column_fields['filesize'];
				$filetype = $this->column_fields['filetype'];
				$filelocationtype = $this->column_fields[$filetype_fieldname];
				$filedownloadcount = 0;
			} else {
				$filelocationtype = 'I';
				$filetype = '';
				$filesize = 0;
				$filedownloadcount = null;
			}
		} else if($this->column_fields[$filetype_fieldname] == 'E' ){
			$filelocationtype = 'E';
			$filename = $this->column_fields[$filename_fieldname];
			// If filename does not has the protocol prefix, default it to http://
			// Protocol prefix could be like (https://, smb://, file://, \\, smb:\\,...)
			if(!empty($filename) && !preg_match('/^\w{1,5}:\/\/|^\w{0,3}:?\\\\\\\\/', trim($filename), $match)) {
				$filename = "http://$filename";
			}
			$filetype = '';
			$filesize = 0;
			$filedownloadcount = null;
		}
		//added by SL for DD files upload :4th August'15
		  if($filelocationtype != 'C') {
		$query = "UPDATE vtiger_notes SET filename = ? ,filesize = ?, filetype = ? , filelocationtype = ? , filedownloadcount = ? WHERE notesid = ?";
 		$re=$adb->pquery($query,array(decode_html($filename),$filesize,$filetype,$filelocationtype,$filedownloadcount,$this->id));
		 }
		//Inserting into attachments table
		//added by SL for DD files upload :4th August'15:start
		if($filelocationtype == 'C'){
		$this->insertIntoDDAttachment($this->id,'Documents');
		}
		else { //added by SL for DD files upload :4th August'15:end
			if($filelocationtype == 'I') {
			$this->insertIntoAttachment($this->id,'Documents');
		}
		else{
			$query = "delete from vtiger_seattachmentsrel where crmid = ?";
			$qparams = array($this->id);
			$adb->pquery($query, $qparams);
		}
        //set the column_fields so that its available in the event handlers
        $this->column_fields['filename'] = $filename;
        $this->column_fields['filesize'] = $filesize;
        $this->column_fields['filetype'] = $filetype;
        $this->column_fields['filedownloadcount'] = $filedownloadcount;
		}
	}

	/**
	 *      This function is used to add the vtiger_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
	 *      @param int $id  - entity id to which the vtiger_files to be uploaded
	 *      @param string $module  - the current module name
	*/
	//Function added by SL for DD files upload :4th August'15:start
	function insertIntoDDAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;
 
		for($i=0; $i<count($_FILES['file']['name']); $i++){
			$result= '';
			
			$filename=$_FILES['file']['name'][$i];
			$filesize=$_FILES['file']['size'][$i];
			$filetmp=$_FILES['file']['tmp_name'][$i];
			$filetype=$_FILES['file']['type'][$i];
			$filelocationtype='C';
			$filedownloadcount=0;
			
		
		$file_saved = $this->uploadAndSaveDDFile($id,$module,$files,$i);
		}
		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}

	//Function added by SL for DD files upload :4th August'15:end
	 //added by SL for DD files upload :4th August'15:start
	function uploadAndSaveDDFile($id, $module, $file_details,$i) {
		global $log;
		$log->debug("Entering into uploadAndSaveFile($id,$module,$file_details) method.");
		global $adb, $current_user;
		global $upload_badext;

		$date_var = date("Y-m-d H:i:s");

		//to get the owner id
		$ownerid = $this->column_fields['assigned_user_id'];
		if (!isset($ownerid) || $ownerid == '')
			$ownerid = $current_user->id;

		if (isset($file_details['original_name'][$i]) && $file_details['original_name'][$i] != null) {
			$file_name = $file_details['original_name'][$i];
		} else {
			$file_name = $file_details['name'][$i];
		}

		$binFile = sanitizeUploadFileName($file_name, $upload_badext);

		$current_id = $adb->getUniqueID("vtiger_crmentity");

		$filename = ltrim(basename(" " . $binFile)); //allowed filename like UTF-8 characters
		$filetype = $file_details['type'][$i];
		$filesize = $file_details['size'][$i];
		$filetmp_name = $file_details['tmp_name'][$i];

		//get the file path inwhich folder we want to upload the file
		$upload_file_path = decideFilePath();

		//upload the file in server
		$upload_status = move_uploaded_file($filetmp_name, $upload_file_path . $current_id . "_" . $binFile);

		$save_file = 'true';
		//only images are allowed for these modules
		if ($module == 'Contacts' || $module == 'Products') {
			$save_file = validateImageFile($file_details);
		}

		if ($save_file == 'true' && $upload_status == 'true') {
			//This is only to update the attached filename in the vtiger_notes vtiger_table for the Notes module
			if ($module == 'Contacts' || $module == 'Products') {
				$sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?, ?, ?, ?, ?, ?, ?)";
				$params1 = array($current_id, $current_user->id, $ownerid, $module . " Image", $this->column_fields['description'], $adb->formatDate($date_var, true), $adb->formatDate($date_var, true));
			} else {
				$sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?, ?, ?, ?, ?, ?, ?)";
				$params1 = array($current_id, $current_user->id, $ownerid, $module . " Attachment", $this->column_fields['description'], $adb->formatDate($date_var, true), $adb->formatDate($date_var, true));
			}
			$adb->pquery($sql1, $params1);

			$sql2 = "insert into vtiger_attachments(attachmentsid, name, description, type, path) values(?, ?, ?, ?, ?)";
			$params2 = array($current_id, $filename, $this->column_fields['description'], $filetype, $upload_file_path);
			$result = $adb->pquery($sql2, $params2);

			if ($_REQUEST['mode'] == 'edit') {
				if ($id != '' && vtlib_purify($_REQUEST['fileid']) != '') {
					$delquery = 'delete from vtiger_seattachmentsrel where crmid = ? and attachmentsid = ?';
					$delparams = array($id, vtlib_purify($_REQUEST['fileid']));
					$adb->pquery($delquery, $delparams);
				}
			}
			if ($module == 'Documents') {
				$query = "delete from vtiger_seattachmentsrel where crmid = ?";
				$qparams = array($id);
				$adb->pquery($query, $qparams);
			}
			if ($module == 'Contacts') {
				$att_sql = "select vtiger_seattachmentsrel.attachmentsid  from vtiger_seattachmentsrel inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_seattachmentsrel.attachmentsid where vtiger_crmentity.setype='Contacts Image' and vtiger_seattachmentsrel.crmid=?";
				$res = $adb->pquery($att_sql, array($id));
				$attachmentsid = $adb->query_result($res, 0, 'attachmentsid');
				if ($attachmentsid != '') {
					$delquery = 'delete from vtiger_seattachmentsrel where crmid=? and attachmentsid=?';
					$adb->pquery($delquery, array($id, $attachmentsid));
					$crm_delquery = "delete from vtiger_crmentity where crmid=?";
					$adb->pquery($crm_delquery, array($attachmentsid));
					$sql5 = 'insert into vtiger_seattachmentsrel values(?,?)';
					$adb->pquery($sql5, array($id, $current_id));
				} else {
					$sql3 = 'insert into vtiger_seattachmentsrel values(?,?)';
					$adb->pquery($sql3, array($id, $current_id));
				}
			} else {
				$sql3 = 'insert into vtiger_seattachmentsrel values(?,?)';
				$adb->pquery($sql3, array($id, $current_id));
			}

			return true;
		} else {
			$log->debug("Skip the save attachment process.");
			return false;
		}
	}
	//added by SL for DD files upload :4th August'15:start
	/**
	 *      This function is used to add the vtiger_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
	 *      @param int $id  - entity id to which the vtiger_files to be uploaded
	 *      @param string $module  - the current module name
	*/
	function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;
		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				$files['original_name'] = vtlib_purify($_REQUEST[$fileindex.'_hidden']);
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}

	/**    Function used to get the sort order for Documents listview
	*      @return string  $sorder - first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['NOTES_SORT_ORDER'] if this session value is empty then default sort order will be returned.
	*/
	function getSortOrder()
	{
		global $log;
		$log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder']))
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['NOTES_SORT_ORDER'] != '')?($_SESSION['NOTES_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**     Function used to get the order by value for Documents listview
	*       @return string  $order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['NOTES_ORDER_BY'] if this session value is empty then default order by will be returned.
	*/
	function getOrderBy()
	{
		global $log;
		$log->debug("Entering getOrderBy() method ...");

		$use_default_order_by = '';
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}

		if (isset($_REQUEST['order_by']))
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		else
			$order_by = (($_SESSION['NOTES_ORDER_BY'] != '')?($_SESSION['NOTES_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

	/**
	 * Function used to get the sort order for Documents listview
	 * @return String $sorder - sort order for a given folder.
	 */
	function getSortOrderForFolder($folderId) {
		if(isset($_REQUEST['sorder']) && $_REQUEST['folderid'] == $folderId) {
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		} elseif(is_array($_SESSION['NOTES_FOLDER_SORT_ORDER']) &&
					!empty($_SESSION['NOTES_FOLDER_SORT_ORDER'][$folderId])) {
				$sorder = $_SESSION['NOTES_FOLDER_SORT_ORDER'][$folderId];
		} else {
			$sorder = $this->default_sort_order;
		}
		return $sorder;
	}

	/**
	 * Function used to get the order by value for Documents listview
	 * @return String order by column for a given folder.
	 */
	function getOrderByForFolder($folderId) {
		$use_default_order_by = '';
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}
		if (isset($_REQUEST['order_by'])  && $_REQUEST['folderid'] == $folderId) {
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		} elseif(is_array($_SESSION['NOTES_FOLDER_ORDER_BY']) &&
				!empty($_SESSION['NOTES_FOLDER_ORDER_BY'][$folderId])) {
			$order_by = $_SESSION['NOTES_FOLDER_ORDER_BY'][$folderId];
		} else {
			$order_by = ($use_default_order_by);
		}
		return $order_by;
	}

	/** Function to export the notes in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Documents Query.
	*/
	function create_export_query($where)
	{
		global $log,$current_user;
		$log->debug("Entering create_export_query(". $where.") method ...");

		include("include/utils/ExportUtils.php");
		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Documents", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'vtiger_users.first_name', 'last_name' => 'vtiger_users.last_name'), 'Users');
		$query = "SELECT $fields_list, case when (vtiger_users.user_name not like '') then $userNameSql else vtiger_groups.groupname end as user_name" .
				" FROM vtiger_notes
				inner join vtiger_crmentity
					on vtiger_crmentity.crmid=vtiger_notes.notesid
				LEFT JOIN vtiger_attachmentsfolder on vtiger_notes.folderid=vtiger_attachmentsfolder.folderid
				LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid=vtiger_users.id " .
				" LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid=vtiger_groups.groupid "
				;
		$query .= getNonAdminAccessControlQuery('Documents',$current_user);
		$where_auto=" vtiger_crmentity.deleted=0";
		if($where != "")
			$query .= "  WHERE ($where) AND ".$where_auto;
		else
			$query .= "  WHERE ".$where_auto;

		$log->debug("Exiting create_export_query method ...");
		        return $query;
	}

	function del_create_def_folder($query)
	{
		global $adb;
		$dbQuery = $query." and vtiger_attachmentsfolderfolderid.folderid = 0";
		$dbresult = $adb->pquery($dbQuery,array());
		$noofnotes = $adb->num_rows($dbresult);
		if($noofnotes > 0)
		{
            $folderQuery = "select folderid from vtiger_attachmentsfolder";
            $folderresult = $adb->pquery($folderQuery,array());
            $noofdeffolders = $adb->num_rows($folderresult);

            if($noofdeffolders == 0)
            {
			    $insertQuery = "insert into vtiger_attachmentsfolder values (0,'Default','Contains all attachments for which a folder is not set',1,0)";
			    $insertresult = $adb->pquery($insertQuery,array());
            }
		}

	}

	function insertintonotesrel($relid,$id)
	{
		global $adb;
		$dbQuery = "insert into vtiger_senotesrel values ( ?, ? )";
		$dbresult = $adb->pquery($dbQuery,array($relid,$id));
	}

	/*function save_related_module($module, $crmid, $with_module, $with_crmid){
	}*/


	/*
	 * Function to get the primary query part of a report
	 * @param - $module Primary module name
	 * returns the query string formed on fetching the related data for report for primary module
	 */
	function generateReportsQuery($module,$queryplanner){
		$moduletable = $this->table_name;
		$moduleindex = $this->tab_name_index[$moduletable];
		$query = "from $moduletable
			inner join vtiger_crmentity on vtiger_crmentity.crmid=$moduletable.$moduleindex";
		if ($queryplanner->requireTable("vtiger_attachmentsfolder")){
		    $query .= " inner join vtiger_attachmentsfolder on vtiger_attachmentsfolder.folderid=$moduletable.folderid";
		}
		if ($queryplanner->requireTable("vtiger_groups".$module)){
		    $query .= " left join vtiger_groups as vtiger_groups".$module." on vtiger_groups".$module.".groupid = vtiger_crmentity.smownerid";
		}
		if ($queryplanner->requireTable("vtiger_users".$module)){
		    $query .= " left join vtiger_users as vtiger_users".$module." on vtiger_users".$module.".id = vtiger_crmentity.smownerid";
		}
		$query .= " left join vtiger_groups on vtiger_groups.groupid = vtiger_crmentity.smownerid";
        $query .= " left join vtiger_notescf on vtiger_notes.notesid = vtiger_notescf.notesid";
		$query .= " left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid";
		if ($queryplanner->requireTable("vtiger_lastModifiedBy".$module)){
		    $query .= " left join vtiger_users as vtiger_lastModifiedBy".$module." on vtiger_lastModifiedBy".$module.".id = vtiger_crmentity.modifiedby ";
		}
		return $query;

	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryplanner) {

		$matrix = $queryplanner->newDependencyMatrix();

		$matrix->setDependency("vtiger_crmentityDocuments",array("vtiger_groupsDocuments","vtiger_usersDocuments","vtiger_lastModifiedByDocuments"));
		$matrix->setDependency("vtiger_notes",array("vtiger_crmentityDocuments","vtiger_attachmentsfolder"));

		if (!$queryplanner->requireTable('vtiger_notes', $matrix)) {
			return '';
		}
		// TODO Support query planner
		$query = $this->getRelationQuery($module,$secmodule,"vtiger_notes","notesid", $queryplanner);
        $query .= " left join vtiger_notescf on vtiger_notes.notesid = vtiger_notescf.notesid";
		if ($queryplanner->requireTable("vtiger_crmentityDocuments",$matrix)){
		    $query .=" left join vtiger_crmentity as vtiger_crmentityDocuments on vtiger_crmentityDocuments.crmid=vtiger_notes.notesid and vtiger_crmentityDocuments.deleted=0";
		}
		if ($queryplanner->requireTable("vtiger_attachmentsfolder")){
		    $query .=" left join vtiger_attachmentsfolder on vtiger_attachmentsfolder.folderid=vtiger_notes.folderid";
		}
		if ($queryplanner->requireTable("vtiger_groupsDocuments")){
		    $query .=" left join vtiger_groups as vtiger_groupsDocuments on vtiger_groupsDocuments.groupid = vtiger_crmentityDocuments.smownerid";
		}
		if ($queryplanner->requireTable("vtiger_usersDocuments")){
		    $query .=" left join vtiger_users as vtiger_usersDocuments on vtiger_usersDocuments.id = vtiger_crmentityDocuments.smownerid";
		}
		if ($queryplanner->requireTable("vtiger_lastModifiedByDocuments")){
		    $query .=" left join vtiger_users as vtiger_lastModifiedByDocuments on vtiger_lastModifiedByDocuments.id = vtiger_crmentityDocuments.modifiedby ";
		}
        if ($queryplanner->requireTable("vtiger_createdbyDocuments")){
			$query .= " left join vtiger_users as vtiger_createdbyDocuments on vtiger_createdbyDocuments.id = vtiger_crmentityDocuments.smcreatorid ";
		}
		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array();
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		/*//Backup Documents Related Records
		$se_q = 'SELECT crmid FROM vtiger_senotesrel WHERE notesid = ?';
		$se_res = $this->db->pquery($se_q, array($id));
		if ($this->db->num_rows($se_res) > 0) {
			for($k=0;$k < $this->db->num_rows($se_res);$k++)
			{
				$se_id = $this->db->query_result($se_res,$k,"crmid");
				$params = array($id, RB_RECORD_DELETED, 'vtiger_senotesrel', 'notesid', 'crmid', $se_id);
				$this->db->pquery('INSERT INTO vtiger_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
			}
		}
		$sql = 'DELETE FROM vtiger_senotesrel WHERE notesid = ?';
		$this->db->pquery($sql, array($id));*/

		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts') {
			$sql = 'DELETE FROM vtiger_senotesrel WHERE notesid = ? AND (crmid = ? OR crmid IN (SELECT contactid FROM vtiger_contactdetails WHERE accountid=?))';
			$this->db->pquery($sql, array($id, $return_id, $return_id));
		} else {
			$sql = 'DELETE FROM vtiger_senotesrel WHERE notesid = ? AND crmid = ?';
			$this->db->pquery($sql, array($id, $return_id));

			$sql = 'DELETE FROM vtiger_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}


// Function to get fieldname for uitype 27 assuming that documents have only one file type field

	function getFileTypeFieldName(){
		global $adb,$log;
		$query = 'SELECT fieldname from vtiger_field where tabid = ? and uitype = ?';
		$tabid = getTabid('Documents');
		$filetype_uitype = 27;
		$res = $adb->pquery($query,array($tabid,$filetype_uitype));
		$fieldname = null;
		if(isset($res)){
			$rowCount = $adb->num_rows($res);
			if($rowCount > 0){
				$fieldname = $adb->query_result($res,0,'fieldname');
			}
		}
		return $fieldname;

	}

//	Function to get fieldname for uitype 28 assuming that doc has only one file upload type

	function getFile_FieldName(){
		global $adb,$log;
		$query = 'SELECT fieldname from vtiger_field where tabid = ? and uitype = ?';
		$tabid = getTabid('Documents');
		$filename_uitype = 28;
		$res = $adb->pquery($query,array($tabid,$filename_uitype));
		$fieldname = null;
		if(isset($res)){
			$rowCount = $adb->num_rows($res);
			if($rowCount > 0){
				$fieldname = $adb->query_result($res,0,'fieldname');
			}
		}
		return $fieldname;
	}

	/**
	 * Check the existence of folder by folderid
	 */
	function isFolderPresent($folderid) {
		global $adb;
		$result = $adb->pquery("SELECT folderid FROM vtiger_attachmentsfolder WHERE folderid = ?", array($folderid));
		if(!empty($result) && $adb->num_rows($result) > 0) return true;
		return false;
	}

	/**
	 * Customizing the restore procedure.
	 */
	function restore($modulename, $id) {
		parent::restore($modulename, $id);

		global $adb;
		$fresult = $adb->pquery("SELECT folderid FROM vtiger_notes WHERE notesid = ?", array($id));
		if(!empty($fresult) && $adb->num_rows($fresult)) {
			$folderid = $adb->query_result($fresult, 0, 'folderid');
			if(!$this->isFolderPresent($folderid)) {
				// Re-link to default folder
				$adb->pquery("UPDATE vtiger_notes set folderid = 1 WHERE notesid = ?", array($id));
			}
		}
	}

	function getQueryByModuleField($module, $fieldname, $srcrecord, $query) {
		if($module == "MailManager") {
			$tempQuery = split('WHERE', $query);
			if(!empty($tempQuery[1])) {
				$where = " vtiger_notes.filelocationtype = 'I' AND vtiger_notes.filename != '' AND vtiger_notes.filestatus != 0 AND ";
				$query = $tempQuery[0].' WHERE '.$where.$tempQuery[1];
			} else{
				$query = $tempQuery[0].' WHERE '.$tempQuery;
			}
			return $query;
		}
	}

	/**
	 * Function to check the module active and user action permissions before showing as link in other modules
	 * like in more actions of detail view.
	 */
	static function isLinkPermitted($linkData) {
		$moduleName = "Documents";
		if(vtlib_isModuleActive($moduleName) && isPermitted($moduleName, 'EditView') == 'yes') {
			return true;
		}
		return false;
	}
	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $root_directory;
		require_once('include/utils/utils.php');
		require_once('include/utils/VtlibUtils.php');
		require_once('vtlib/Vtiger/Module.php');
		require_once('vtlib/Vtiger/Link.php');
		global $adb;
		 if($eventType == 'module.postupdate') {
			 
			//$this->addModuleToCustomerPortal();
			
			$adb->pquery("CREATE TABLE IF NOT EXISTS vtiger_documentmodulesrel ( tabid int(19) NOT NULL,modulename varchar(25) NOT NULL,modulelabel varchar(25) NOT NULL,
			  defaultfolder int(19),multiplerecords BOOLEAN,enabled BOOLEAN ) ENGINE=InnoDB DEFAULT CHARSET=utf8",array());			
			
			$result = $adb->pquery("select 1 from vtiger_links where tabid=8 and linktype='LISTVIEWSIDEBARWIDGET' and  linklabel='Folders' and linkurl='module=Documents&view=ListFolders'",array());
			
			if($adb->num_rows($result) < 1) {
				
				/*$adb->pquery("INSERT vtiger_links SET linkid=(SELECT id+1 FROM vtiger_links_seq), tabid=8, linktype='LISTVIEWSIDEBARWIDGET', linklabel='Folders', linkurl='module=Documents&view=ListFolders'",array());*/ 
				
				#To Add Link to calculate Respective Fields
				$adb->pquery("UPDATE vtiger_links_seq SET id=(SELECT MAX(linkid) FROM vtiger_links)",array());
				$url = "module=Documents&view=ListFolders";
				$moduleInstance = Vtiger_Module::getInstance('Documents');
				$moduleInstance->addLink('LISTVIEWSIDEBARWIDGET', 'Documents', $url);
				
				$adb->pquery("INSERT vtiger_documentmodulesrel (
				tabid, modulename, modulelabel, defaultfolder, multiplerecords, enabled
				) SELECT tabid, name, tablabel, 1, false, false FROM vtiger_tab WHERE isentitytype=1 AND tabid NOT IN (8,9,10,16,28,35,39,40)",array());
				
				$adb->query("ALTER TABLE vtiger_notes ADD COLUMN filepath text  AFTER notesid,ADD COLUMN multiple INT(1)  NULL AFTER filepath,ADD COLUMN related_to INT(19)  NULL AFTER multiple,ADD COLUMN fileurl text AFTER related_to"); 
				$adb->pquery("ALTER TABLE vtiger_attachmentsfolder ADD COLUMN parentid INT(11) )",array());
				
				$adb->pquery("UPDATE vtiger_attachmentsfolder SET parentid=0]",array());
				
				$adb->pquery("INSERT vtiger_cvcolumnlist SET cvid=22, columnindex=0, columnname='vtiger_notes:folderid:folderid:Notes_Folderid:I'",array());
				
				$adb->pquery("INSERT vtiger_cvcolumnlist SET cvid=22, columnindex=-1, columnname='vtiger_notes:filepath:filepath:Notes_Path:N'",array());
				
				
			}
				
			$result = $adb->pquery("SELECT 1 FROM vtiger_modentity_num WHERE semodule = ? AND active =1 ", array($moduleName));
			if ($adb->num_rows($result) < 1) {
				//Initialize module sequence for the module
				$adb->pquery("INSERT INTO vtiger_modentity_num values(?,?,?,?,?,?)", array($adb->getUniqueId("vtiger_modentity_num"), $moduleName, 'ASSET', 1, 1, 1));
			}
			
			//@mkdir( $root_directory . 'libraries/' ,0777,true);			
			$this->copy_r( 'modules/Documents/libraries/', 'libraries/' );
		}		
 	}
	
	function copy_r( $path, $dest ) {
        if( is_dir($path) ) {
            //@mkdir( $dest );
            @mkdir( $dest ,0777,true);
            $objects = scandir($path);
            if( sizeof($objects) > 0 ) {
                foreach( $objects as $file ) {
                    if( $file == "." || $file == ".." )
                        continue;
                    // go on
                    if( is_dir( $path.DIRECTORY_SEPARATOR.$file ) ) {
                        $this->copy_r( $path.DIRECTORY_SEPARATOR.$file, $dest.DIRECTORY_SEPARATOR.$file );
                    }
                    else {
						// TODO Must save existing script copies for rollback
						//$this->vtFileCopy($dest.DIRECTORY_SEPARATOR.$file);
                        $crslt = copy( $path.DIRECTORY_SEPARATOR.$file, $dest.DIRECTORY_SEPARATOR.$file );
						/*if ($crslt) {
							//echo "Copied ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file."<br>";
							$this->copiedFiles[] = "Copied ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file;
						}
						else {
							$this->failedCopies[] = "Could not copy ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file;
						}*/
                    }
                }
            }
           // return true;
        }
        elseif( is_file($path) ) {
			$crslt = copy($path, $dest);
			if ($crslt)
			{
				//echo "Copied ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file."<br>";
				//$this->copiedFiles[] = "Copied ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file;
			}
			else {
				//echo "<font color=red>Could not copy ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file."</font><br>";
				//$this->failedCopies[] = "Could not copy ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file;
			}
           // return $crslt;
        }
        else {
            //return false;
        }
    } 
}
?>
<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class PointSale_Record_Model extends Vtiger_Record_Model {

	/**
	 * Function to get Image Details
	 * @return <array> Image Details List
	 */
	/*public function getImageDetails() {
		$db = PearDatabase::getInstance();
		$imageDetails = array();
		$recordId = $this->getId();

		if ($recordId) {
			$sql = "SELECT vtiger_attachments.*, vtiger_crmentity.setype FROM vtiger_attachments
						INNER JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
						INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_attachments.attachmentsid
						WHERE vtiger_crmentity.setype = 'PointSale Attachment' AND vtiger_seattachmentsrel.crmid = ? and subject=?";

			$result = $db->pquery($sql, array($recordId));
			$count = $db->num_rows($result);

			for($i=0; $i<$count; $i++) {
				$imageIdsList[] = $db->query_result($result, $i, 'attachmentsid');
				$imagePathList[] = $db->query_result($result, $i, 'path');
				$imageName = $db->query_result($result, $i, 'name');

				//decode_html - added to handle UTF-8 characters in file names
				$imageOriginalNamesList[] = decode_html($imageName);

				//urlencode - added to handle special characters like #, %, etc.,
				$imageNamesList[] = $imageName;
			}

			if(is_array($imageOriginalNamesList)) {
				$countOfImages = count($imageOriginalNamesList);
				for($j=0; $j<$countOfImages; $j++) {
					$imageDetails[] = array(
							'id' => $imageIdsList[$j],
							'orgname' => $imageOriginalNamesList[$j],
							'path' => $imagePathList[$j].$imageIdsList[$j],
							'name' => $imageNamesList[$j]
					);
				}
			}
		}
		return $imageDetails;
	}

	/**
	 * Function to get Gallery Details
	 * @return <array> Gallery Details List
	 */
	public function getGalleryDetails($fieldname) {
		$db = PearDatabase::getInstance();
		$galleryDetails = array();
		$recordId = $this->getId();

		if ($recordId) {
			$sql = "SELECT vtiger_attachments.*, vtiger_crmentity.setype FROM vtiger_attachments
						INNER JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
						INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_attachments.attachmentsid
						WHERE vtiger_crmentity.setype = 'PointSale Attachment' AND vtiger_seattachmentsrel.crmid = ?  and subject=? ORDER BY subject";

			$result = $db->pquery($sql, array($recordId,$fieldname));
			$count = $db->num_rows($result);

			for($i=0; $i<$count; $i++) {
				$galleryIdsList[] = $db->query_result($result, $i, 'attachmentsid');
				$galleryPathList[] = $db->query_result($result, $i, 'path');
				$galleryName = $db->query_result($result, $i, 'name');
				$galleryField = $db->query_result($result, $i, 'subject');

				//decode_html - added to handle UTF-8 characters in file names
				$galleryOriginalNamesList[] = decode_html($galleryName);

				//urlencode - added to handle special characters like #, %, etc.,
				$galleryNamesList[] = $galleryName;
				$galleryFieldsList[] = $galleryField;
			}

			if(is_array($galleryOriginalNamesList)) {
				$countOfGallerys = count($galleryOriginalNamesList);
				for($j=0; $j<$countOfGallerys; $j++) {
					$galleryDetails[] = array(
							'fieldid' => $galleryFieldsList[$j],
							'id' => $galleryIdsList[$j],
							'orgname' => $galleryOriginalNamesList[$j],
							'path' => $galleryPathList[$j].$galleryIdsList[$j],
							'name' => $galleryNamesList[$j]
					);
				}
			}
		}
		//die(print_r($galleryDetails));
		return $galleryDetails;
	}
public function getGalleryEditDetails() {
		$db = PearDatabase::getInstance();
		//$db->setDebug(true);
		$galleryDetails = array();
		$recordId = $this->getId();
		if ($recordId) {
			$sql = "SELECT vtiger_attachments.*, vtiger_crmentity.setype FROM vtiger_attachments
						INNER JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
						INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_attachments.attachmentsid
						WHERE vtiger_crmentity.setype = 'PointSale Attachment' AND vtiger_seattachmentsrel.crmid = ? ORDER BY subject";

			$result = $db->pquery($sql, array($recordId));
			$count = $db->num_rows($result);

			for($i=0; $i<$count; $i++) {
				$galleryIdsList[] = $db->query_result($result, $i, 'attachmentsid');
				$galleryPathList[] = $db->query_result($result, $i, 'path');
				$galleryName = $db->query_result($result, $i, 'name');
				$galleryField = $db->query_result($result, $i, 'subject');

				//decode_html - added to handle UTF-8 characters in file names
				$galleryOriginalNamesList[] = decode_html($galleryName);

				//urlencode - added to handle special characters like #, %, etc.,
				$galleryNamesList[] = $galleryName;
				$galleryFieldsList[] = $galleryField;
			}

			if(is_array($galleryOriginalNamesList)) {
				$countOfGallerys = count($galleryOriginalNamesList);
				for($j=0; $j<$countOfGallerys; $j++) {
					$galleryDetails[] = array(
							'fieldid' => $galleryFieldsList[$j],
							'id' => $galleryIdsList[$j],
							'orgname' => $galleryOriginalNamesList[$j],
							'path' => $galleryPathList[$j].$galleryIdsList[$j],
							'name' => $galleryNamesList[$j],
							'subject' => $galleryFieldsList[$j]
					);
				}
			}
		}
		return $galleryDetails;
	}

	function get($key) {
		$value = parent::get($key);
		$fieldModel = Vtiger_Field_Model::getInstance($key, Vtiger_Module_Model::getInstance($this->getModuleName()));
		$ui='';
		if(!empty($fieldModel))
		$ui=$fieldModel->get('uitype');
		if ($ui==19 || $ui == 540) return decode_html($value);
		return $value;
	}
}

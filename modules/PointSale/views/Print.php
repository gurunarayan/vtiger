<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
//require_once('libraries/mpdf60/mpdf.php');
require_once('libraries/tcpdf/config/lang/eng.php');
require_once('libraries/tcpdf/tcpdf.php');
class PointSale_Print_View extends Vtiger_View_Controller {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('GetPrintReport');
	}

	function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	function preProcess(Vtiger_Request $request) {
		return false;
	}

	function postProcess(Vtiger_Request $request) {
		return false;
	}

	function process(Vtiger_request $request) {
		$mode = $request->getMode();
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
		}
	}

	function GetPrintReport(Vtiger_Request $request) {
		global $adb;
		$pdf = new TCPDF();

		$pdf->AddPage();
		
		$recordid = $request->get('record');
		$moduleName = $request->getModule();
		
		$query1 = $adb->pquery("SELECT tax from vtiger_pos_settings",array());
		$tax_settings = $adb->query_result($query1, 0, 'tax');
		
		
		$query2 = $adb->pquery("SELECT total_amt, paid_amt, return_amt, related_contact, pointsale_no, createdtime FROM vtiger_pointsalecf 
								LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_pointsalecf.pointsaleid
								LEFT JOIN vtiger_pointsale ON vtiger_pointsale.pointsaleid = vtiger_pointsalecf.pointsaleid
								WHERE vtiger_pointsale.pointsaleid=?", array($recordid));
		$total_amount = $adb->query_result($query2,0,'total_amt');
		$tendered = $adb->query_result($query2,0,'paid_amt');
		$return = $adb->query_result($query2,0,'return_amt');
		$contactid = $adb->query_result($query2,0,'related_contact');
		$createdtime = $adb->query_result($query2,0,'createdtime');
		$Date = date("jS F, Y H:i:s", strtotime($createdtime));
		$pointsale_no = $adb->query_result($query2,0,'pointsale_no');
		
		$query3 = $adb->pquery("SELECT SUM(selected_qty) as items FROM vtiger_posdetails WHERE posid=?",array($recordid));
		$total_item = $adb->query_result($query3,0,'items');
		//cf_771 --total
		//cf_773 --tendered
		//cf_775 -- retun
		$query4 = $adb->pquery("SELECT CONCAT(firstname,' ',lastname) AS name FROM vtiger_contactdetails WHERE contactid=?",array($contactid));
		$contactname = $adb->query_result($query4,0,'name');
		$header = "<div style='width:500px;text-align:center;'><b>POS RECEIPT</b></div>";
		$pdf->SetFont('helvetica', '', 10);
		$pdf->writeHTML($header, true, false, false, false, '');
		$contentss = "<div style='width:500px;'>
						<div style='width:250px;float:left;'>
							<p>Name: $contactname</p>
							<p>Date: $Date</p>
							<p>Receipt: $pointsale_no</p>
						</div>
					</div>";
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML($contentss, true, false, false, false, '');
$pdf->SetFont('helvetica', '', 10);
		if($tax_settings == 1){
			$tbl_header = '<table border="1" cellspacing="1" cellpadding="3" width="100%" >
							<thead>
							<tr>
								<th style="border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #d2d2d3;color: black;" align="center" >S.No</th>
								<th style="border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #d2d2d3;color: black;" align="center">Productname</th>
								<th style="border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #d2d2d3;color: black;" align="center">Qty</th>
								<th style="border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #d2d2d3;color: black;" align="center">Price</th>
								<th style="border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #d2d2d3;color: black;" align="center">Total</th>
								<th style="border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #d2d2d3;color: black;" align="center">Total With Tax</th>
							</tr>
						</thead>
					<tbody>';
		}else{
			
			$tbl_header = '<table border="1" cellspacing="0" cellpadding="3" width="100%">
						<thead>
							<tr>
								<th style="border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #d2d2d3;color: black;" align="center">S.No</th>
								<th style="border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #d2d2d3;color: black;" align="center">Productname</th>
								<th style="border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #d2d2d3;color: black;" align="center">Qty</th>
								<th style="border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #d2d2d3;color: black;" align="center">Price</th>
								<th style="border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #d2d2d3;color: black;" align="center">Total</th>
							</tr>
						</thead>
					<tbody>';
		}
		
		$query1 = $adb->pquery("SELECT productname, vtiger_posdetails.productid, selected_qty, vtiger_posdetails.price FROM vtiger_posdetails 
								LEFT JOIN vtiger_products ON vtiger_products.productid = vtiger_posdetails.productid 
								WHERE posid=?", array($recordid));
		$row1 = $adb->num_rows($query1);
		$totalwithtax_total = 0;
		$totalwithouttax_total = 0;
		for($i=0; $i < $row1; $i++){
			$productname = $adb->query_result($query1,$i,'productname');
			$select_qty = $adb->query_result($query1,$i,'selected_qty');
			$price = $adb->query_result($query1,$i,'price');
			$productid = $adb->query_result($query1,$i,'productid');
			
			$query2 = $adb->pquery("SELECT SUM(taxpercentage) AS taxpercent FROM vtiger_producttaxrel WHERE productid=? ",array($productid));
			$taxpercent = $adb->query_result($query2,0,'taxpercent');
			
			$sno = $i+1;
			$total_p = $select_qty * $price;
			$totalwithouttax_total = number_format(($totalwithouttax_total + $total_p), 2, '.', '');
			
			
			$totalwithtax = $total_p + ($total_p * ($taxpercent/100));
			$totalwithtax_total = number_format(($totalwithtax_total + $totalwithtax), 2, '.', '');
			
			if($tax_settings == 1){
				$contents .= '<tr><td style="padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;" align="center">'.$sno.'</td>
							<td style="padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;" align="center">'.$productname.'</td>
							<td style="padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;" align="center">'.number_format($select_qty,2, '.', '').'</td>
							<td style="padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;" align="center">'.number_format($price,2, '.', '').'</td>
							<td style="padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;" align="center">'.number_format($total_p,2, '.', '').'</td>
							<td style="padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;" align="center">'.number_format($totalwithtax,2, '.', '').'</td>
						</tr>';
			}else{
				$contents .= '<tr><td style="padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;" align="center">'.$sno.'</td>
							<td style="padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;" align="center">'.$productname.'</td>
							<td style="padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;" align="center">'.number_format($select_qty,2, '.', '').'</td>
							<td style="padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;" align="center">'.number_format($price,2, '.', '').'</td>
							<td style="padding-top: 12px;padding-bottom: 12px;text-align: left;color: black;" align="center">'.number_format($total_p,2, '.', '').'</td>
						</tr>';
			}
			
		}
		
		if($tax_settings == 1){
				$contents1 = '
						<tr style="background-color: #f2f2f2;">
							<td align="center" colspan=5>Total Amount:</td>
							<td>'.number_format($totalwithtax_total,2, '.', '').'</td>
						</tr>
					  <tr style="background-color: #d2d2d3;">
						<td align="center" colspan=5 >Cash Tendered:</td>
						<td>'.number_format($tendered,2, '.', '').'</td>
					  </tr>
					  <tr style="background-color: #f2f2f2;">
						<td align="center" colspan=5 >Cash Return:</td>
						<td>'.number_format($return,2, '.', '').'</td>
					  </tr>
					  <tr style="background-color: #d2d2d3;">
						<td align="center" colspan=5 >Total Items:</td>
						<td>'.number_format($total_item,2, '.', '').'</td>
					  </tr>';
			}else{
				$contents1 = '
						<tr style="background-color: #f2f2f2;">
							<td align="center" colspan=4 >Total Amount:</td>
							<td >'.number_format($totalwithouttax_total,2, '.', '').'</td>
						</tr>
						  <tr style="background-color: #d2d2d3;">
							<td align="center" colspan=4 >Total With Tax:</td>
							<td>'.number_format($totalwithtax_total,2, '.', '').'</td>
						  </tr>
						  <tr style="background-color: #f2f2f2;">
							<td align="center" colspan=4>Cash Tendered:</td>
							<td>'.number_format($tendered,2, '.', '').'</td>
						  </tr>
						  <tr style="background-color: #d2d2d3;">
							<td align="center" colspan=4>Cash Return:</td>
							<td>'.number_format($return,2, '.', '').'</td>
						  </tr>
						  <tr style="background-color: #f2f2f2;">
							<td align="center" colspan=4>Total Items:</td>
							<td>'.number_format($total_item,2, '.', '').'</td>
						  </tr>';
			}
		
		
		$tbl_footer = '</tbody></table>';

		$pdf->writeHTML($tbl_header . $contents . $contents1 . $tbl_footer, true, false, true, false, '');
		//$pdf->writeHtml($contents, true, false, false, false, '');
		
		$js .= 'print(true);';
		$pdf->IncludeJS($js);
		$pdf->Output('receipt.pdf', 'I');
	}
}
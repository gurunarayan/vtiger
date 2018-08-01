<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
require_once 'include/Webservices/Create.php';
require_once ('modules/PointSale/PointSale.php');
class PointSale_IndexAjax_Action extends Vtiger_SaveAjax_Action {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('addItem');
		$this->exposeMethod('getCatVal');				
		$this->exposeMethod('saveData');	
		$this->exposeMethod('getProductWithCode');	
		$this->exposeMethod('posSettings');	
		
	}
	
	public function process(Vtiger_Request $request) {
		$mode = $request->get('mode');
		if (!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}
	
	public function addItem(Vtiger_Request $request){
		global $adb;
		//$adb->setDebug(true);
		//print_r($request);
		$id = $request->get('productid');
		$response = new Vtiger_Response();
		$product_List = array();
		
		$query = "SELECT vtiger_products.*, SUM(taxpercentage) AS taxpercent FROM vtiger_products 
					LEFT JOIN vtiger_producttaxrel ON vtiger_producttaxrel.productid = vtiger_products.productid
					WHERE vtiger_products.productid =?";
		$result = $adb->pquery($query,array($id)); 
			while( $row = $adb->fetchByAssoc($result)){
				$product_List[] = $row;
			}
			
		$response->setResult(array("result"=>json_encode($product_List), 'success'=>true));
		$response->emit();
	}
	
	public function saveData(Vtiger_Request $request){
		global $adb, $current_user;
		//print_r($request);
		//$adb->setDebug(true);
		$data = $request->get('tabledata');
		$total = $request->get('total');
		$paid = $request->get('paid');
		$return = $request->get('return');
		$contactid = $request->get('contactid');
		
		
		$currenuserid = $_SESSION['authenticated_user_id'];
		$currentUser = $current_user->retrievecurrentuserinfofromfile($currenuserid);
	
		$pos = new PointSale();
        $pos->column_fields['pointsalename'] = 'pointsalename';
		$pos->column_fields['total_amt'] = $total;
		$pos->column_fields['paid_amt'] = $paid;
		$pos->column_fields['return_amt'] = $return;
		$pos->column_fields['related_contact'] = $contactid;
		$pos->column_fields['assigned_user_id'] = 1;
		$pos->save('PointSale');
		$pointsaleid = $pos->id;
		
		for($i=0;$i<count($data);$i++){
			
			$adb->pquery("INSERT INTO vtiger_posdetails(posid, productid, price, total_qty, selected_qty, taxpercent) VALUES(?,?,?,?,?,?)",array($pointsaleid, $data[$i]['productId'], $data[$i]['price'], $data[$i]['totalQuantity'], $data[$i]['selectedQuantity'], $data[$i]['taxpercent']));
			
			$get_qty = $adb->pquery("SELECT qtyinstock FROM vtiger_products WHERE productid=?",array($data[$i]['productId']));
			$p_qty = $adb->query_result($get_qty, 0, 'qtyinstock');
			$adb->pquery("UPDATE vtiger_products SET qtyinstock=? WHERE productid=?",array( ($p_qty - $data[$i]['selectedQuantity']), $data[$i]['productId'] ));
		}
		$q = $adb->pquery("SELECT pointsale_no FROM vtiger_pointsale WHERE vtiger_pointsale.pointsaleid=?",array($pointsaleid));
		$saleno = $adb->query_result($q,0,'pointsale_no');
		$date = date("jS F, Y H:i:s");
		
		$q2 = $adb->pquery("SELECT SUM(selected_qty) AS total_sel_qty FROM vtiger_posdetails WHERE posid=?",array($pointsaleid));
		$total_sel_qty = $adb->query_result($q2, 0, 'total_sel_qty');
		
		$response = new Vtiger_Response();
		$response->setResult(array('id'=>$pointsaleid, 'sale'=>$saleno, 'date'=>$date, 'total_sel_qty'=>$total_sel_qty));
		$response->emit();
	}
	
	public function getCatVal(Vtiger_Request $request){
		global $adb;
		//$adb->setDebug(true);
		//print_r($request);
		$category = $request->get('category');
		$response = new Vtiger_Response();
		$data = array();
		
		
		if($category == 'All'){
			$query = $adb->pquery("SELECT vtiger_products.productid, vtiger_products.qtyinstock, ROUND((vtiger_products.unit_price), 2) AS unitprice, productname, 		vtiger_seattachmentsrel.attachmentsid FROM vtiger_products 
							LEFT JOIN vtiger_productcf ON vtiger_productcf.productid = vtiger_products.productid 
							LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid 
							LEFT JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.crmid = vtiger_products.productid 
							WHERE vtiger_productcf.is_pos = 1 AND vtiger_crmentity.deleted =0",array());
			
		}else{
			$query = $adb->pquery("SELECT vtiger_products.productid, vtiger_products.qtyinstock, ROUND((vtiger_products.unit_price), 2) AS unitprice, productname, 		vtiger_seattachmentsrel.attachmentsid FROM vtiger_products 
							LEFT JOIN vtiger_productcf ON vtiger_productcf.productid = vtiger_products.productid 
							LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid 
							LEFT JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.crmid = vtiger_products.productid 
							WHERE vtiger_productcf.is_pos = 1 AND vtiger_crmentity.deleted =0 AND productcategory=?",array($category));
		}
		
		$data=array();
		$i=0;
		while($resultinfo = $adb->fetch_array($query))
		{
			$data[$i]['productid'] = $resultinfo["productid"];
			$data[$i]['qtyinstock'] = $resultinfo["qtyinstock"];
			$data[$i]['productname'] = $resultinfo["productname"];
			$data[$i]['attachmentsid'] = $resultinfo["attachmentsid"];
			$data[$i]['unitprice'] = $resultinfo["unitprice"];
			$sql1 = $adb->pquery("SELECT * FROM vtiger_attachments WHERE attachmentsid=?",array($resultinfo["attachmentsid"]));
			$data[$i]['path'] = $adb->query_result($sql1, 0, 'path');
			$data[$i]['name'] = $adb->query_result($sql1, 0, 'name');
			$sql2 = $adb->pquery("SELECT SUM(taxpercentage) AS taxpercent FROM vtiger_producttaxrel WHERE productid=?",array($resultinfo["productid"]));
			$data[$i]['taxpercent'] = $adb->query_result($sql2, 0, 'taxpercent');
			$i++;
		} 	
		$response->setResult(array("result"=>json_encode($data), 'success'=>true));
		$response->emit();
	}
	
	public function getProductWithCode(Vtiger_Request $request){
		global $adb;
		$code = $request->get('code');
		$data_arr = array();
		
		$result = $adb->pquery("SELECT vtiger_products.*, SUM(taxpercentage) AS taxpercent FROM vtiger_products
						LEFT JOIN vtiger_productcf ON vtiger_productcf.productid = vtiger_products.productid 
						LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid
						LEFT JOIN vtiger_producttaxrel ON vtiger_producttaxrel.productid = vtiger_products.productid
						WHERE vtiger_crmentity.deleted=0 AND vtiger_productcf.is_pos = 1 AND vtiger_productcf.barcode=?",array($code));
		while( $row = $adb->fetchByAssoc($result)){
			$data_arr[] = $row;
		}
		$response = new Vtiger_Response();
		$response->setResult(array("result"=>json_encode($data_arr), 'success'=>true));
		$response->emit();
	}
	
	public function posSettings(Vtiger_Request $request){
		global $adb;
		//$adb->setDebug(true);
		$currency = $request->get('selected_modules');
		if($currency != ''){
			$currencys = implode(',', $currency);
		}
		$tax = $request->get('taxstatus');
		
		$query = $adb->pquery("SELECT * FROM vtiger_pos_settings",array());
		if($adb->num_rows($query) > 0) {
			$adb->pquery("UPDATE vtiger_pos_settings SET tax=?, currency=?",array($tax, $currencys));
		}else{
			$adb->pquery("INSERT INTO vtiger_pos_settings(tax, currency) VALUES(?,?)",array($tax, $currencys));
		}	
		
		$response = new Vtiger_Response();
		$response->setResult(array('success' => true));
		$response->emit();
	} 
}

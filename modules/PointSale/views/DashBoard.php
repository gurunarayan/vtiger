<?php 
/** License Text Here **/
class PointSale_DashBoard_View extends Vtiger_Index_View {
    function __construct() {
		parent::__construct();
	}
	
	public function preProcess(Vtiger_Request $request, $display = true) {
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_NAME', $request->getModule());

		parent::preProcess($request, false);
		if($display) {
			$this->preProcessDisplay($request);
		}
	}
   
	protected function preProcessTplName(Vtiger_Request $request) {
		return 'DashboardPreProcess.tpl';
	}
	
	public function getHeaderCss1(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$parentCSSScripts = parent::getHeaderCss($request);
		$styleFileNames = array();
		
		$cssScriptInstances = $this->checkAndConvertCssStyles($styleFileNames);
		$headerCSSScriptInstances = array_merge($parentCSSScripts, $cssScriptInstances);
		return $headerCSSScriptInstances;
	}
	public function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$jsFileNames = array(
			"modules.Vtiger.resources.Edit",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	public function process(Vtiger_Request $request) {
		global $adb;
		
		$mode = $request->getMode();
		if(!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		
		$sql= $adb->pquery("SELECT vtiger_products.productid, vtiger_products.qtyinstock, ROUND((vtiger_products.unit_price), 2) AS unitprice, productname, 		vtiger_seattachmentsrel.attachmentsid FROM vtiger_products 
							LEFT JOIN vtiger_productcf ON vtiger_productcf.productid = vtiger_products.productid 
							LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid 
							LEFT JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.crmid = vtiger_products.productid 
							WHERE is_pos = 1 AND vtiger_crmentity.deleted =0",array());
	
		$data=array();
		$i=0;
		while($resultinfo = $adb->fetch_array($sql))
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

		$sql2 = $adb->pquery("SELECT CONCAT(firstname,' ',lastname) AS name, contactid, mobile FROM vtiger_contactdetails 
							LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid 
							WHERE deleted = 0",array());
		$contact=array();
		$j=0;
		while($con_result = $adb->fetch_array($sql2))
		{
			$contact[$j]['name'] = $con_result['name'];
			$contact[$j]['contactid'] = $con_result['contactid'];
			$contact[$j]['mobile'] = $con_result['mobile'];
			$j++;
		}
		
		
		$y1 = $adb->pquery("SELECT productcategory FROM vtiger_productcategory",array());
		$y=0;
		$yogita = array();
		$category = array();
		while($y_result = $adb->fetch_array($y1))
		{
			$yogita[$y]['category'] = $y_result['productcategory'];
			$category[$y] = $y_result['productcategory'];
			$inner_q = $adb->pquery("SELECT vtiger_products.productid ,ROUND((vtiger_products.unit_price), 2) AS unitprice, productname, 			vtiger_seattachmentsrel.attachmentsid FROM vtiger_products 
							LEFT JOIN vtiger_productcf ON vtiger_productcf.productid = vtiger_products.productid 
							LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid 
							LEFT JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.crmid = vtiger_products.productid 
							WHERE is_pos = 1 AND vtiger_crmentity.deleted =0 AND productcategory=? ",array($y_result['productcategory']));
			$o = 0;				
			while($inner_result = $adb->fetch_array($inner_q)){
				$yogita[$y]['data'][$o]['productid'] = $inner_result["productid"];
				$yogita[$y]['data'][$o]['productname'] = $inner_result["productname"];
				$yogita[$y]['data'][$o]['attachmentsid'] = $inner_result["attachmentsid"];
				$yogita[$y]['data'][$o]['unitprice'] = $inner_result["unitprice"];
				$y_2 = $adb->pquery("SELECT * FROM vtiger_attachments WHERE attachmentsid=?",array($inner_result["attachmentsid"]));
				$yogita[$y]['data'][$o]['path'] = $adb->query_result($y_2, 0, 'path');
				$yogita[$y]['data'][$o]['name'] = $adb->query_result($y_2, 0, 'name');
				
				$sql2 = $adb->pquery("SELECT SUM(taxpercentage) AS taxpercent FROM vtiger_producttaxrel WHERE productid=?",array($inner_result["productid"]));
				$yogita[$y]['data'][$o]['taxpercent'] = $adb->query_result($sql2, 0, 'taxpercent');
				$o++;
			}
			$y++;
		}		
		
		$viewer->assign('YOGITA',$yogita);
		$viewer->assign('CATEGORY',$category);
		$viewer->assign('CONTACT',$contact);
		$viewer->assign('ALLPRODUCTS',$data);
		
		$pos_setting_qury = $adb->pquery("SELECT * FROM vtiger_pos_settings",array());
		if($adb->num_rows($pos_setting_qury) > 0){
			$tax_settings = $adb->query_result($pos_setting_qury,0,'tax');
			$currency = $adb->query_result($pos_setting_qury,0,'currency');
			
			$currency_arr = explode(',', $currency);
			
			$viewer->assign('TAX_SETTINGS',$tax_settings);
			$viewer->assign('CURRENCY_SETTINGS',$currency_arr);
			$viewer->assign('CURRENCY',$currency);
		}
		
		$viewer->view('Index.tpl', $request->getModule());
	}
	
	
	
}
?>
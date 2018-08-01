<?php
class OS2UserSettings_ToggleAction_Action extends Vtiger_Action_Controller {
   
	function checkPermission(Vtiger_Request $request) {  
               return true;  
    } 
	
   function process(Vtiger_Request $request) {
		global $adb;
		$db = PearDatabase::getInstance();
		$toggle = $request->get('toggle');
		$userid= $request->get('userid');
		$sql= $adb->pquery("SELECT status,is_admin FROM vtiger_users WHERE id=?",array($userid));
		$resultinfo = $adb->fetch_array($sql);
		$status=$resultinfo["status"];
		$is_admin=$resultinfo["is_admin"];
		if($toggle=='ToggleStatus')
		{
			if($status=='Active') $update_status='Inactive';
			else $update_status='Active';
			
			$f = new Users();
			$f->retrieve_entity_info($userid, "Users");
			$f->column_fields['status'] = $update_status;
			$f->mode='edit';
			$f->id=$userid;
			$f->save("Users",$userid);
			
		}
		if($toggle=='ToggleAdmin')
		{
			if($is_admin=='on') $update_admin='off';
			else $update_admin='on';
			
			$f = new Users();
			$f->retrieve_entity_info($userid, "Users");
			$f->column_fields['is_admin'] = $update_admin;
			$f->mode='edit';
			$f->id=$userid;
			$f->save("Users",$userid);
		}
		$response = new Vtiger_Response();
		$response->setEmitType(Vtiger_Response::$EMIT_JSON);
		$response->setResult(array("msg"=>"SUCCESS"));
		$response->emit();

    }

}
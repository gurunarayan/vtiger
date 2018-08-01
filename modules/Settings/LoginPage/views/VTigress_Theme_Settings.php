<?php
require_once "includes/runtime/Viewer.php";
$moduleName = 'Users';
//if(isModuleActive('LoginPage')){ 
	$viewer = new Vtiger_Viewer();
	global $adb;
	$res = $adb->pquery("SELECT * FROM vtigress_themes WHERE Status=?",array('1'));
	if($adb->num_rows($res) > 0){
		$data = $adb->fetch_row($res);
		$themename = $data['name'];
		$temp_file = 'layouts/vlayout/modules/Settings/LoginPage/';
		$filepath = $temp_file.$themename.".tpl";
		$newpath =  'layouts/vlayout/modules/Users/'.$themename.'.tpl';
		if(file_exists($filepath)){
			copy($filepath, $newpath);
			$viewer->view($themename.'.tpl', 'Users');
			die;
		}
	}
	
//}
?>

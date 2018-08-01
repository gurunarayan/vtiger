<?php
require_once 'modules/Documents/config.php';
//print_r($vtDocsSettings[$relmod]);
$relmod = $_REQUEST['relmod'];
 $tree = getVirtualTreeDocs();
 $select = "<select class='chzn-select chzn-done' name='folderslist'  style='width: 220px;' >";
for($i=0; $i<count($tree);$i++){
	if($vtDocsSettings[$relmod]['folderslist'] == $tree[$i][0]){
		$select .= "<option value='".$tree[$i][0]."' selected>".$tree[$i][1]."</option>";
	}else{
		$select .= "<option value='".$tree[$i][0]."'>".$tree[$i][1]."</option>";
	}
}
$select .= "</select>";	
echo json_encode($select);
 function getVirtualTreeDocs(){
	$db = PearDatabase::getInstance();
	$floderquery = "SELECT folderid, foldername FROM vtiger_attachmentsfolder order by foldername ASC";
	$fres = $db->query($floderquery);
	$numRows = $db->num_rows($fres);
	$folders = array();
	for ($i = 0; $i < $numRows; $i++) {
		$folderid = $db->query_result($fres, $i, "folderid");
		$foldername = $db->query_result($fres, $i, "foldername");
		$folders[]= array($folderid,$foldername);
	}
	return $folders;
}
?>
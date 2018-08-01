<?php
////Function added by SL for DD files enable :28th July '15:start
class Documents_DDSettingsSave_Action extends Vtiger_SaveAjax_Action {
        public function process(Vtiger_Request $request) {
			global $root_directory;
			    $response = new Vtiger_Response();
				$db = PearDatabase::getInstance();
             
				  $request->get('mname');
                 $tabid = getTabid($request->get('mname'));
                $operation = $request->get('operation');
			
               if ($tabid) {
				 	$chkquery = 'select * from vtiger_documentmodulesrel where tabid = ? and modulename =?';
					$chkresult = $db->pquery($chkquery,array($tabid,$request->get('mname')));
				 	$cnt = $db->num_rows($chkresult);
                    //we are toggling a tabid, and returning the current status of that tab

                    if(trim($operation)=="enable"){//if it is on at the moment we delete it
						 
						if($cnt>0){
                    
						Vtiger_Link::addLink($tabid, 'DETAILVIEWSIDEBARWIDGET', "Folders", 'module=Documents&view=AttachDocuments','','','');
						}
						
					 	$db->pquery("update vtiger_documentmodulesrel set enabled=1 where tabid=?",Array($tabid));

                        $result=true;
                    }
					/*else{
                        Vtiger_Link::deleteLink($tabid, 'DETAILVIEWSIDEBARWIDGET', 'Folders');
						$db->pquery("update vtiger_documentmodulesrel set enabled=0 where tabid=?",Array($tabid));
                        $result=false;
                    }*/
                   $response->setResult(array('success'=>$result));  
                } else {
                    $response->setError(vtranslate('Failed to enable',$request->get('mname')));
                }
				$response->emit();
                

        }
}

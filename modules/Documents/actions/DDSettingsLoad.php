<?php
////Function added by SL for DD settings  :6th August '15:start
class Documents_DDSettingsLoad_Action extends Vtiger_SaveAjax_Action {
        public function process(Vtiger_Request $request) {
			 $response = new Vtiger_Response();
               require_once 'modules/Documents/config.php';
               $moduleName= $request->get('mname');
				 foreach($vtDocsSettings as $key => $val)
			{
				if ($key == $moduleName){
					$result= $val;
				}
			}
			$response->setResult(array($result)); 
			$response->emit();

        }
}

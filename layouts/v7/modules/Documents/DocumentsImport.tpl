{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
********************************************************************************/
-->*}
<script src="libraries/jquery/jquery-ui/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>

{strip}
	<div class="padding-left1per">
		<div class="row-fluid widget_header">
			<div class="span8">
				<h3>{vtranslate('Docs++ Settings', $QUALIFIED_MODULE)}</h3>
				{if $DESCRIPTION}<span style="font-size:12px;color: black;"> - &nbsp;{vtranslate({$DESCRIPTION}, $QUALIFIED_MODULE)}</span>{/if}
			</div>
			<div class="span4">
			<!--button id="updateCompanyDetails" class="btn pull-right">{vtranslate('LBL_EDIT',$QUALIFIED_MODULE)}</button-->
			</div>
		</div>
		<div>
			<table class="table equalSplit">
				<tr>
					<td width="100%">
						{assign var=WIDTHTYPE value=$CURRENT_USER_MODEL->get('rowheight')}
						{include file="modules/Documents/DocumentsImportForm.tpl"}
					</td>
				</tr>
			</table>
		</div>
		</div>

	 <!-- MODALS -->
 <script>
 $(document).ready(function() {
  $("button.chooseEntityModule").click(function(e){ 
  var params = {
    'module' : 'Documents',
    'parent' : 'Settings',
    'action' : 'DDImportRecords',
    
    'mname' : "Documents",
    'operation' : 'import'

   };
   AppConnector.request(params).then(function(data) {
     
     console.log('data.success',data.success);
      
   },
   function(error,err){
   });
   })
  
  
   
});
	
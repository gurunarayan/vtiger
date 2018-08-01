{*<!--
/*********************************************************************************
 ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/
-->*}
{literal}
 
<link rel='stylesheet' href='libraries/jstree/themes/default/style.min.css' /> 
 
 {/literal}
<div id='folder_tree'  style='max-height:300px;overflow: scroll; float:left;overflow-y: scroll;'></div>		
{strip}
<!--<script type="text/javascript" src="libraries/jquery/jquery.min.js"></script>
<script src='libraries/jstree/jstree.js'></script>-->
<script>  
/* $j = jQuery.noConflict(true); */
jQuery(document).ready(function(){  
	 
	var jsondata = {$FOLDERS_DATA};

	jQuery('#folder_tree').jstree({  
		'plugins' : [ 'types','themes'],
		'themes' : { 
			'theme' : "classic", 
            'dots' : true,
            'icons' : true 
         },  
		'core' : { 

				'data' :  jsondata
			},
			'types' : {	
				'#' : {
					
					},
				'root' : {
					  'icon' : 'jstree-file',
					  'valid_children' : ['default']
					},			
				'default' : { 'valid_children' : ['default','file'] },
				'file' : { 'icon' : 'jstree-file' }
			}  
		}); 
});
</script>

{/strip}

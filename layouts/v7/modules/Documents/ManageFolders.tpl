{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/
-->*}
<link href="modules/Documents/plugins/jquery.ui.fancytree.css" rel="stylesheet" type="text/css">
<script src="modules/Documents/plugins/jquery.fancytree.js" type="text/javascript"></script>
<!-- Initialize the tree when page is loaded -->
{literal}
<script type="text/javascript">
jQuery(document).ready(function(){
	// Create the tree inside the <div id="tree"> element.
	$("#ntree").fancytree({
      source: {
        url: 'modules/Documents/plugins/results.json'
      },
		debugLevel: 2
	});
});
</script>
{/literal}
<img src="./modules/Documents/plugins/folder-tree.png"><strong>Documents Root</strong>
<div id="ntree" style='min-width:600px;'>
</div>
{*strip}
    <div class="modalContainer" style='min-width:600px;'>
        <div class="modal-header contentsBackground">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>{vtranslate('LBL_MANAGE_FOLDERS', $MODULE)}</h3>
        </div>
		<div class="modal-body">
		</div>
    </div>
{/strip*}

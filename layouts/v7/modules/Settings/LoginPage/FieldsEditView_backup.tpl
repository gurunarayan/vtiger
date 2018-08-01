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
{strip}
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<script type="text/javascript">
jQuery(function() {
    jQuery(".chzn-select").chosen();
});
</script>
<div class="listViewEntriesDiv">
	<input type="hidden" name="module" value="LoginPage">
	<input type="hidden" name="parent" value="Settings">
	<input type="hidden" name="action" value="save">
		<form class="form-horizontal recordEditView" id="EditView" name="EditView" method="post" action="" enctype="multipart/form-data">
			
			{if $ROWS==''}
		<table class="table table-bordered marginLeftZero">
			<tbody>
				<tr class="listViewActionsDiv">
					<th colspan="4">Page Information</th>
				</tr>
				<tr>
					<td class="fieldLabel medium">Page Type</td>
					<td class="fieldValue medium"><input type="text" name="row" id="row" value="">&nbsp;X&nbsp;<input type="text" name="column" id="column" value="">&nbsp;&nbsp;&nbsp;<input type="button" class="create" id="create" value="Create"></td>
				</tr>
			</tbody>
		</table>
		{/if}
		<div class="page">
			<div class="container-fluid login-container">
				<table>
				{for $x = 0 to $ROWS-1} 
				 <tr id="{$x}" class="container">
					{for $y = 0 to $COLUMNS-1} 
				 <td style="padding: 15px;" id="{$x}{$y}" name="section[]" class="data">
				 <div>
				 <select name="page[]" class="text chzn-select select_page_view">
							<option value="">Select Value</option>
							<option value="Login Box">Login Box</option>
							<option value="Image Slider">Image Slider</option>
							<option value="Content Slider">Content Slider</option>
							<option value="Image">Image</option>
							<option value="Logo">Logo</option>
							<option value="Social Icons">Social Icons</option>
							<option value="Website Links">Website Links</option>
				</select>
				 </div>
				 </td>
				 {/for}
				  </div>
				{/for} 
				</table>
				{if $ROWS!=''}
				 <div class="row-fluid">
				 <button class="btn btn-default" type="button" id="preview"><strong>Preview</strong></button>
            <div class="pull-right">
				<button class="btn btn-success" type="submit"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
				<a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $MODULE)}</a>
			</div>
			<div class="clearfix"></div>
        </div>
        {/if}
			</div>
		</div>
{/strip}

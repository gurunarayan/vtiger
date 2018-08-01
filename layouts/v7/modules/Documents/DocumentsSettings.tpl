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
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.10.1/skin-xp/ui.fancytree.css" rel="stylesheet" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.10.1/jquery.fancytree.js" type="text/javascript"></script>
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
						{include file="modules/Documents/DocumentsSettingsForm.tpl"}
					</td>
				</tr>
			</table>
		</div>

	<!-- MODALS -->
	<div id="ModuleDocumentsContainer">
		<div class="modal moduleDocsSettingsModal hide">
			<div class="modal-header contentsBackground">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 id="modalheading">{vtranslate('Documents Options', $QUALIFIED_MODULE)}</h3>
			</div>
			<form class="form-horizontal moduleDocsSettingsForm" id='moduleDocsSettingsForm' method="POST">
				<div class="modal-body">
					<div class="control-group">
						<span class="control-label">
							<span class="redColor">*</span>
							<span>{vtranslate('Default Selected Folder', $QUALIFIED_MODULE)}</span>
						</span>
						<div class="controls">
						{*<input type="text" name="label" readonly class="span3" data-validation-engine="validate[required]" />
						<hr>*}
							<div id="foldertree">
							<select class='chzn-select chzn-done' name='folderslist' id='folderslist' style='width: 220px;' >
							{foreach item=FOLDER_NAME key=FOLDER_VALUE from=$FOLDERS}
								<option value="{$FOLDER_NAME[0]}">{$FOLDER_NAME[1]}</option>
							{/foreach}
							</select>
							</div>
						{*manasa commented this on jan 20 2016*}
						</div>
					</div>
					{*<div class="control-group">
						<span class="control-label">
							{vtranslate('Multiple Records', $QUALIFIED_MODULE)}
						</span>
						<div class="controls">
							<span class="row-fluid">
								<input type="checkbox" name="multiplerecords">
							</span>
						</div>
					</div>*}
					<div class="control-group">
						<span class="control-label">
							<strong>
								{vtranslate('Upload Restrictions', $QUALIFIED_MODULE)}
							</strong>
						</span><!-- Added Radio buttons by SL on 4th August 2015:start -->
						<div class="controls">
							<span class="row-fluid"><label class="radio">
							     <label class="radio">
								<input   type="radio"     name="fileextensions" value='Allowall'>
								Allow all
								</label>
								<label class="radio">
								<input   type="radio"     name="fileextensions" value='Exclude'>
								All except extensions selected below 
								</label>
								<label class="radio">
								<input   type="radio"     name="fileextensions" value='Include'>
								Only extensions selected below
								</label></span>

						</div>
					</div>
					<div class="control-group">
						<span class="control-label">
							<strong>
								{vtranslate('Select Extensions', $QUALIFIED_MODULE)}
							</strong>
						</span> 
						<div class="controls">
						  <em><label for="multiple-label-example">Click to Highlight Multiple Select</label></em>
						  {*<select data-placeholder="Types of Extensions"  class="select2-container columnsSelect" style="width:235px;" >*}
						  
<select id="viewColumnsSelect123" name="xyz" class="columnsSelect" multiple="" customview="" data-placeholder="Types of Extensions" style="display: none;">
						  <option value=""></option>
						   <option value=".jpg">.jpg</option>
						    <option value=".jpeg">.jpeg</option>
						    <option value=".png">.png</option>
						    <option value=".gif">.gif</option>
						    <option value=".bmp">.bmp</option>
						    <option value=".pdf">.pdf</option>
						    <option value=".ppt">.ppt</option>
						    <option value=".doc">.doc</option>
						    <option value=".odt">.odt</option>
						    <option value=".xls">.xls</option>
						  </select>
						  <div id="selectedextensitions"></div>
						  {*<input type='hidden' name='hdextensions' id='hdextensions' value=''>*}
						   <input type='hidden' name='hdModulename' id='hdModulename' value=''>{* manasa *}
						</div>
						 
					</div>
				</div><!-- Added Radio buttons by SL on 4th August 2015:end -->
				{include file='ModalFooter.tpl'|@vtemplate_path:'Vtiger'}
			</form>
		</div>
	</div>
	{/strip}
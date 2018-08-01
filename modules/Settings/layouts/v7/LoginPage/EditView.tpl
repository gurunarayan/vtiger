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
<div class='container-fluid editViewContainer'>
        <form class="form-horizontal recordEditView" id="EditView" name="EditView" method="post" action="" enctype="multipart/form-data">
            <div class="contentHeader row-fluid">
                {assign var=SINGLE_MODULE_NAME value='SINGLE_'|cat:$MODULE}
                {if $RECORD_ID neq ''}
                    <h3 class="span8 textOverflowEllipsis" title="{vtranslate('LBL_EDITING', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)} {$RECORD_STRUCTURE_MODEL->getRecordName()}">{vtranslate('LBL_EDITING', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)} - {$RECORD_STRUCTURE_MODEL->getRecordName()}</h3>
                {else}
                    <h3 class="span8 textOverflowEllipsis">{vtranslate('LBL_CREATING_NEW', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)}</h3>
                {/if}
                <span class="pull-right">
                    <button class="btn btn-success" type="submit"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
                    <a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                </span>
            </div>
<div class="targetFieldsTableContainer">
	{include file="FieldsEditView.tpl"|@vtemplate_path:$QUALIFIED_MODULE}
</div>
<br>	
{include file="EditViewActions.tpl"|@vtemplate_path:Vtiger}
<div class="row-fluid" style="margin-bottom:150px;"></div>
{/strip}

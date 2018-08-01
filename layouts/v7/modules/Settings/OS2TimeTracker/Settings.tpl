{*<!--
/* ********************************************************************************
 * The content of this file is subject to the Time Tracker ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */
-->*}

<div class="container-fluid">
    <div class="widget_header row-fluid">
        <h3>{'OS2TimeTracker Settings'}</h3>
    </div>
    <hr>
    <div class="clearfix"></div>
    <form action="index.php" id="formSettings">
        <input type="hidden" name="module" value="OS2TimeTracker"/>
        <input type="hidden" name="parent" value="Settings"/>
        <input type="hidden" name="action" value="SaveAjax"/>
        <div class="summaryWidgetContainer">
            <ul class="nav nav-tabs massEditTabs">
                <li class="active">
                    <a href="#module_LBL_MODULE_SETTINGS" data-toggle="tab">
                        <strong>{'MODULE SETTINGS'}</strong>
                    </a>
                </li>
                <li>
                    <a href="#module_LBL_EVENT_SETTINGS" data-toggle="tab">
                        <strong>{'EVENT SETTINGS'}</strong>
                    </a>
                </li>
                <!--<li>
                    <a href="#module_LBL_TIME_TRACKING_SETTINGS" data-toggle="tab">
                        <strong>{'TIME TRACKING SETTINGS'}</strong>
                    </a>
                </li>
                -->
            </ul>
            <div class="tab-content massEditContent">
                 {assign var=SELECTED_MODULES value=$SETTING_MODULES}
                <div class="tab-pane active" id="module_LBL_MODULE_SETTINGS">
                    <div class="widgetContainer" style="padding: 20px 5px 5px 20px;">
                        {foreach from=$MODULES_LIST item=MODULE}
                            <div class="row-fluid">
                                <input type="checkbox" value="{$MODULE}" {if $SELECTED_MODULES neq ''}{if in_array($MODULE,$SELECTED_MODULES)}checked{/if}{/if}  class="selectedModules"/>&nbsp;&nbsp; {vtranslate($MODULE, $MODULE)}
                            </div>
                        {/foreach}
                    </div>
                </div>

                <div class="tab-pane" id="module_LBL_EVENT_SETTINGS">
                  {assign var=FIELD_SETTINGS value=$SETTING_FIELDS}
                    <div class="widgetContainer" style="padding: 20px 5px 5px 20px;">
                        <table class="table table-bordered equalSplit" style="width: 50%;">
                            <thead>
                            <tr>
                                <th>{'FIELD'}</th>
                                <th>{'VISIBLE'}</th>
                                <th>{'DEFAULT'}</th>
								
                            </tr>
                            </thead>
                            <tbody>
								{foreach from=$FIELD_NAME item=FIELDLIST}
									{assign var="FIELD_MODEL" value=$EVENT_MODULE->getField($FIELDLIST)}
										
									 <tr>
										<td>{vtranslate($FIELD_MODEL->get('label'), $EVENT_MODULE->getName())}</td>
										<td><input type="checkbox" value="1"{if $FIELD_SETTINGS[$FIELDLIST]['visible']}checked{/if} name="field_settings[{$FIELDLIST}][visible]"></td>
										<td>
										{if $FIELDLIST neq 'description' && $FIELDLIST neq 'subject'}
                                            {assign var=PICKLIST_VALUES value=$FIELD_MODEL->getPicklistValues()}
                                            <div class="row-fluid">
                                                    <span class="span10">
                                                        <select class="chzn-select" name="field_settings[{$FIELDLIST}][default]">
                                                            <option value="">{vtranslate('LBL_SELECT_OPTION','Vtiger')}</option>
                                                            {foreach item=PICKLIST_VALUE key=PICKLIST_NAME from=$PICKLIST_VALUES}
                                                                <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_NAME)}" {if trim(decode_html($FIELD_SETTINGS[$FIELDLIST]['default'])) eq trim($PICKLIST_NAME)} selected {/if}>{$PICKLIST_VALUE}</option>
                                                            {/foreach}
                                                        </select>
                                                    </span>
                                            </div>
                                        {else}
                                            <div class="row-fluid">
                                                    <span class="span10">
                                                        <input type="text" class="input-large fieldInput" name="field_settings[{$FIELDLIST}][default]" value="{$FIELD_SETTINGS[$FIELDLIST]['default']}" />
                                                    </span>
                                            </div>
                                        {/if}
										</td>
									</tr>
								{/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--<div class="tab-pane" id="module_LBL_TIME_TRACKING_SETTINGS">
                    <div class="widgetContainer" style="padding: 20px 5px 5px 20px;">
                        <div class="row-fluid">
                            <span class="span2">
                                {'START DATETIME Editable'}
                            </span>
                            <input type="checkbox" value="1" name="start_datetime_editable"/>
                        </div>
                        <div class="row-fluid">
                            <span class="span2">
                                {'DUE DATETIME Editable'}
                            </span>
                            <input type="checkbox" value="1"  name="due_datetime_editable"/>
                        </div>
                    </div>
                </div>
                -->

                
            <div style="margin-top: 20px;">
                <span>
                    <button class="btn btn-success" type="button" id="btnSaveSettings">{vtranslate('LBL_SAVE')}</button>
                </span>
            </div>
        </div>
    </form>
</div>
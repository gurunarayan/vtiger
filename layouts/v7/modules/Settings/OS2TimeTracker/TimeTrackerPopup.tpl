{strip}
    <div style="width: 270px;">
        <link rel="stylesheet" href="layouts/v7/modules/Settings/OS2TimeTracker/css/bootstrap-datetimepicker.min.css" type="text/css" media="screen" />  
        <script type="text/javascript" src="layouts/v7/modules/Settings/OS2TimeTracker/resources/bootstrap-datetimepicker.min.js"></script> 
		
        <div class="modelContainer" >
            <div class="modal-header contentsBackground" style="text-align: center;">
                <h4>
                <input type="hidden" id="recordName" value="{if $RECORD_RUNNING_NAME} {$RECORD_RUNNING_NAME} {else} {$RECORD_MODEL->getName()} {/if}">
                    <a href="{$RECORD_MODEL->getDetailViewUrl()}" id="header_popup">
                    {if $RECORD_RUNNING_NAME}
                            {'Running For '}
                            {if $RECORD_RUNNING_NAME}
                                {$RECORD_RUNNING_NAME}
                            {else}
                                {$RECORD_RUNNING['name']}
                            {/if}
                    {else}
                        {$RECORD_MODEL->getName()}
                    {/if}
                    </a>
                </h4>
            </div>

            <form class="form-horizontal timeTrackerForm" name="TrackerForm" method="post" action="index.php">
                <input type="hidden" name="parentId" value="{$RECORD_ID}"/>
                <input type="hidden" id="dateFormat" value="{$USER_MODEL->get('date_format')}"/>
                <input type="hidden" id="timeFormat" value="{$USER_MODEL->get('hour_format')}"/>
                <input type="hidden" id="module" name="form_data[module]" value="{$RECORD_MODEL->getModuleName()}"/>

                <div class="quickCreateContent">
                    <div class="modal-body">
                        <table style="margin: 0 auto;">
                            {foreach from=$SETTING_FIELD key=FIELDNAME item=FIELDINFO}
                            {if $FIELDINFO['visible']}
                            <tr>
                                <td class="fieldValue medium" colspan="2">
                                    {assign var="FIELD_MODEL" value=$EVENT_MODEL->getField($FIELDNAME)}
                                    {if $FIELDNAME eq 'subject'}
                                        <div class="row-fluid">
                                            <span class="span10">
                                                <input id="{$FIELDNAME}" type="text" style="width: 210px;" class="inputElement " data-fieldtype="string" name="form_data[{$FIELDNAME}]" value="{if $FORM_DATA[$FIELDNAME] neq ''}
                                                            {$FORM_DATA[$FIELDNAME]}
                                                        {elseif $SETTING_FIELD[$FIELDNAME]['default'] neq ''}
                                                            {$SETTING_FIELD[$FIELDNAME]['default']}
                                                        {else}
                                                        {/if}"  placeholder="{$FIELD_MODEL->get('label')}"/>
                                            </span>
                                        </div>
                                    {elseif $FIELDNAME eq 'description'}
                                        <div class="row-fluid">
                                            <span class="span10">
                                                <textarea id="{$FIELDNAME}" name="form_data[{$FIELDNAME}]" placeholder="{$FIELD_MODEL->get('label')}"  class="inputElement" rows="10" style="width: 210px; margin: 0px; height: 73px;">
                                                {if $FORM_DATA[$FIELDNAME] neq ''}
                                                    {$FORM_DATA[$FIELDNAME]}
                                                {elseif trim(decode_html($SETTING_FIELD[$FIELDNAME]['default'])) neq ''}{$SETTING_FIELD[$FIELDNAME]['default']}
                                                {else}{/if}</textarea>
                                            </span>
                                        </div>
                                    {elseif $FIELDNAME eq 'activitytype'}
                                        {assign var=PICKLIST_VALUES value=$FIELD_MODEL->getPicklistValues()}
                                        <div class="row-fluid">
                                            <span class="span10">
                                                <select id="{$FIELDNAME}" type="picklist" data-default-value = "{decode_html($SETTING_FIELD[$FIELDNAME]['default'])}" class="cinputElement select2 row" name="form_data[{$FIELDNAME}]" style="width: 210px;">
                                                    <option value="{$FIELD_MODEL->get('label')}"></option>
                                                    {if $FORM_DATA[$FIELDNAME]}
                                                        {foreach item=PICKLIST_VALUE key=PICKLIST_NAME from=$PICKLIST_VALUES}
                                                            <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_NAME)}"
                                                                {if trim(decode_html($FORM_DATA[$FIELDNAME])) eq trim($PICKLIST_NAME)}
                                                                    selected
                                                                {/if}
                                                            >{$PICKLIST_VALUE}</option>
                                                        {/foreach}
                                                    {else}
                                                        {foreach item=PICKLIST_VALUE key=PICKLIST_NAME from=$PICKLIST_VALUES}
                                                        <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_NAME)}"
                                                            {if trim(decode_html($SETTING_FIELD[$FIELDNAME]['default'])) eq trim($PICKLIST_NAME)}
                                                                selected
                                                            {/if}
                                                        >{$PICKLIST_VALUE}</option>
                                                            {/foreach}
                                                    {/if}
                                                </select>
                                            </span>
                                        </div> 
                                    {else}
                                    {assign var=PICKLIST_VALUES value=$FIELD_MODEL->getPicklistValues()}
                                        <div class="row-fluid">
                                            <span class="span10">
                                                <select class="inputElement select2 row" type="picklist" name="form_data[{$FIELDNAME}]" style="width: 210px;">
                                                   
                                                    {foreach item=PICKLIST_VALUE key=PICKLIST_NAME from=$PICKLIST_VALUES}
                                                        <option value="{Vtiger_Util_Helper::toSafeHTML($PICKLIST_NAME)}"
                                                            {if $FORM_DATA[$FIELDNAME] eq trim($PICKLIST_NAME)}
                                                                            selected
                                                            {elseif trim(decode_html($SETTING_FIELD[$FIELDNAME]['default'])) eq trim($PICKLIST_NAME)}
                                                                    selected
                                                            {else}
                                                            {/if}
                                                        >{$PICKLIST_VALUE}</option>
                                                    {/foreach}
                                                </select>
                                            </span>
                                        </div>   
                                    {/if}
                                </td>
                            </tr>
                            {else}
                                <input type="hidden" name="form_data[{$FIELDNAME}]" value="{$SETTING_FIELD[$FIELDNAME]['default']}" />
                            {/if}
                            {/foreach}

                            <tr>
                                <td colspan="2"><input type="text" style="width: 210px;" id="startDateTime" class="inputElement dateTimeField" name="form_data[startdate]"
                                 value="{if $FORM_DATA['startdate']}{$FORM_DATA['startdate']}
                                 {elseif $RECORD_RUNNING['form_data']['startdate']}{$RECORD_RUNNING['form_data']['startdate']}{/if}" placeholder="{if $FORM_DATA['startdate']}{$FORM_DATA['startdate']}{/if}" /></td>
                            </tr>
                            <tr>
                                <td  colspan="2"><input type="text" style="width: 210px;" id="endDateTime" class="inputElement dateTimeField" name="form_data[enddate]" value="{if $FORM_DATA['enddate']}{$FORM_DATA['enddate']}
                                 {elseif $RECORD_RUNNING['form_data']['enddate']}{$RECORD_RUNNING['form_data']['enddate']}{/if}" placeholder="{if $FORM_DATA['enddate']}{$FORM_DATA['enddate']}{/if}" /></td>
                            </tr>
                            <tr>
                            </tr>

                            <tr>
                                <td class="fieldValue medium" colspan="2">
                                    <div style="text-align: center;" class="detailViewTitle">
                                        <input type="hidden" id="timeTrackerTotal" name="form_data[timeTrackerTotal]" value="{$FORM_DATA['timeTrackerTotal']}" />                                      
                                        <span class="recordLabel font-x-x-large pushDown timeTrackerTotal" style="color: #2787e0;" >00:00:00</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                            
                            <tr>
                                <td class="fieldValue medium" colspan="2">
                                    <div class="row-fluid" style="text-align: center">                                        
                                        <a href="javascript:void(0);" id="btnPause" style="margin: 0 10px;display: inline-block;">
                                            <img src="layouts/vlayout/modules/Settings/OS2TimeTracker/images/pause.png" width="35">
                                        </a>
                                        <a href="javascript:void(0);" id="btnCancel" style="margin: 0 10px;display: inline-block;">
                                            <img src="layouts/vlayout/modules/Settings/OS2TimeTracker/images/close_red.png" width="37" height="35">
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <div class="row-fluid" style="text-align: center">
                                        <input type="hidden" name="trackerStatus" id="trackerStatus" value="{$STATUS}"/>
                                        <button type="button" class="btn btn-success" style="padding: 4px; width: 210px;" id="controlButton" data-start-label="{'Start'}" data-complete-label="{'Complete'}" data-resume-label="{'Resume'}" data-status="{$STATUS}">
                                        {if $STATUS eq 'running'}
                                                {'Complete'}
                                        {elseif $STATUS eq 'pause'}
                                                {'Resume'}
                                        {else} 
                                            {if $RECORD_RUNNING}
                                                    {'START TIMER FOR '} {$RECORD_MODEL->getName()}
                                                {else}
                                                    {'START'}
                                            {/if}                                                                                       
                                        {/if}
                                        </button>
                                        <input type="hidden" id="record_name" name="record_name" value="{$RECORD_MODEL->getName()}"/> 
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>    
            </form>
            
            {************LIST TIMER ACTIVE************}
            {if $ACTIVE_TIMER}
                <div class="modal-header contentsBackground" style="text-align: center; border-bottom: none; "><h3 style="color: #004123;">{'Active Timers'}</h3></div>
                <table class="table table-bordered listViewEntriesTable" id="listActiveTimers">

                    {foreach from=$ACTIVE_TIMER  item=TIMER_DATA }
                    
                        <tr id="record_{$TIMER_DATA['recordid']}">
                            <td class="summaryViewEntries">
								<span class="alignCenter " style="color: #004123;">
									<a class="record_name" href="index.php?module={$TIMER_DATA['formdata']['module']}&record={$TIMER_DATA['recordid']}&view=Detail" style="display:inline-block;overflow: hidden;width: 145px;white-space: nowrap;text-overflow: ellipsis;-o-text-overflow: ellipsis;-ms-text-overflow: ellipsis;" title="{if $TIMER_DATA['name'] neq ''} {$TIMER_DATA['name']} {else} - {/if}">
										{if $TIMER_DATA['name'] neq ''} {$TIMER_DATA['name']} {else} - {/if}
									</a>
								</span>
                            </td>
                            <td class="summaryViewEntries " >
                            <span  {if $RECORD_ID eq $TIMER_DATA['recordid']} class="alignCenter timeValue timeTrackerTotal" {else} class="alignCenter" {/if} style="color: #2787e0;">
                                {if $TIMER_DATA['timeTrackerTotal'] neq ''} {$TIMER_DATA['timeTrackerTotal']} {else} - {/if}
                            </span>
                            </td>
                            <td class="summaryViewEntries ">
								<span class="alignMiddle">
									<a class="play_icon" href="index.php?module={$TIMER_DATA['formdata']['module']}&record={$TIMER_DATA['recordid']}&view=Detail&go_back=1">
										<img {if $TIMER_DATA['status'] eq 'pause'} src="layouts/v7/modules/Settings/OS2TimeTracker/images/pause.png"  {elseif $TIMER_DATA['status'] eq 'running'} src="layouts/v7/modules/Settings/OS2TimeTracker/images/play.png" {else} {/if} alt="Go back record" style="width:20px;height:20px;"/>
									</a>
								</span>
                            </td>
                        </tr>
                    {/foreach}
                    <tr class="hide row_base">
                        <td class="summaryViewEntries">
							<span class="alignCenter " style="color: #004123;">
								<a class="record_name" href="javascript:voice(0)" style="display:inline-block;overflow: hidden;width: 145px;white-space: nowrap;text-overflow: ellipsis;-o-text-overflow: ellipsis;-ms-text-overflow: ellipsis;" title=""</a>
							</span>
                        </td>
                        <td class="summaryViewEntries" >
                            <span class="alignCenter timeTrackerTotal timeValue" style="color: #2787e0;"></span>
                        </td>
                        <td class="summaryViewEntries ">
							<span class="alignMiddle">
								<a class="play_icon" href="javascript:voice(0)">
									<img src="layouts/v7/modules/Settings/OS2TimeTracker/images/pause.png" alt="Go back record" style="width:20px;height:20px;"/>
								</a>
							</span>
                        </td>
                    </tr>
                </table>
            {/if}

        </div>
    </div>
{/strip}
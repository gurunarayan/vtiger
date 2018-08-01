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
{strip}
<div class="container-fluid">
	<div class="contents">
		<form id="ConfigUserSettingsEditorForm" class="form-horizontal" data-detail-url="{$MODEL->getDetailViewUrl()}" method="POST">
			<div class="widget_header row-fluid">
				<div class="span8"><h3>{vtranslate('LBL_USER_CONFIG_EDITOR', $QUALIFIED_MODULE)}</h3>&nbsp;{vtranslate('LBL_CONFIG_DESCRIPTION', $QUALIFIED_MODULE)}</div>
				<div class="span4 btn-toolbar">
					<div class="pull-right">
						<button class="btn btn-success saveButton" type="submit" title="{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}"><strong>{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</strong></button>
						<a type="reset" class="cancelLink" title="{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
					</div>
				</div>
			</div>
			<hr>
			{assign var=WIDTHTYPE value=$CURRENT_USER_MODEL->get('rowheight')}
			{assign var=FIELD_VALIDATION  value=['UC_EMAIL_ID_ONE' => ['name'=>'Email'],												 
												'UC_EMAIL_ID_TWO' => ['name' => 'Email'],
												'max_login_attempts' => ['name' => 'NumberRange5']]}
			<table class="table table-bordered table-condensed themeTableColor">
				<thead>
					<tr class="blockHeader"><th colspan="2" class="{$WIDTHTYPE}">Common Configuration for Users</th></tr>
				</thead>
				<tbody>
						<tr>
							<td width="30%" class="{$WIDTHTYPE}"><label class="muted pull-right marginRight10px">{$USERFIELDS['failed_logins_criteria']['label']}</label></td>
							<td style="border-left: none;" class="row-fluid {$WIDTHTYPE}">
									<span class="span3">
									<select class="select2 row-fluid" name="failed_logins_criteria">
										<option value="0" {if "0" == $USERSETTINGS['failed_logins_criteria']} selected {/if}>No check for failed login</option>
										<option value="1" {if "1" == $USERSETTINGS['failed_logins_criteria']} selected {/if}>IP Check</option>
										<option value="2" {if "2" == $USERSETTINGS['failed_logins_criteria']} selected {/if}>Calendar Check</option>
										<option value="3" {if "3" == $USERSETTINGS['failed_logins_criteria']} selected {/if}>Calendar and IP Check</option>
										<option value="4" {if "4" == $USERSETTINGS['failed_logins_criteria']} selected {/if}>Password Check</option>
										<option value="5" {if "5" == $USERSETTINGS['failed_logins_criteria']} selected {/if}>PW and IP Check</option>
										<option value="6" {if "6" == $USERSETTINGS['failed_logins_criteria']} selected {/if}>PW and Calendar Check</option>
										<option value="7" {if "7" == $USERSETTINGS['failed_logins_criteria']} selected {/if}>PW, Calendar and IP Check</option>
									</select>
									</span>
							</td>
						</tr>
						
						<tr>
							<td width="30%" class="{$WIDTHTYPE}"><label class="muted pull-right marginRight10px">{$USERFIELDS['max_login_attempts']['label']}</label></td>
							<td style="border-left: none;" class="row-fluid {$WIDTHTYPE}">
									<input type="text" name="max_login_attempts" data-validation-engine="validate[required, funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" {if $FIELD_VALIDATION['max_login_attempts']} data-validator={Zend_Json::encode([$FIELD_VALIDATION['max_login_attempts']])} {/if} value="{$USERSETTINGS['max_login_attempts']}"  />
							</td>
						</tr>
						
						<tr>
							<td width="30%" class="{$WIDTHTYPE}"><label class="muted pull-right marginRight10px">{$USERFIELDS['UC_NAME_ONE']['label']}</label></td>
							<td style="border-left: none;" class="row-fluid {$WIDTHTYPE}">
									
										<input type="text" name="UC_NAME_ONE" data-validation-engine="validate[required, funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" {if $FIELD_VALIDATION['UC_NAME_ONE']} data-validator={Zend_Json::encode([$FIELD_VALIDATION['UC_NAME_ONE']])} {/if} value="{$USERSETTINGS['uc_name_one']}"  />
							</td>
						</tr>
						
						<tr>
							<td width="30%" class="{$WIDTHTYPE}"><label class="muted pull-right marginRight10px">{$USERFIELDS['UC_EMAIL_ID_ONE']['label']}</label></td>
							<td style="border-left: none;" class="row-fluid {$WIDTHTYPE}">
									
										<input type="text" name="UC_EMAIL_ID_ONE" data-validation-engine="validate[required, funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" {if $FIELD_VALIDATION['UC_EMAIL_ID_ONE']} data-validator={Zend_Json::encode([$FIELD_VALIDATION['UC_EMAIL_ID_ONE']])} {/if} value="{$USERSETTINGS['uc_email_id_one']}"  />
							</td>
						</tr>
						
						<tr>
							<td width="30%" class="{$WIDTHTYPE}"><label class="muted pull-right marginRight10px">{$USERFIELDS['UC_NAME_TWO']['label']}</label></td>
							<td style="border-left: none;" class="row-fluid {$WIDTHTYPE}">
									
										<input type="text" name="UC_NAME_TWO" data-validation-engine="validate[required, funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" {if $FIELD_VALIDATION['UC_NAME_TWO']} data-validator={Zend_Json::encode([$FIELD_VALIDATION['UC_NAME_TWO']])} {/if} value="{$USERSETTINGS['uc_name_two']}"  />
							</td>
						</tr>
						
						<tr>
							<td width="30%" class="{$WIDTHTYPE}"><label class="muted pull-right marginRight10px">{$USERFIELDS['UC_EMAIL_ID_TWO']['label']}</label></td>
							<td style="border-left: none;" class="row-fluid {$WIDTHTYPE}">
									
										<input type="text" name="UC_EMAIL_ID_TWO" data-validation-engine="validate[required, funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" {if $FIELD_VALIDATION['UC_EMAIL_ID_TWO']} data-validator={Zend_Json::encode([$FIELD_VALIDATION['UC_EMAIL_ID_TWO']])} {/if} value="{$USERSETTINGS['uc_email_id_two']}"  />
							</td>
						</tr>
						
						<tr>
							<td width="30%" class="{$WIDTHTYPE}"><label class="muted pull-right marginRight10px">{$USERFIELDS['Working_Hours_start']['label']}</label></td>
							<td style="border-left: none;" class="row-fluid {$WIDTHTYPE}">
									
									<div class="input-append time">
										<input type="text" name="Working_Hours_start" data-format="24" data-toregister="time" class="timepicker-default input-small" data-validation-engine="validate[required, funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" {if $FIELD_VALIDATION[$FIELD_NAME]} data-validator={Zend_Json::encode([$FIELD_VALIDATION['Working_Hours_start']])} {/if} value="{$USERSETTINGS['working_hours_start']}"/>
										<span class="add-on cursorPointer">
											<i class="icon-time"></i>
										</span>
									</div>
							</td>
						</tr>
						
						<tr>
							<td width="30%" class="{$WIDTHTYPE}"><label class="muted pull-right marginRight10px">{$USERFIELDS['Working_Hours_end']['label']}</label></td>
							<td style="border-left: none;" class="row-fluid {$WIDTHTYPE}">
									
									<div class="input-append time">
										<input type="text" name="Working_Hours_end" data-format="24" data-toregister="time" class="timepicker-default input-small" data-validation-engine="validate[required, funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" {if $FIELD_VALIDATION[$FIELD_NAME]} data-validator={Zend_Json::encode([$FIELD_VALIDATION['Working_Hours_end']])} {/if} value="{$USERSETTINGS['working_hours_end']}"/>
										<span class="add-on cursorPointer">
											<i class="icon-time"></i>
										</span>
									</div>
							</td>
						</tr>
									
						<tr>
							<input type="hidden" name="totaldays" id="" value="6">
							<td width="30%" class="{$WIDTHTYPE}"><label class="muted pull-right marginRight10px">{$USERFIELDS['working_week_days']['label']}</label></td>
							<td style="border-left: none;" class="row-fluid {$WIDTHTYPE}">
{if !empty($USERSETTINGS['weeks']) }
					<input type="checkbox" name="week_0" value="Sunday" {if "Sunday"|in_array:$USERSETTINGS['weeks']}checked{/if} > &nbsp; Sunday&nbsp;
					<input type="checkbox" name="week_1" value="Monday" {if "Monday"|in_array:$USERSETTINGS['weeks']}checked{/if}> &nbsp; Monday&nbsp;
					<input type="checkbox" name="week_2" value="Tuesday" {if "Tuesday"|in_array:$USERSETTINGS['weeks']}checked{/if}> &nbsp; Tuesday&nbsp;
					<input type="checkbox" name="week_3" value="Wednesday" {if "Wednesday"|in_array:$USERSETTINGS['weeks']}checked{/if}> &nbsp; Wednesday&nbsp;
					<input type="checkbox" name="week_4" value="Thursday" {if "Thursday"|in_array:$USERSETTINGS['weeks']}checked{/if}> &nbsp; Thursday&nbsp;
					<input type="checkbox" name="week_5" value="Friday" {if "Friday"|in_array:$USERSETTINGS['weeks']}checked{/if}> &nbsp; Friday&nbsp;
					<input type="checkbox" name="week_6" value="Saturday" {if "Saturday"|in_array:$USERSETTINGS['weeks']}checked{/if}> &nbsp; Saturday&nbsp;
{else}
					<input type="checkbox" name="week_0" value="Sunday"> &nbsp; Sunday&nbsp;
					<input type="checkbox" name="week_1" value="Monday"> &nbsp; Monday&nbsp;
					<input type="checkbox" name="week_2" value="Tuesday"> &nbsp; Tuesday&nbsp;
					<input type="checkbox" name="week_3" value="Wednesday"> &nbsp; Wednesday&nbsp;
					<input type="checkbox" name="week_4" value="Thursday"> &nbsp; Thursday&nbsp;
					<input type="checkbox" name="week_5" value="Friday"> &nbsp; Friday&nbsp;
					<input type="checkbox" name="week_6" value="Saturday"> &nbsp; Saturday&nbsp;
{/if}
							</td>
						</tr>
					
				</tbody>
				<table class="table table-bordered table-condensed themeTableColor">
				<thead>
					<tr class="blockHeader"><th colspan="2" class="{$WIDTHTYPE}">Holidays</th></tr>
					<tr class="blockHeader"><th class="{$WIDTHTYPE}">Name</th><th class="{$WIDTHTYPE}">Date</th></tr>
				</thead>
				<tbody id="holiday_name_date">
				
				{if !empty($USERSETTINGS['holiday_lbl_val'])}
				{$keys = array_keys($USERSETTINGS['holiday_lbl_val'])}
				{for $i=0; $i<count($USERSETTINGS['holiday_lbl_val']); $i++}
					<tr id="tr_{$i}">
						<td width="30%" class="{$WIDTHTYPE}">								
							<input type="text" name="holiday_lbl_{$i}"   value="{$keys[$i]}" placeholder="Holiday Name"/>
						</td>
						<td style="border-left: none;" class="row-fluid {$WIDTHTYPE}">
						<div class="input-append row-fluid">
							<div class="span12 row-fluid date">						
							<input type="text" id="holiday_val_0" data-date-format="yyyy-mm-dd" name="holiday_val_{$i}" class="dateField" value="{$USERSETTINGS['holiday_lbl_val'][$keys[$i]]}" placeholder="YYYY-MM-DD"/>
							<span class="add-on"><i class="icon-calendar"></i></span>
								<div class="span6 pull-left holidaybuttons">
									{if $i == 0}
									<button class="btn btn-success addHolidayRow" type="button" title="Add"><strong>Add</strong></button>
									{/if}
									{if $i > 0}
									<a type="" class="Link removeHolidayRow" title="Remove">Remove</a>
									{/if}
								</div>
							</div>
						</div>
						</td>
					</tr>
					{/for}
					{else}
						<tr id="tr_0">
							<td width="30%" class="{$WIDTHTYPE}">								
								<input type="text" name="holiday_lbl_0"   value="{$keys[$i]}" placeholder="Holiday Name"/>
							</td>
							<td style="border-left: none;" class="row-fluid {$WIDTHTYPE}">
							<div class="input-append row-fluid">
								<div class="span12 row-fluid date">						
								<input type="text" id="holiday_val_0" data-date-format="yyyy-mm-dd" name="holiday_val_0" class="dateField" value="" placeholder="YYYY-MM-DD"/>
								<span class="add-on"><i class="icon-calendar"></i></span>
									<div class="span6 pull-left holidaybuttons">
										<button class="btn btn-success addHolidayRow" type="button" title="Add"><strong>Add</strong></button>
									</div>
								</div>
							</div>
							</td>
						</tr>
					{/if}
					
				</tbody>
			</table>
			<input type="hidden" name="totalcount" id="totalcount" value="{count($USERSETTINGS['holiday_lbl_val'])}">
			<input type="hidden" name="itemcount" id="itemcount" value="{count($USERSETTINGS['holiday_lbl_val'])}">
		</form>
	</div>
</div>
{/strip}
<script>
$(document).ready(function(){
	var tc = $('#idHdnTermsConditions').val();
	$('#idTextareaTC').val(tc);	
		var itemcount = $("#itemcount").val();
	$(".addHolidayRow").click(function(){
		itemcount++;
		var html = '<tr id="tr_'+itemcount+'"><td width="30%" class="{$WIDTHTYPE}"><input type="text" name="holiday_lbl_'+itemcount+'"   value="" placeholder="Holiday Name"/></td><td style="border-left: none;" class="row-fluid {$WIDTHTYPE}"><div class="input-append row-fluid"><div class="span12 row-fluid date"><input type="text" id="holiday_val_'+itemcount+'" data-date-format="yyyy-mm-dd" name="holiday_val_'+itemcount+'" class="dateField" value="" placeholder="YYYY-MM-DD"/><span class="add-on"><i class="icon-calendar"></i></span><div class="span6 pull-left holidaybuttons"><a type="" class="Link removeHolidayRow" title="Remove">Remove</a></div></div></div></td></tr>';
		$("#holiday_name_date").append(html);	
		$('#itemcount').val(itemcount);
	});
	
	$(".removeHolidayRow").click(function(){
		if(itemcount > 0)
		{
			$('#tr_'+itemcount).remove();
			itemcount--;
			$('#itemcount').val(itemcount);
		}
	});
});
</script>

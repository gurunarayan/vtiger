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
<div class="container-fluid" id="ConfigUserSettingsEditorDetails">
	{assign var=WIDTHTYPE value=$CURRENT_USER_MODEL->get('rowheight')}
	<div class="widget_header row-fluid">
		<div class="span8"><h3>User Settings Editor</h3></div>
		<div class="span4">
			<div class="pull-right">
				<button class="btn editButtonUserSettings" data-url='{$MODEL->getEditViewUrl()}' type="button" title="{vtranslate('LBL_EDIT', $QUALIFIED_MODULE)}"><strong>{vtranslate('LBL_EDIT', $QUALIFIED_MODULE)}</strong></button>
			</div>
		</div>
	</div>
	<hr>
	<div class="contents">
		<table class="table table-bordered table-condensed themeTableColor">
			<thead>
				<tr class="blockHeader">
					<th colspan="2" class="{$WIDTHTYPE}">
						<span class="alignMiddle">Common Configuration for Users</span>
					</th>
				</tr>
			</thead>
			<tbody>	
				{assign var=FIELD_DATA value=$MODEL->getViewableData()}
				<tr>
					<td width="30%" class="medium"><label class="muted marginRight10px pull-right">Failed Logins Criteria</label></td>
					<td style="border-left: none;" class="medium"><span>
						{if $USERSETTINGS['failed_logins_criteria'] == 0}
							No check for failed login
						{/if}
						{if $USERSETTINGS['failed_logins_criteria'] == 1}
							IP Check
						{/if}	
						{if $USERSETTINGS['failed_logins_criteria'] == 2}
							Calendar Check
						{/if}	
						{if $USERSETTINGS['failed_logins_criteria'] == 3}
							Calendar and IP Check
						{/if}
						{if $USERSETTINGS['failed_logins_criteria'] == 4}
							Password Check
						{/if}
						{if $USERSETTINGS['failed_logins_criteria'] == 5}
							PW and IP Check
						{/if}
						{if $USERSETTINGS['failed_logins_criteria'] == 6}
							PW and Calendar Check
						{/if}
						{if $USERSETTINGS['failed_logins_criteria'] == 7}
							PW, Calendar and IP Check
						{/if}
					</span></td>
				</tr>
				<tr>
					<td width="30%" class="medium"><label class="muted marginRight10px pull-right">Max Login Attempts</label></td>
					<td style="border-left: none;" class="medium"><span>{$USERSETTINGS['max_login_attempts']}</span></td>
				</tr>
				<tr>
					<td width="30%" class="medium"><label class="muted marginRight10px pull-right">Security Observer Name 1	</label></td>
					<td style="border-left: none;" class="medium"><span>{$USERSETTINGS['uc_name_one']}</span></td>
				</tr>
				<tr>
					<td width="30%" class="medium"><label class="muted marginRight10px pull-right">Security Observer Email 1	</label></td>
					<td style="border-left: none;" class="medium"><span>{$USERSETTINGS['uc_email_id_one']}</span></td>
				</tr>
				<tr>
					<td width="30%" class="medium"><label class="muted marginRight10px pull-right">Security Observer Name 2	</label></td>
					<td style="border-left: none;" class="medium"><span>{$USERSETTINGS['uc_name_two']}</span></td>
				</tr>
				<tr>
					<td width="30%" class="medium"><label class="muted marginRight10px pull-right">Security Observer Email 2	</label></td>
					<td style="border-left: none;" class="medium"><span>{$USERSETTINGS['uc_email_id_two']}</span></td>
				</tr>
				<tr>
					<td width="30%" class="medium"><label class="muted marginRight10px pull-right">Working Hours Start</label></td>
					<td style="border-left: none;" class="medium"><span>{$USERSETTINGS['working_hours_start']}</span></td>
				</tr>
				<tr>
					<td width="30%" class="medium"><label class="muted marginRight10px pull-right">Working Hours Start</label></td>
					<td style="border-left: none;" class="medium"><span>{$USERSETTINGS['working_hours_end']}</span></td>
				</tr>
				<tr>
					<td width="30%" class="medium"><label class="muted marginRight10px pull-right">Working Week Days</label></td>
{if !empty($USERSETTINGS['weeks'])}
					<td style="border-left: none;" class="medium"><span>{implode(",", $USERSETTINGS['weeks'])}</span></td>
{else}
<td style="border-left: none;" class="medium"><span></span></td>
{/if}
				</tr>
			</tbody>
			
		</table>
		<table class="table table-bordered table-condensed themeTableColor">
			<thead>
				<tr class="blockHeader"><th colspan="2" class="{$WIDTHTYPE}">Holidays</th></tr>
					<tr class="blockHeader"><th class="{$WIDTHTYPE}" >Name</th><th class="{$WIDTHTYPE}">Date</th></tr>
			</thead>
		<tbody>				
				
				{if !empty($USERSETTINGS['holiday_lbl_val'])}
				{$keys = array_keys($USERSETTINGS['holiday_lbl_val'])}
				{for $i=0; $i<count($USERSETTINGS['holiday_lbl_val']); $i++}
					<tr>
						<td width="30%" class="{$WIDTHTYPE}"><label class="muted marginRight10px pull-right">{$keys[$i]} </label></td>
						<td width="30%" class="{$WIDTHTYPE}"><span>{{$USERSETTINGS['holiday_lbl_val'][$keys[$i]]}} </span></td>						 
					</tr>
				{/for}
				{else}
					<tr>
						<td width="30%" class="{$WIDTHTYPE}"><label class="muted marginRight10px pull-right">{$FIELD_DATA['lbl'][$FIELD_NAME]} </label></td>
						<td width="30%" class="{$WIDTHTYPE}"><span>{$FIELD_DATA['val'][$FIELD_NAME]} </span></td>						 
					</tr>
				{/if}
			</tbody>
		</table>
	</div>
</div>
{/strip}

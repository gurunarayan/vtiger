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
<div class="widget_header row-fluid">
   <div class="span6">
      <h3>Login Themes</h3>
   </div>
</div>
<hr/>
<div class="row-fluid">
   <span class="span8 btn-toolbar"><button class="btn addButton">
	<a href='index.php?module=LoginPage&parent=Settings&view=NewTheme'>
		<i class="icon-plus"></i>&nbsp;<strong>Add Theme</strong>
	</a>
   </button>
   </span>
</div>
    <div class="listViewEntriesDiv" style='overflow-x:auto;'>
        <span class="listViewLoadingImageBlock hide modal" id="loadingListViewModal">
            <img class="listViewLoadingImage" src="{vimage_path('loading.gif')}" alt="no-image" title="{vtranslate('LBL_LOADING', $MODULE)}"/>
            <p class="listViewLoadingMsg">{vtranslate('LBL_LOADING_LISTVIEW_CONTENTS', $MODULE)}........</p>
        </span>
        <table class="table table-bordered listViewEntriesTable" id="Themes" width="60%">
            <thead>
                <tr class="listViewHeaders">
                    <th>
                        S.No  
                    </th>
					<th>Theme Name</th>
					<th>Status</th>
					<th>Preview</th>
					<th>Action</th>
            </tr>
        </thead>
        <tbody>
				
                {foreach item=i key=k from=$THEMELIST}
				<tr>
                    <td class="listViewEntryValue {$WIDTHTYPE}"  width="{$WIDTH}%" nowrap>
						{$i['id']}
                    </td>
					<td nowrap class="{$WIDTHTYPE}">
						{$i['name']}   
                    </td>
					<td nowrap class="{$WIDTHTYPE}">
						<input type='radio' class="radio" name='themestatus' data-display="{$i['name']}" value=""							{if $i['status'] eq 1}checked{/if}						/>
                    </td>
					<td nowrap class="{$WIDTHTYPE}">
						<a target='_blank' class="btn btn-success" href="{$i['previewurl']}">
							<strong>Preview</strong>
						</a>
                    </td>
					<td nowrap class="{$WIDTHTYPE}">
						<a class="deleteRecordButton" data-name="{$i['name']}">
							<i title="Delete" class="icon-trash alignMiddle"></i>
						</a>						
                    </td>
				</tr>
				{/foreach}
    </tbody>
</table>
</div>
{/strip}
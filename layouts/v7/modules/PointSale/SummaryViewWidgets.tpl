{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
{strip}
{foreach item=DETAIL_VIEW_WIDGET from=$DETAILVIEW_LINKS['DETAILVIEWWIDGET']}
	{if ($DETAIL_VIEW_WIDGET->getLabel() eq 'Documents') }
		{assign var=DOCUMENT_WIDGET_MODEL value=$DETAIL_VIEW_WIDGET}
	{elseif ($DETAIL_VIEW_WIDGET->getLabel() eq 'ModComments')}
		{assign var=COMMENTS_WIDGET_MODEL value=$DETAIL_VIEW_WIDGET}
	{elseif ($DETAIL_VIEW_WIDGET->getLabel() eq 'LBL_UPDATES')}
		{assign var=UPDATES_WIDGET_MODEL value=$DETAIL_VIEW_WIDGET}
	{/if}
{/foreach}

<div class="left-block col-lg-4">
	{* Module Summary View*}
		<div class="summaryView">
			<div class="summaryViewHeader">
				<h4 class="display-inline-block">{vtranslate('LBL_KEY_FIELDS', $MODULE_NAME)}</h4>
			</div>
			<div class="summaryViewFields">
				{$MODULE_SUMMARY}
			</div>
		</div>
	{* Module Summary View Ends Here*}

	{* Summary View Documents Widget*}
	{if $DOCUMENT_WIDGET_MODEL}
		<div class="summaryWidgetContainer">
			<div class="widgetContainer_documents" data-url="{$DOCUMENT_WIDGET_MODEL->getUrl()}" data-name="{$DOCUMENT_WIDGET_MODEL->getLabel()}">
				<div class="widget_header clearfix">
					<input type="hidden" name="relatedModule" value="{$DOCUMENT_WIDGET_MODEL->get('linkName')}" />
					<span class="toggleButton pull-left"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;</span>
					<h4 class="display-inline-block pull-left">{vtranslate($DOCUMENT_WIDGET_MODEL->getLabel(),$MODULE_NAME)}</h4>

					{if $DOCUMENT_WIDGET_MODEL->get('action')}
						{assign var=PARENT_ID value=$RECORD->getId()}
						<div class="pull-right">
							<div class="dropdown">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									<span class="fa fa-plus" title="{vtranslate('LBL_NEW_DOCUMENT', $MODULE_NAME)}"></span>&nbsp;{vtranslate('LBL_NEW_DOCUMENT', 'Documents')}&nbsp; <span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li class="dropdown-header"><i class="fa fa-upload"></i> {vtranslate('LBL_FILE_UPLOAD', 'Documents')}</li>
									<li id="VtigerAction">
										<a href="javascript:Documents_Index_Js.uploadTo('Vtiger',{$PARENT_ID},'{$MODULE_NAME}')">
											<img style="  margin-top: -3px;margin-right: 4%;" title="Vtiger" alt="Vtiger" src="layouts/v7/skins//images/Vtiger.png">
											{vtranslate('LBL_TO_SERVICE', 'Documents', {vtranslate('LBL_VTIGER', 'Documents')})}
										</a>
									</li>
									<li class="dropdown-header"><i class="fa fa-link"></i> {vtranslate('LBL_LINK_EXTERNAL_DOCUMENT', 'Documents')}</li>
									<li id="shareDocument"><a href="javascript:Documents_Index_Js.createDocument('E',{$PARENT_ID},'{$MODULE_NAME}')">&nbsp;<i class="fa fa-external-link"></i>&nbsp;&nbsp; {vtranslate('LBL_FROM_SERVICE', 'Documents', {vtranslate('LBL_FILE_URL', 'Documents')})}</a></li>
									<li role="separator" class="divider"></li>
									<li id="createDocument"><a href="javascript:Documents_Index_Js.createDocument('W',{$PARENT_ID},'{$MODULE_NAME}')"><i class="fa fa-file-text"></i> {vtranslate('LBL_CREATE_NEW', 'Documents', {vtranslate('SINGLE_Documents', 'Documents')})}</a></li>
								</ul>
							</div>
						</div>
					{/if}
				</div>
				<div class="widget_contents">

				</div>
			</div>
		</div>
	{/if}
	{* Summary View Documents Widget Ends Here*}
</div>

<div class="middle-block col-lg-8">
	{* Summary View Related Activities Widget*}
		<div id="relatedActivities">
			{$RELATED_ACTIVITIES}
			<div class="summaryWidgetContainer">
				<div >
					<b>{'BILL OF SALE'}</b>
				</div>
				<hr>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Product</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Total</th>
							{if $TAX eq 1}<th>TotalWithTax</th>{/if}
						</tr>
					</thead>
					<tbody>
					{foreach from=$POS_DETAIL key=k item=data}
						<tr>
							<td>{$data['productname']}</td>
							<td>{$data['selected_qty']|string_format:"%.2f"}</td>
							<td>{$data['price']|string_format:"%.2f"}</td>
							<td>{$data['selected_qty']*$data['price']|string_format:"%.2f"}</td>
							{if $TAX eq 1}<td>{(($data['selected_qty']*$data['price']) + (($data['selected_qty']*$data['price']) * ($data['taxpercent']/100)))|string_format:"%.2f"}</td>{/if}
						</tr>
					{/foreach}
					</tbody>
				</table>
				<hr>
				<table class="table table-striped table-bordered">
					<tbody>
						<tr>
							<td>Total Items</td>
							<td colspan=3>{$total_qty|string_format:"%.2f"}</td>
						</tr>
						{if $TAX eq 1}
						<tr>
							<td>Total</td>
							<td colspan=3>{$pos_amount['total_amount']|string_format:"%.2f"}</td>
						</tr>
						{else}
						<tr>
							<td>Total Amount</td>
							<td colspan=3>{$totalwithouttax|string_format:"%.2f"}</td>
						</tr>
						<tr>
							<td>Total With Tax</td>
							<td colspan=3>{$pos_amount['total_amount']|string_format:"%.2f"}</td>
						</tr>
						{/if}
						<tr>
							<td>Cash Tendered</td>
							<td colspan=3>{$pos_amount['paid_amount']|string_format:"%.2f"}</td>
						</tr>
						<tr>
							<td>Change Return</td>
							<td colspan=3>{$pos_amount['return_amount']|string_format:"%.2f"}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	{* Summary View Related Activities Widget Ends Here*}

	{* Summary View Comments Widget*}
	{if $COMMENTS_WIDGET_MODEL}
		<div class="summaryWidgetContainer">
			<div class="widgetContainer_comments" data-url="{$COMMENTS_WIDGET_MODEL->getUrl()}" data-name="{$COMMENTS_WIDGET_MODEL->getLabel()}">
				<div class="widget_header">
					<input type="hidden" name="relatedModule" value="{$COMMENTS_WIDGET_MODEL->get('linkName')}" />
					<h4 class="display-inline-block">{vtranslate($COMMENTS_WIDGET_MODEL->getLabel(),$MODULE_NAME)}</h4>
				</div>
				<div class="widget_contents">
				</div>
			</div>
		</div>
	{/if}
	{* Summary View Comments Widget Ends Here*}
</div>
{/strip}
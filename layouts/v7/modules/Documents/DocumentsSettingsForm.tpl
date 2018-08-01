<div>
	<div class="contents tabbable">
		<ul class="nav nav-tabs layoutTabs massEditTabs"> 
			<li class="jtab active"><a data-toggle="tab" href="#generalOptions"><strong>General</strong></a></li>
			<li class="jtab"><a data-toggle="tab" href="#displayOptions"><i class="icon-th-large"></i>&nbsp;<strong>Module Settings</strong></a></li>
			<li class="jtab"><a data-toggle="tab" href="#aboutExtension"><strong>About</strong></a></li>
		</ul>
		<div class="tab-content layoutContent padding20 themeTableColor overflowVisible">
			<div class="tab-pane" id="displayOptions">
				<div style="border-radius: 4px 4px 0px 0px;background: white;" class="editFieldsTable marginBottom10px border1px">
					<div class="row-fluid layoutBlockHeader">
						<div class="blockLabel span5 padding10 marginLeftZero">
							<img src="layouts/vlayout/skins/images/drag.png" class="alignMiddle">&nbsp;&nbsp;<strong><span id="am_viewname" class="large">All chosen entity modules</span></strong>
						</div>
						<div style="float:right !important;" class="span6 marginLeftZero">
							<div style="margin: 4px;" class="pull-right btn-toolbar blockActions">
							<div class="btn-group">
								<span id="Documents_listView_basicAction_LBL_ADD_FOLDER" class="btn addButton" onclick="Documents_List_Js.triggerAddFolder('index.php?module=Documents&view=AddFolder');">
									<i class="icon-plus"></i>&nbsp;
									<strong>Add Folder</strong>
								</span>
							</div>
								<div class="btn-group">
									<button type="button" class="btn chooseEntityModule">
									<strong>Choose Entity</strong>
									</button>
								</div>
								<div class="btn-group">
									<button data-toggle="dropdown" class="btn dropdown-toggle">
										<strong>Actions</strong>&nbsp;&nbsp;<i class="caret">
										</i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li class="allChosenEntities">
											<a href="javascript:void(0)">All Entities chosen</a>
										</li>
										<li class="entitiesEnabled">
											<a href="javascript:void(0)">Entities Enabled</a>
										</li>
										<li class="entitiesNotEnabled">
											<a href="javascript:void(0)">Entities Not Enabled</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<table class="table table-bordered table-condensed listViewEntriesTable" id="associatedModulesList">
					<thead>
						<tr class="listViewHeaders">
							<th width="25%" nowrap="" class="medium">
								<a data-columnname="modulename" data-nextsortorderval="ASC" class="listViewHeaderValues cursorPointer">Module</a>
							</th>
							<th width="15%" nowrap="" class="medium">
								<a>Detailview Widget</a>
							</th>
							{*<th width="15%" nowrap="" class="medium">
								<a>Multiple Records</a>
							</th>*}
							<th width="25%" nowrap="" class="medium">
								<a>Default Folder Name</a>
							</th>
							<th width="25%" nowrap="" class="medium">
								<a>Folder Description</a>
							</th>
							<th width="10%" nowrap="" class="medium">
							</th>
						</tr>
					</thead>
				<tbody>
				{foreach item=ASSOCIATED_MODULE from=$ASSOCIATED_MODULES name=ass_mod}
				<tr data-id="{$smarty.foreach.ass_mod.iteration}" data-modulename="{$ASSOCIATED_MODULE.modulename}" data-modulelabel="{$ASSOCIATED_MODULE.modulelabel}" data-modulestatus="{$ASSOCIATED_MODULE.enabled}" class="listViewEntries {if $ASSOCIATED_MODULE.enabled eq '0'}notenabled{else}enabled{/if}">
					<td width="25%" nowrap="" class="listViewEntryValue medium">&nbsp;{$ASSOCIATED_MODULE.modulelabel}{if $ASSOCIATED_MODULE.enabled eq "1"}&nbsp;<i class="icon-ok"></i>{/if}</td>
					<td width="15%" nowrap="" class="listViewEntryValue medium">&nbsp;{if $ASSOCIATED_MODULE.enabled eq "0"}Disabled{else}<strong>Enabled</strong>{/if}</td>
					{if $ASSOCIATED_MODULE.enabled eq "1"}
					{*<td width="15%" nowrap="" class="listViewEntryValue medium">&nbsp;{if $ASSOCIATED_MODULE.multiplerecords eq "0"}<i class="icon-remove"></i>{else}<i class="icon-ok"></i>{/if}</td>*}
					<td width="25%" nowrap="" class="listViewEntryValue medium">&nbsp;{$ASSOCIATED_MODULE.foldername}</td>
					<td width="25%" nowrap="" class="listViewEntryValue medium">&nbsp;{$ASSOCIATED_MODULE.description}</td>
					{else}
					<td colspan=2>&nbsp;</td>
					{/if}

					<td width="10%" nowrap="" class="medium">
						<div class="pull-right actions">
							<span class="actionImages">
								<a>
									<i title="Toggle Association" class="icon-pencil alignMiddle">
									</i>
								</a>&nbsp;&nbsp;
								<a>
									<i title="Delete" class="icon-trash alignMiddle">
									</i>
								</a>
							</span>
						</div>
					</td>
				</tr>
				{/foreach}
				</tbody>
				</table>
			</div>
			<div class="tab-pane active" id="generalOptions">
				<div style="overflow-y:auto;height:400px;">
					<form method="post" data-name-fields="[&quot;accountname&quot;]" id="detailView" name=
					"detailView">
					<div class="contents">
					  <table class="table table-bordered equalSplit detailview-table">
						<thead>
						  <tr>
							<th colspan="4" class="blockHeader"><img data-id="9" data-mode="hide" src=
							"layouts/vlayout/skins/alphagrey/images/arrowRight.png" class=
							"cursorPointer alignMiddle blockToggle hide" /> <img data-id="9" data-mode=
							"show" src="layouts/vlayout/skins/alphagrey/images/arrowDown.png" class=
							"cursorPointer alignMiddle blockToggle" />&nbsp;&nbsp;Scanner Options</th>
						  </tr>
						</thead>
						<tbody>
						  <tr>
							<td class=
							"fieldLabel medium"><label class=
							"muted pull-right marginRight10px">Enable Web Scanning</label></td>

							<td class="fieldValue medium">
							<input type="checkbox" name="enableScanning" class="input-large" />
							</td>

							<td id="Accounts_detailView_fieldLabel_account_no" class="fieldLabel medium">
							<label class="muted pull-right marginRight10px">Scanner API</label></td>

							<td id="Accounts_detailView_fieldValue_account_no" class="fieldValue medium">
							<span data-field-type="string" class="value">Dynamic WebTwain</span>
							<span class="pull-right"><button class="btn btn-default btn-mini btn-scanner">Test Scan</button>
							</span></td>
						  </tr>
						</tbody>
					  </table>
					</div>
					</form>
				</div>
			</div>
			<div class="tab-pane" id="aboutExtension">
				<div style="overflow-y:auto;height:400px;">
					<pre>
						{include file="modules/Documents/README.txt"}
					</pre>
				</div>
			</div>
		</div>
	</div>
</div>
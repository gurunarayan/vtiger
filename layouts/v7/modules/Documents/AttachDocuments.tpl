{*<!--
/*********************************************************************************
 ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/
{foreach key=index item=jsModel from=$SCRIPTS}
	<script type="{$jsModel->getType()}" src="{$jsModel->getSrc()}"></script>
{/foreach}
-->*}
<style type="text/css">
#modulefolderslist{
	font-size:12px;
	padding:2px;
	width:95%;
}
</style>
<link rel="stylesheet" href="modules/Documents/plugins/jquery.ezdz.css">
<script src="modules/Documents/plugins/jquery.ezdz.js"></script>
<!--form enctype="multipart/form-data" method="POST" action="index.php?module=Documents&action=AttachDocuments" id="fileupload" class=""-->
	
	<table class="table table-condensed" style="table-layout:fixed;width:100%;">
		
		<!---Anji, Folder Tree View  -->
		<tr>
		<td colspan=3>
				<div id='docTreeJs' class="demo" style='overflow: auto;width:350px;'></div>
			</td> 
		</tr>
		<!---End, Anji, Folder Tree View  -->
		
		<tr>
			<td colspan=3>
				<input type='hidden' name='folderslist' value='' id='modulefolderslist'>
				{* <select >
			{foreach item=FOLDER_NAME key=FOLDER_VALUE from=$FOLDERS}
				{if $FOLDER_NAME[0] eq  $SELECTEDFOLDER}
					<option value="{$FOLDER_NAME[0]}" selected>{$FOLDER_NAME[1]}</option>
				{else}
					<option value="{$FOLDER_NAME[0]}">{$FOLDER_NAME[1]}</option>
				{/if}
			{/foreach}
				</select> *}
		</td>
		</tr>
		<tr>
		<td colspan=2>Multiple Records</td><td><input class="pull-right" type="checkbox" {$MULTIPLE_RECORDS}/></td>
		</tr>
		<tr>
		<td colspan=3>
					<input type="file" id='ddfilesupload' name="file_upload[]" multiple />  
		</td>
		</tr>
		<tr>
			<td colspan=3>
			<table class="table narrow" style="table-layout:fixed;width:100%;">
				<tr id="fileslist">
					<td width="60%"><strong>Filename</strong></td>
					<td width="30%"><strong>Size</strong></td>
					<td width="5%"><span class="pull-right" title="Clear all files">
					{*<i class="icon-trash"></i></span></td> *}
					<i class="icon-trash" onclick="removeBulkAttachment(fileslist,'+al');"></i></span></td> 
				</tr>
			</table>
			</td>
		</tr>
		<tr>
		<td>
		<div id="dialog" title="Link Window" style="display:none">
			 Link <input type="text" name="linktxt" id='linktxt' class="input input-large" >
			</div>
		</td>
		</tr>
		<tr>
			<td colspan=3>
			<div style="text-align:center;">
			{assign var="urllink" value=$SOURCEMODULE|cat:"_Detail_Js.show_textbox();"}
				<button class="btn btn-default btn-mini attachDocLink" onclick="javascript:show_textbox();">Link</button>
				{if $SCANALLOWED eq 1}
				<button class="btn btn-default btn-mini attachDocScan" onclick="bootbox.alert('Enable Scanner to commence scanning of document');">Scan</button>
				{/if}
				{assign var="url" value=$SOURCEMODULE|cat:"_Detail_Js.saveAttachments('attachedFiles');"}
				
				<button id="kid" class="btn btn-default btn-mini attachDocSave" onclick="javascript:saveAttachments();removeBulkAttachment(fileslist,'+al');">Save</button>
			
			</div>
			</td>
		</tr>
	</table>
{if $STATE eq 'CLOSEWINDOW'}
    {if $DENY}
        <script>
            window.close();
        </script>
    {else}
    <script>
        window.opener.sync();
        window.close();
    </script>
    {/if}
{/if}
  <script type="text/javascript">
	var attachedFiles = [];
    jQuery(document).ready(function($){
	
    	$('input[type="file"]').ezdz({
    			text: 'Drag and drop files here or click to open file selection dialog',
	            validators: {
	                maxNumber: 6
	            },
				accept: function(file){
					 
					listAttachment($('input[type="file"]')[0].files);
				},
	            reject: function(file, errors) {
	                if (errors.mimeType) {
	                    alert(file.name + ' must be an image.');
	                    return;
	                }

	                if (errors.maxWidth) {
	                    alert(file.name + ' must be width:2000px max.');
	                    return;
	                }

	                if (errors.maxHeight) {
	                    alert(file.name + ' must be height:2000px max.');
	                    return;
	                }

	                if (errors.maxNumber) {
		                alert('you can upload maximum of 6 images');
		                return;
	                }
	            }
    	});
		
		
    });

    function saveAvvttachments(){
		if (attachedFiles.length > 0){
			/* bootbox.alert('Saving Attachments for module and record'); */
			console.log(attachedFiles,'--',attachedFiles.length);
			  var params = {
            'module' : 'Documents',
            'action' : 'DDSaveAjax',
	    'fls':attachedFiles
            
    }
    AppConnector.request(params).then(
            function(data) {                    
                  console.log('data',data);
            },
            function(error,err){
                  console.log('err',err);
            }
    );
    e.preventDefault();
		} else {
			bootbox.alert('No Attachments selected for module and record');
		}
	}

    function listAttachment(fileslist){
		jQuery.map(fileslist, function(val) { 
			attachedFiles.push(val);
			var s = val.name.replace(/\./g, '_');  // Added By Parag
			s = s.replace(/\ /g, '_');  // Added By Parag
			var al = Number(attachedFiles.length)-1;  // Added By Parag
			jQuery("#fileslist").after('<tr class="'+s+'"><td width="65%" style="white-space: nowrap;  overflow: hidden;  text-overflow: ellipsis;">'+val.name+'</td><td width="30%">'+val.size+'</td><td width="5%"><span class="pull-right" title="Remove this file"><i class="icon-remove" onclick="removeAttachment(\''+s+'\',\''+al+'\');"></i></span></td></tr>');  // Modified By Parag
		});
	}

	/* Function added By Parag */
	function removeAttachment(ind,al){
		$("."+ind).remove();
		attachedFiles.splice(Number(al),1);
		$('#fileslist').nextAll('tr').remove();
		for(var i=0;i< attachedFiles.length;i++){
			var s = attachedFiles[i]['name'].replace(/\./g, '_');
			s = s.replace(/\ /g, '_');
			jQuery("#fileslist").after('<tr class="'+s+'"><td width="65%" style="white-space: nowrap;  overflow: hidden;  text-overflow: ellipsis;">'+attachedFiles[i]['name']+'</td><td width="30%">'+attachedFiles[i]['size']+'</td><td width="5%"><span class="pull-right" title="Remove this file"><i class="icon-remove" onclick="removeAttachment(\''+s+'\',\''+i+'\');"></i></span></td></tr>');
		}
		
	}
	
	// Function added by Murali
function removeBulkAttachment(fileslist, al){
		
	$('#fileslist').nextAll('tr').remove();
	
    jQuery( "ul.image-g li").remove();
    
    for(var i=0; i< attachedFiles.length; i++){	 
		var spans = $('#notimage');
		spans.remove();
    }
    attachedFiles.splice(Number(al),'+attachedFiles.length');
	attachedFiles=[];
  }
	
	
	
    function linkAttachment(attachedFile){
		bootbox.prompt('Enter URL for External Attachment for module and record');
	}
function show_textbox()
{

var thisInstance = this;
     jQuery( "#dialog" ).dialog({
    'buttons' : {
        'Cancel' : {
            priority: 'secondary', text:'Cancel', click: function() {
                jQuery(this).dialog('close');
            },
        },
	'Saveurl' : {
            priority: 'secondary',text:'Saveurl', click: function() {
	    		 var linktxt = jQuery("#linktxt").val();
			
				var params = {
					'module' : 'Documents',
					'parent' : app.getParentModuleName(),
					'action' : 'DDLinkSave',
					'srcrecordid' : jQuery('#recordId').val(),
					'mname' : '{$SOURCEMODULE}',
					'fileurl' : linktxt

				}
			
				AppConnector.request(params).then(function(data) {
					if(data.success) {
					Vtiger_Helper_Js.showPnotify('Saved the URL');
						console.log('data.success',data.success);
						 location.reload(true); 
					}
				},
				function(error,err){
				});
				jQuery(this).dialog('close');
			}		
			
        },
    }
}); }
 function saveAttachments(){

            formdata = false;
    if (window.FormData) {
        formdata = new FormData();
    }
    var i = 0, len = attachedFiles.length, img, reader, file;
	/* var foldername = jQuery("#modulefolderslist").val(); */
	var foldername = jQuery("#modulefolderslist").val();
	if(foldername == ''){
		Vtiger_Helper_Js.showPnotify('Please Select Folder.');
		return false;
	}
	
		for (; i < len; i++) {
			file = attachedFiles[i];
			if (window.FileReader) {
				reader = new FileReader();
				reader.onloadend = function(e) {
					/*  showUploadedItem(e.target.result, file.fileName);*/
				};
				reader.readAsDataURL(file);
			}
			if (formdata) {
				formdata.append("file[]", file);
				formdata.append("action", 'DDSaveAjax');
				formdata.append("module", 'Documents');
				formdata.append("srcmodule", '{$SOURCEMODULE}');
				formdata.append("srcrecordid", jQuery('#recordId').val());
				formdata.append("folderid", foldername);
			}
		}
			 var params = {
				 'data':	formdata,
				'dataType':'json',
                'module' : '{$SOURCEMODULE}',
                'action' : 'DDSaveAjax',
                'mode' : 'uploadDocsToStorage',
				'processData': false,
				'contentType': false
				
                
            }
            AppConnector.request(params).then(function(data) {
				if(data.success) {
					Vtiger_Helper_Js.showPnotify('Saved the Attachments');
					console.log('data.success',data.success);
				 location.reload(true); 
				}
			},
			function(error,err){
			}); 
 }

  </script>
  
<link rel="stylesheet" href="libraries/jstree/themes/default/style.min.css" />
<script src="libraries/jquery/jquery.min.js" ></script>
<script src="libraries/jstree/jstree.js" ></script>

<script>

$j = jQuery.noConflict(true);
$j(document).ready(function($){
		
	var jsondata = {$FOLDERS_DATA};
		<!---Anji :: jstree view jscode ---->  
		$j('#docTreeJs').jstree({ 
			'core' : {
					'data' : jsondata
					 
				} ,
			'plugins' : [ 'changed', 'types'],
			'types' : {	
				'#' : {
					
					},
				'root' : {
					  'icon' : 'jstree-file',
					  'valid_children' : ['default']
					},			
				'default' : { 'valid_children' : ['default','file'] },
				'file' : { 'icon' : 'jstree-file' }
			}
		}).on('changed.jstree', function (e, data) { 		  
			  var selected_id  = data.changed.selected;
			  /* console.log(data.changed.deselected); newly deselected */ 
			  var t = data.node.id;
				console.log(t); 
				
				$j('#modulefolderslist').val(t);
				
		  
		});
			
		<!---Anji :: jstree view jscode ---->  
	
});
  
  </script>


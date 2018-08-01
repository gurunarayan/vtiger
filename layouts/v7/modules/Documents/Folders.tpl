{strip}
<link rel='stylesheet' href='libraries/jstree/themes/default/style.min.css' />   


<style>

.jstree-grid-wrapper { 
	border:1px solid #ddd;
}
.jstree-grid-midwrapper  { 	
	background-color:white;
}
 
.jstree-grid-header-cell {
	text-align:left;
	font-size:11px;
	border:1px solid #ddd;
	padding:5px;

	white-space:nowrap;
	color:#333;
	background: #fff;
	background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#ededed));
	background: -moz-linear-gradient(top,  #fff,  #ededed);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#ededed');
}

.jstree-grid-separator {
	display:none;
}

.jstree-anchor,.jstree-grid-cell {
	text-overflow:ellipsis !important;
	white-space:nowrap !important;
	overflow:hidden !important;
}
div.jstree-grid-cell-root-folder_tree {
     line-height: 24px;
     height: 24px; 
}
</style>
<div class='container-fluid editViewContainer'> 
	
	<div class='contentHeader row-fluid'>
	<h3 class='span8 textOverflowEllipsis'>Folder Tree</h3>
	<!--<span class='pull-right'>			
		<button type='button' class='btn btn-success addNewFolder' onclick='folder_create();' disabled ><i class='glyphicon glyphicon-asterisk'></i> <strong>Create Folder</strong></button> 
		<button type='button' class='btn btn-warning renameFolder' onclick='folder_rename();' disabled ><i class='glyphicon glyphicon-pencil'></i> <strong>Rename</strong></button>&nbsp;
		<a href='#' target='_blank' title="Download Selected File" id='fileDownload'><button type='button' class='btn btn-danger clsDownloadFile' disabled ><i class='glyphicon glyphicon-remove'></i> <strong>Download</strong></button></a>
	</span>	-->
	</div>
	<div class='row-fluid'>
		<div>
			<input class="search-input form-control" placeholder='Search'></input>
		</div>
		<div id='folder_tree' class=''></div>	
		 
	</div>
</div>   
<!--<script src="https://code.jquery.com/jquery-1.8.0.js"   integrity="sha256-00Fh8tkPAe+EmVaHFpD+HovxWk7b97qwqVi7nLvjdgs="  
 crossorigin="anonymous"></script>
<script src='libraries/jquery/jquery.dataAttr.min.js?v=6.4.0'></script>
<script src='libraries/jstree/jstree.js?v=6.4.0'></script>
<script src='libraries/jstree/jstreegrid_new.js?v=6.4.0'></script>-->
<script>  
/* $j = jQuery.noConflict(); */
jQuery(document).ready(function(){   
	/* Search */
	jQuery(".search-input").keyup(function() { 
        var searchString = jQuery(this).val();
        console.log(searchString);
		if(searchString != ''){
			jQuery('#folder_tree').jstree('search', searchString);
		}       
		
    });
	 
	 
	var jsondata = {$FOLDERS_DATA};
 
	jQuery('#folder_tree').jstree({ 
		'plugins' : [ 'dnd', 'types', 'state', 'grid','sort','changed','themes','contextmenu','search','json_data'],
			
		'core' : {
			'check_callback' : function (op, node, par, pos, more) { 
				if ((op === "move_node" || op === "copy_node") && node.type && node.type == "root") {  
					return false;
				}
				if((op === "move_node" || op === "copy_node") && more && more.core && !confirm('Are you sure ...')) {  
				return false;
			  }
			  return true;
			},
			'data' :  jsondata 
						
		},
				 
		'json_data':{ 	"progressive_render" : true		},	
        'search' : { 'show_only_matches' : false},
		'types' : {	
				'#' : {
					
					},
				'root' : {
					  'icon' : 'jstree-file',
					  'valid_children' : ['default']
					},			
				'default' : { 'valid_children' : ['default','file'] },
				'file' : { 'icon' : 'jstree-file' }
			},
			'grid': {
				'columns': [
					{ 'width':'15%','header': "Document Folder" },				  
					{ 'width':'10%','header': "File Name",'value': "title" },
					{ 'width':'10%','header': "Module",'value': "module" },				  
					{ 'width':'10%','header': "Modified Time",'value': "modifiedtime" },				  
					{ 'width':'10%','header': "User",'value': "user" },	
					{ 'width':'10%','header': "Assigned To",'value': "smownerid" },	
					{ 'width':'10%','header': "Tools",'value': "tools"  }
				  
				],
				resizable:true 
			}
			 /* ,
			checkbox: {
				tie_selection: false,
				whole_node: false,
			} */
			
		}).on('loaded.jstree', function() {
			
			/* jQuery("#folder_tree").jstree('open_all'); */
			
		}).on('rename_node.jstree', function(e, data) {
			  			 
			if(data.node.type == 'file'){ 				 
				Vtiger_Helper_Js.showPnotify('file name can not change.');
				jQuery('#folder_tree').jstree().refresh();
				return false;
			}else{ 				
				var renamedata = [];
				renamedata['fid'] = data.node.id;
				renamedata['ftext'] = data.node.text;
				renamedata['fparent'] = data.node.parent;
				renamedata['ftype'] = data.node.type;				
				var namelength = renamedata['ftext'].length;
				if(namelength > 150){  
					Vtiger_Helper_Js.showPnotify('Folder Name less then 150 Characters.');
					jQuery('#folder_tree').jstree().refresh();
				}else{ 
					fun_rename_folder(renamedata,'rename');
				} 
			}		
			
		}).on('create_node.jstree', function(e, data) { 
			
		}).on('changed.jstree', function (e, data) { 		  
			  
		 

		  /*  var selected_id  = data.changed.selected; console.log(data.changed.deselected); newly deselected */ 
		   var dnid = data.node.id;
	if(typeof(dnid) != "undefined" && dnid !== null) {

		  var t = data.node.id;	

		  if (t.indexOf("f") >= 0){ 			
			console.log('changed func-file');

			var aid = data.node.original.attachmentsid;
			var nid = data.node.original.notesid;
			var link = "index.php?module=Documents&action=DownloadFile&record="+nid+"&fileid="+aid;
			jQuery('#fileDownload').attr("href", link);
			
			jQuery('.addNewFolder').disable(true);
			jQuery('.renameFolder').disable(true);	
			jQuery('.clsDownloadFile').prop('disabled', false);
			jQuery('#fileDownload').prop('disabled', false); 			 		
		  }else{ 
			console.log('changed func-folder');
			jQuery('.addNewFolder').prop('disabled', false);
			jQuery('.renameFolder').prop('disabled', false);
			jQuery('.clsDownloadFile').disable(true);
			jQuery('#fileDownload').disable(true);			
		  }
		 }
		  
		}).on('move_node.jstree', function (e, data) {  
		     
		   console.log(data);  
		   var renamedata = [];
			renamedata['fid'] = data.node.id;
			renamedata['ftext'] = data.node.text;
			renamedata['fparent'] = data.node.parent;
			renamedata['ftype'] = data.node.type;
			renamedata['old_parent'] = data.node.old_parent; 
			var p = renamedata['fparent'];
			var parray = data.node.parents;
			if(jQuery.isNumeric(parray[0])){
				if (p.indexOf("f") >= 0){ 	
					console.log('file');				 
					Vtiger_Helper_Js.showPnotify('Cont move under File.');
					jQuery('#folder_tree').jstree().refresh();
					return false;
				}else{ 
					fun_dnd(renamedata);
				}	
			}else{
				Vtiger_Helper_Js.showPnotify('Cont move under File.');
				jQuery('#folder_tree').jstree().refresh();
				return false;
			} 
		});

		/* cls for create folder */
		jQuery("[data-action='dataAddFolder']").live("click", function(e) {
			var id = jQuery(this).data("id");
			folder_create(id);
			return false;
		});

		/* cls for delete folder */
		jQuery("[data-action='dataDeleteFolder']").live("click", function(e) {
			var id = jQuery(this).data("id");
			folder_delete(id);
			return false;
		});

		/* cls for delete File */
		jQuery("[data-action='dataDeleteFile']").live("click", function(e) {
			var id = jQuery(this).data("id");
			file_delete(id);
			return false;
		});
		
		/* edit folder */
		jQuery("[data-action='dataEditFolder']").live("click", function(e) {
			var id = jQuery(this).data("id");
			var ref = jQuery('#folder_tree').jstree(true);		 
			ref.edit(id); 
		});
		
		/* After jsTree has been loaded, set valid widths in percents */
		jQuery(".jstree-grid-header th:nth-child(1)").css( "width","30%" );
		 
		 
});
	function folder_create(id) { 
		var ref = jQuery('#folder_tree').jstree(true);
		if(id){
			var sel = id;			
		}else{
			var sel = ref.get_selected();
			if(!sel.length) { return false; }
			sel = sel[0];	
		} 			 
		var parent_folder_id = sel;
		var new_older_id = ref.create_node(sel, { 'type':'default' ,});
		if(new_older_id){			 
			ref.edit(new_older_id);
		}
	}
	
	function folder_rename() { 		 
		var sel = ref.get_selected();				
		if(!sel.length) { return false; }
		sel = sel[0];	 
		var ref = jQuery('#folder_tree').jstree(true);				 
		ref.edit(sel);
	}
	
	function file_delete(id){
		bootbox.confirm("Are you sure you want to delete the File!", function(result){  
			if(result == true){ 
				var ref = jQuery('#folder_tree').jstree(true);
				if(id){
					var sel = id;			
				}else{ 
					var sel = ref.get_selected();
					if(!sel.length) { return false; }
					sel = sel[0];	
				} 	
				params = {};
				params['module'] = 'Documents';	 
				params['action'] = 'Jstree';
				params['mode'] = 'deleteFile';	
				params['file_id'] = sel; 
				console.log(params);
				AppConnector.request(params).then(
					function(data){						 
						var params = {};
						if (data.success){ 
							Vtiger_Helper_Js.showPnotify(data.result.message);
							ref.delete_node(sel);				
							location.reload();		 
							
							/* jQuery('#folder_tree').jstree().refresh(); 	*/	
							 
						} else { 
							
						}
						/* location.reload();	*/			
					},
					function(error) { 				 
						alert('please try again!');
					}
				);
			}
		});
	}
	function folder_delete(id) {
		bootbox.confirm("Are you sure you want to delete the Folder!", function(result){ 
			 
			if(result == true){
					 
				var ref = jQuery('#folder_tree').jstree(true);
				if(id){
					var sel = id;			
				}else{
					var sel = ref.get_selected();
					if(!sel.length) { return false; }
					sel = sel[0];	
				} 	 
				 
				/* Ajax call for delete*/
				params = {};
				params['module'] = 'Documents';	 
				params['action'] = 'Jstree';
				params['mode'] = 'delete';	
				params['folder_id'] = sel; 
				 
				AppConnector.request(params).then(
					function(data){						 
						var params = {};
						if (data.success){ 
							Vtiger_Helper_Js.showPnotify(data.result.message);
							ref.delete_node(sel); 		
												
						} else { 
							
						}
						/* location.reload();	*/			
					},
					function(error) { 				 
						alert('please try again!');
					}
				);		
				/* End*/
				
			}			
		 });
		
	}; 

	 
	function fun_rename_folder(params,mode) { 
		params['module'] = 'Documents';	 
		params['action'] = 'Jstree';
		params['mode'] = mode;		 
		AppConnector.request(params).then(
			function(data){						 
				var params = {};
				if (data.success){ 
					 Vtiger_Helper_Js.showPnotify(data.result.message);
					if(data.result.type == 'new'){
						location.reload();
					} 
					/* alert(data.result.message);
					 jQuery('#folder_tree').jstree().refresh(); */
					
				} else { 
					
				}
				/* location.reload();	*/			
			},
			function(error) { 				 
				alert('Very serious error. Investigate!!');
			}
		);
	}
	
	function fun_dnd(params){
		params['module'] = 'Documents';	 
		params['action'] = 'Jstree';
		params['mode'] = 'dndnode';		 
		AppConnector.request(params).then(
			function(data){						 
				var params = {};
				if (data.success){ 
					Vtiger_Helper_Js.showPnotify(data.result.message);				
				} else { 
					
				}
				/* location.reload();	*/			
			},
			function(error) { 				 
				alert('Very serious error. Investigate!!');
			}
		);
	} 
	
	</script>
     
{/strip}

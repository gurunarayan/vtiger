/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Edit_Js("PointSale_Edit_Js",{
},{
	/**
	 * Function to register event for image delete
	 */
	registerEventForImageDelete : function(){
			var formElement = this.getForm();
			var recordId = formElement.find('input[name="record"]').val();
			formElement.find('.imageDelete').on('click',function(e){
					var element = jQuery(e.currentTarget);
					var parentTd = element.closest('td');
					var imageUploadElement = parentTd.find(':file');
					var fieldInfo = imageUploadElement.data('fieldinfo');
					var mandatoryStatus = fieldInfo.mandatory;
					var imageId = element.closest('div').find('img').data().imageId;
					element.closest('div').remove();
					var exisitingImages = parentTd.find('[name="existingImages"]');
					if(exisitingImages.length < 1 && mandatoryStatus){
							formElement.validationEngine('detach');
							imageUploadElement.attr('data-validation-engine','validate[required,funcCall[Vtiger_Base_Validator_Js.invokeValidation]]');
							formElement.validationEngine('attach');
					}

					if(formElement.find('[name=imageid]').length != 0) {
							var imageIdValue = JSON.parse(formElement.find('[name=imageid]').val());
							imageIdValue.push(imageId);
							formElement.find('[name=imageid]').val(JSON.stringify(imageIdValue));
					} else {
							var imageIdJson = [];
							imageIdJson.push(imageId);
							formElement.append('<input type="hidden" name="imgDeleted" value="true" />');
							formElement.append('<input type="hidden" name="imageid" value="'+JSON.stringify(imageIdJson)+'" />');
					}
			});
	},

	registerSubmitEvent: function() {
		var form = jQuery('#EditView');
		form.on('submit',function(e) {
			if(form.data('submit') == 'true' && form.data('performCheck') == 'true') {
				return true;
			} else {
				form.data('submit', 'true');
			}
		});
		var editViewForm = this.getForm();
		editViewForm.submit(function(e){
			if((editViewForm.find('[name="existingImages"]').length >= 1) || (editViewForm.find('[name="imagename[]"]').length > 1)){
				jQuery.fn.MultiFile.disableEmpty(); // before submiting the form - See more at: http://www.fyneworks.com/jquery/multiple-file-upload/#sthash.UTGHmNv3.dpuf
			}
		});
	},

   registerEventForCkEditor : function(){
		var form = this.getForm();
		form.find('textarea.richtext').each(function(){
    		var inputname=jQuery(this).attr('name');
			var elem=jQuery(this).data('fieldinfo');
			var noteContentElement=jQuery(this);
			var fieldUIType = elem.fielduitype;
			if(fieldUIType==540){
				if(noteContentElement.length > 0){
					noteContentElement.removeAttr('data-validation-engine').addClass('ckEditorSource');
					var ckEditorInstance = new Vtiger_CkEditor_Js();
					ckEditorInstance.loadCkEditor(noteContentElement);
				}
			}
			
		});
	},

	registerEvents : function(){
		this._super();
	},

	registerBasicEvents : function(container) {
		this._super(container);
		this.registerEventForCkEditor();
	}
});


//$CURRENT_VIEW
//$VIEW
//$DEFAULT_RECORD_VIEW
//$IS_AJAX_ENABLED
//$IS_DELETABLE
//$IS_EDITABLE

var path = "";
var vtRsPath = [];
var plparams ={};
var pickBlock = {};			// will hold the array of blockids assigned as pickblock for each picklist item
var vtDZ_pickBlocks = {};	// will hold the array of blockids that have been chosed as pickblocks for the picklist 
var plFieldId = {};			// will hold the fieldid of the picklist field
var plFieldName = {};		// will hold the fieldname of the picklist field
var plFieldBlockId = {};	// will hold the blockid of the picklist field, used to prevent selection as pickblock
var plFieldLabel2Name = {}; // will hold the field label as value of the picklist field, used to preload
var plFieldValue = {};		// will hold the field value of the picklist field, used to preload
var plModuleName = {};		// will hold the module name as value of the picklist field, used to preload

function plwalker(key, value) {
	var savepath = path;
	path = path ? (path + "::||::" + key) : key;
	if (key=="uitype" && (value=="15" || value=="16")){
		var fieldid=vtRsPath.pop();
		var blockid=vtRsPath.pop();
		vtRsPath.push(blockid);
		vtRsPath.push(fieldid);
		pllabel = vtRecordStructure[blockid][fieldid].label;
		plvalues = Object.keys(vtRecordStructure[blockid][fieldid].fieldInfo.picklistvalues);
		plparams[pllabel] = plvalues;
		vtDZ_pickBlocks[pllabel] = [];
		plFieldBlockId[pllabel] = vtRecordStructure[blockid]["id"];
		plFieldId[pllabel] = vtRecordStructure[blockid][fieldid].id;
		plFieldName[pllabel] = vtRecordStructure[blockid][fieldid].name;
		plFieldLabel2Name[vtRecordStructure[blockid][fieldid].name] = pllabel;
		plFieldValue[vtRecordStructure[blockid][fieldid].name] = vtRecordStructure[blockid][fieldid].fieldvalue;
		plModuleName[vtRecordStructure[blockid][fieldid].name] = vtRecordStructure[blockid][fieldid].module.name;
	}
	if (value !== null && typeof value === "object") {
		// Recurse into children
		vtRsPath.push(key);
		jQuery.each(value, plwalker);
		vtRsPath.pop();
	}
	path = savepath;
}

jQuery(document).ready(function() {
	if (typeof(vtRecordStructure) !== 'undefined') {
		// Implementation of Pickblocks in EditView
		jQuery.each(vtRecordStructure, plwalker);
		if (Object.keys(pickBlocks).length > 0) {
			jQuery.each(pickBlocks, function(index, value){
				pickkey = plFieldValue[index];
				selectedBlockLabel = "";
				jQuery.each(value, function(i,e){
					jQuery("th.blockHeader:contains('"+e[1]+"')").closest('table').hide();
					if (i == pickkey) {
						selectedBlockLabel=e[1];
					}
				});
				jQuery("th.blockHeader:contains('"+selectedBlockLabel+"')").closest('table').show();

				// set the onchange function
				jQuery("select[name='"+index+"']").change(function(){
					selectedBlockLabel = "";
					selectedKey = index;
					pickkey = jQuery("select[name='"+index+"']").next().find("a.chzn-single").find("span").first().text().trim();
					pickKeys = pickBlocks[selectedKey];
					jQuery.each(pickKeys, function(i,e){
						jQuery("th.blockHeader:contains('"+e[1]+"')").closest('table').hide();
						if (i == pickkey) {
							selectedBlockLabel=e[1];
						}
					});
					jQuery("th.blockHeader:contains('"+selectedBlockLabel+"')").closest('table').show();
				}); // end on change registration
			});
		}
	}
});

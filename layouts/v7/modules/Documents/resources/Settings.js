/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class('Documents_Settings_Js', {

}, {
		showNotify : function(customParams) {
			var params = {
				title: 'Document Settings',
				text: customParams.text,
				type: customParams.type,
				width: '30%',
				delay: '2000'
			};
			Vtiger_Helper_Js.showPnotify(params);
		},

		f: function(){
			alert("hi...");
		},

		g: function(){
			bootbox.alert("hi...");
		},

		searchEntityModules: function(){
			this.showNotify({"text":"Searching for new modules","type":"error"});
		},
		showAllModules: function(){
			jQuery("#associatedModulesList tr").removeClass("hide");
			jQuery("#am_viewname").html("All chosen entity modules");
		},
		showEnabledModules: function(){
			jQuery("#associatedModulesList tr.notenabled").addClass("hide");
			jQuery("#associatedModulesList tr.enabled").removeClass("hide");
			jQuery("#am_viewname").html("All enabled modules");
		},
		showNotEnabledModules: function(){
			jQuery("#associatedModulesList tr.enabled").addClass("hide");
			jQuery("#associatedModulesList tr.notenabled").removeClass("hide");
			jQuery("#am_viewname").html("All modules not enabled for Documents Association");
		},

		removeChosenModule: function(module){
			var thisInstance = this;
			modulename = jQuery(module.currentTarget).closest('tr').data('modulename');
			modulelabel = jQuery(module.currentTarget).closest('tr').data('modulelabel');
			modulestatus = jQuery(module.currentTarget).closest('tr').data('modulestatus');
			if (modulestatus==0){
				bootbox.alert("Removing "+modulelabel+" (Not Enabled)");
			} else {
				bootbox.alert("Cannot remove an enabled module "+modulelabel);
			}
		},

		/**
		 * Function to handle click event for modify module document options
		 */

		editModuleSettings: function(module){
			var thisInstance = this;
			modulename = jQuery(module.currentTarget).closest('tr').data('modulename');
			jQuery('input:hidden[name=hdModulename]').val(modulename);
			modulelabel = jQuery(module.currentTarget).closest('tr').data('modulelabel');
			modulestatus = jQuery(module.currentTarget).closest('tr').data('modulestatus');
			if (modulestatus==0){
				Vtiger_Helper_Js.showConfirmationBox({'message' : "Enable "+modulelabel+" for association with Documents?"}).then(
					function(e, modulelabel) {
						thisInstance.enableDDModule(modulename);
						thisInstance.showNotify({"text":"Enabling module: "+modulename+"","type":"info"});//Fixed by SL on 24th july:
					});
			} else {
				var contents = jQuery('#ModuleDocumentsContainer');
				thisInstance.getFolderDetails(modulename);
				var moduleDocsSettingsContainer = contents.find('.moduleDocsSettingsModal').clone(true, true);
					//this.showNotify({"text":"Editing settings for "+modulelabel,"type":"warning"});
					var callBackFunction = function(data) {
					//thisInstance.getFolderDetails(modulename);
					data.find('.moduleDocsSettingsModal').removeClass('hide');
					var headingText = data.find("#modalheading");
					//fixed path for json SL 
					var params = {
					'module' : app.getModuleName(),
					'parent' : app.getParentModuleName(),
					'action' : 'DDSettingsLoad',
					'mname' : modulename
					

				}
					AppConnector.request(params).then(function(data) {
						if(data.success) {
							var result = data['result'];//manasa on 21 jan 2016
							var record  = '';
							for(i=0; i< data.result.length; i++) {
								var record  = data.result[i];
								console.log(typeof record);
								if(record != '' && record != null){
									jQuery('select[name=folderslist]').val(record['folderslist']);
									jQuery('select[name=folderslist]').trigger('change');
									jQuery("input[name=fileextensions][value=" + record['fileextensions'] + "]").prop('checked', true);
									//jQuery('select[name=xyz]').val(record['xyz']);
									console.log(record['xyz'].length);
									jQuery('select[name=xyz]').val(record['xyz']);
									jQuery('select[name=xyz]').trigger('change');
									//jQuery("#viewColumnsSelect123").val('.bmp').trigger('chosen:updated');
								}else{
									jQuery('select[name=folderslist]').val(1);
									jQuery('select[name=folderslist]').trigger('change');
									jQuery("input[name=fileextensions][value=Allowall]").prop('checked', true);
								}
										
							}
						}
				  },
				  function(error,err){
				});
					//data.find('select[name="folderslist"]').val();
					data.find('select[name="folderslist"]').on('change',function(e){
							var selectedOption = jQuery(e.currentTarget).val();
							var selectedname = e.currentTarget.options[e.currentTarget.selectedIndex].text;
					});
					data.find('select[name="xyz"]').on('change',function(e){
							var mulsel = jQuery(e.currentTarget).val();
							
							//var mulindex = e.currentTarget.options[e.currentTarget.mulsel].text;
					});
					//register all select2 Elements
					app.showSelect2ElementView(data.find('select'));

					var form = data.find('.moduleDocsSettingsForm');
					//thisInstance.setBlocksListArray(form);
					//var fieldLabel = form.find('[name="label"]');
					var params = app.validationEngineOptions;
					params.onValidationComplete = function(form, valid){
						if(valid) {
							var formData = form.serializeFormData();
							if(jQuery.inArray(formData['label'], thisInstance.blockNamesList) == -1) {
								thisInstance.saveModuleDetails(form,modulename).then(
									function(data) {
										var params = {};
										if(data['success']) {
											var result = data['result'];
											if (!result['blockType']){
												params['text'] = app.vtranslate('JS_CUSTOM_BLOCK_ADDED');
											} else {
												params['text'] = app.vtranslate('JS_CUSTOM_BLOCK_ADDED')+"\n"+result["message"];
											}
										} else {
											params['text'] = data['error']['message'];
											params['type'] = 'error';
										}
										console.log('shownotify',JSON.stringify(params));
										thisInstance.showNotify(JSON.stringify(params));//Fixed by SL on 24th july:
									}
								);
								app.hideModalWindow();
								return valid;
							} else {
								var result = app.vtranslate('JS_BLOCK_NAME_EXISTS');
								fieldLabel.validationEngine('showPrompt', result , 'error','topLeft',true);
								e.preventDefault();
								return;
							}
						}
					}
					form.validationEngine(params);

					form.submit(function(e) {
						e.preventDefault();
					})
				}
				app.showModalWindow(moduleDocsSettingsContainer,function(data) {
					if(typeof callBackFunction == 'function') {
						callBackFunction(data);
					}
				}, {'width':'1000px'});
			}
		},

		/**
		 * Function to save the new custom block details
		 */
		 ////Function added by SL for DD files enable :27th July:start
		enableDDModule : function(modulename){
		var params = {
				'module' : app.getModuleName(),
				'parent' : app.getParentModuleName(),
				'action' : 'DDSettingsSave',
				'mname' : modulename,
				'operation' : 'enable'

			}
			AppConnector.request(params).then(function(data) {
				if(data.success) {
					location.reload(true);
				}
			},
			function(error,err){
			});
		
		},
		//manasa jan 21 2015
		getFolderDetails : function(modulename){
			var params = {
				'module' : 'Documents',
				'action' : 'SettingsEditForm',
				'relmod' : modulename
			}
			AppConnector.request(params).then(function(data) {
				jQuery("#foldertree").empty();
				jQuery("#foldertree").append(data);

			},
			function(error,err){
			});
			
		},
		//manasa ended jan 21 2015
			////Function added by SL for DD files enable :27th July'15:end
		/**
		 * Function to save the new custom block details
		 */
		saveModuleDetails : function(form,modulename) {
			//console.log('saveModuleDetails');
			var thisInstance = this;
			//jQuery('#multiple-label-example').chosen();
			//console.log('radio',jQuery("input[name=fileextensions]:checked").val());
			// then, declare how you handle the change event
			
			var aDeferred = jQuery.Deferred();
			var progressIndicatorElement = jQuery.progressIndicator({
				'position' : 'html',
				'blockInfo' : {
					'enabled' : true
				}
			});
			var settingsFormData = form.serializeFormData();
			var params ={
					'module'  : app.getModuleName(),
					'parent': app.getParentModuleName(),
					'srcmodule': modulename,
					'sourceModule': jQuery('#selectedModuleName').val(),
					'action'  : 'Settings',
					'settings'  : settingsFormData
			}

			AppConnector.request(params).then(
				function(data) {
					if(data.success) {
					location.reload(true);
				}
				},
				function(error) {
					progressIndicatorElement.progressIndicator({'mode' : 'hide'});
					aDeferred.reject(error);
				}
			);
			return aDeferred.promise();
		},
		registerExtensions : function (){
			var thisInstance = this;
			var chjson;
			jQuery('select[name=xyz]').chosen().change(function(e){
				console.log(JSON.stringify(jQuery(e.target).val()));
				jQuery('input:hidden[name=hdextensions]').val(JSON.stringify(jQuery(e.target).val()));	 
			});	
			console.log('hidden ... '+jQuery('input:hidden[name=hdextensions]').val());	
		},
updateHiddenExtensions : function(ExtensionsEle) {
		ExtensionsEle.html('');
		console.log("ddddd");
		var ExtensionsJSON = jQuery('input:hidden[name=hdextensions]').val();
		if(ExtensionsJSON) {
			var hiddenExtensions = '';
			var Extens = JSON.parse(ExtensionsJSON);
			for(i in Extens) {
				hiddenExtensions += '<option selected value='+Extens[i]+'>'+Extens[i]+'</option>';
			}
			ExtensionsEle.html(hiddenExtensions);
			
		}
	},
		registerEvents : function (){
			var thisInstance = this;
			jQuery("button.chooseEntityModule").click(function(e){thisInstance.searchEntityModules()});
			jQuery("i.icon-pencil").click(function(e){thisInstance.editModuleSettings(e)});
			jQuery("i.icon-trash").click(function(e){thisInstance.removeChosenModule(e)});
			jQuery("li.entitiesEnabled").click(function(e){thisInstance.showEnabledModules()});
			jQuery("li.entitiesNotEnabled").click(function(e){thisInstance.showNotEnabledModules()});
			jQuery("li.allChosenEntities").click(function(e){thisInstance.showAllModules()});
			thisInstance.showNotify({"text":"Documents Settings JS functions registered successfully","type":"error"});
		},

	}
);

jQuery(document).ready(function() {
	var instance = new Documents_Settings_Js();
	//instance.showNotify({"text":"Loaded successfully after doc ready","type":"error"});
	//Register Events
});

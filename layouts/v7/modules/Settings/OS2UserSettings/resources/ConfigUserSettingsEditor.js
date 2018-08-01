/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Settings_OS2UserSettings_ConfigUserSettingsEditor_Js",{},{
	
	/*
	 * Function to save the Configuration Editor content
	 */
	saveConfigEditor : function(form) {
		var aDeferred = jQuery.Deferred();
		
		var data = form.serializeFormData();
		var updatedFields = {};
		jQuery.each(data, function(key, value) {
			updatedFields[key] = value;
		})
		
		var params = {
			'module' : app.getModuleName(),
			'parent' : app.getParentModuleName(),
			'action' : 'ConfigUserSettingsEditorSaveAjax',
			'updatedFields' : JSON.stringify(updatedFields)
		}
		AppConnector.request(params).then(
			function(data) {
				aDeferred.resolve(data);
			},
			function(error,err){
				aDeferred.reject();
			}
		);
		return aDeferred.promise();
	},
	
	/*
	 * Function to load the contents from the url through pjax
	 */
	loadContents : function(url) {
		var aDeferred = jQuery.Deferred();
		app.request.pjax({"url" : url}).then(
			function(err, data){
				if(err === null){
					jQuery('.settingsPageDiv ').html(data);
					aDeferred.resolve(data);
				}
			},
			function(error, err){
				aDeferred.reject();
			}
		);
		return aDeferred.promise();
	},
	
	/*
	 * function to register the events in editView
	 */
	registerEditViewEvents : function(e) {
		var thisInstance = this;
		var form = jQuery('#ConfigUserSettingsEditorForm');
		var cancelLink = jQuery('.cancelLink', form);
		var detailUrl = form.data('detailUrl');
        
		//register validation engine
		var params = {
            submitHandler : function(form) {
                  app.helper.showProgress();
                var form = jQuery(form);
				thisInstance.saveConfigEditor(form).then(
					function(data) {
						var params = {};
						if(data['success']) {
							params['text'] = 'User Configuration Settings Saved.'
							thisInstance.loadContents(detailUrl).then(
								function(data) {
									app.helper.hideProgress();
									jQuery('.settingsPageDiv ').html(data);
									thisInstance.registerDetailViewEvents();
								}
							);
						} else {
							app.helper.hideProgress();
							params['text'] = data['error']['message'];
							params['type'] = 'error';
						}
						
						Settings_Vtiger_Index_Js.showMessage(params);
					},function(error, err) {
						app.helper.hideProgress();
					}
				);
            }
		};
		if (form.length) {
        	form.vtValidate(params);
		 	form.on('submit', function(e){
            	e.preventDefault();
            	return false;
        	});
		}
		
		
		//register click event for cancelLink
		var cancelLink = form.find('.cancelLink');
		cancelLink.click(function(e) {
			var detailUrl = form.data('detailUrl');
			thisInstance.loadContents(detailUrl).then(
				function(data) {
                     jQuery('.editViewPageDiv').html(data);
					//after loading contents, register the events
					thisInstance.registerDetailViewEvents();
				}
			);
		});
	},
	
	/*
	 * function to register the events in DetailView
	 */
	registerDetailViewEvents : function() {
		var thisInstance = this;
		var container = jQuery('#ConfigUserSettingsEditorDetails');
		var editButtonUserSettings = container.find('.editButtonUserSettings');
		//editButtonUserSettings
		//Register click event for edit button
		editButtonUserSettings.click(function() {
			var url = editButtonUserSettings.data('url');
			app.helper.showProgress();

			thisInstance.loadContents(url).then(
				function(data) {
					app.helper.hideProgress();
					jQuery('.settingsPageDiv ').html(data);
					thisInstance.registerEditViewEvents();
				}, function(error, err) {
					console.log(error+'/-/'+err);
					app.helper.hideProgress();
				}
			);
		});
	},
	
	registerEvents: function() {
		if(jQuery('#ConfigUserSettingsEditorDetails').length > 0) {
			this.registerDetailViewEvents();
		} else {
			this.registerEditViewEvents();
			//app.registerEventForDatePickerFields('#ConfigUserSettingsEditorForm');
			/* handled registration of time field */
			var timeFieldElement = jQuery('#ConfigUserSettingsEditorForm').find('[data-toregister="time"]');
			if(timeFieldElement.length > 0){
				var dropDownMenu = timeFieldElement.addClass('timepicker-default timePicker');
				app.registerEventForTimeFields(dropDownMenu);
			}
		}
	}

});

jQuery(document).ready(function(e){
	var tacInstance = new Settings_OS2UserSettings_ConfigUserSettingsEditor_Js();
	tacInstance.registerEvents();
});

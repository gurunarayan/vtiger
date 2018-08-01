/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Settings_LoginPage_Edit_Js",{
	displayPage : function() {
		var instance = this;
		jQuery('.create').on('click',function(){
		var rows = jQuery('#row').val(); 
		var cols = jQuery('#column').val();
		var dataString = 'rows='+ rows+ '&columns='+ cols;
		 jQuery.ajax({
		 type: "POST",
		 url: "index.php?module=LoginPage&parent=Settings&view=Edit",
		 data: dataString,
		 dataType: "html",
		 cache: true,
		 success: function (html) {
			jQuery(".page").html(html);
		 }

		});
		});
		
	},
	applyPage : function(rows,cols) {
		var aDeferred = jQuery.Deferred();
		var params = {};
		params['module'] = 'LoginPage';
		params['parent'] = 'Settings';
		params['action'] = 'Save';
		params['rows'] = rows;
		params['cols'] = cols;
		
		AppConnector.request(params).then(
			function(data) {
				aDeferred.resolve(data);
			}, function(error, err) {
				aDeferred.reject();
			}
		);
		return aDeferred.promise();
	},
	/**
	 * Function to register events
	 */
	registerEvents : function(){
		this.displayPage();
	}
	
});
jQuery(document).ready(function(){
	var settingLoginPageInstance = new Settings_LoginPage_Edit_Js();
	settingLoginPageInstance.registerEvents();
})

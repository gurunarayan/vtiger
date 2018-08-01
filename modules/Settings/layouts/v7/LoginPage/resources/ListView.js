/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Settings_LoginPage_ListView_Js",{
	registerForDeleteTheme : function(){
		jQuery('.deleteRecordButton').on('click',function(e){
			bootbox.confirm("Are you sure delete this theme?",  function(result){ 
				var target = e.currentTarget.closest('tr');
				var themename = jQuery('input.radio',target).attr('data-display');
				if(result == true){
					console.log(themename);
					//Delete Ajax Call
					jQuery.ajax({
						method: "POST",
						url: "index.php",
						  data: { module: "LoginPage", parent: "Settings",view:"ThemeDelete",themename: themename}
					}).done(function(data) {
						window.location.href='index.php?module=LoginPage&parent=Settings&view=List';
					});
				//Ended here
				}			
			});	//Bootbox close
		});
	},
	registerUnselectRadioEvents : function(){
		jQuery('#Themes').find('.radio').on('click',function(e) {
			var target = e.currentTarget.closest('tr');
			var themename = jQuery('input.radio',target).attr('data-display');
			if (this.getAttribute('checked')) {
				jQuery(this).removeAttr('checked');
				jQuery(this).val('');
				//Delete Ajax Call
					jQuery.ajax({
						method: "POST",
						url: "index.php",
						  data: { module: "LoginPage", mode : "0",parent: "Settings",view:"ThemeStatus",themename: themename}
					}).done(function(data) {
						//window.location.href='index.php?module=LoginPage&parent=Settings&view=List';
					});
				//Ended here
			} else {
				jQuery(this).attr('checked', true);
				var disval = jQuery(this).attr('data-display');
				jQuery(this).val(disval);
				//Delete Ajax Call
					jQuery.ajax({
						method: "POST",
						url: "index.php",
						  data: { module: "LoginPage", mode : "1",parent: "Settings",view:"ThemeStatus",themename: themename}
					}).done(function(data) {
						//window.location.href='index.php?module=LoginPage&parent=Settings&view=List';
					});
				//Ended here

			}
		});
	},
	/**
	 * Function to register events
	 */
	registerEvents : function(){
		var instance = this;
		this.registerForDeleteTheme();
		this.registerUnselectRadioEvents();
		 
	}
	
});
jQuery(document).ready(function(){
	var settingCountersInstance = new Settings_LoginPage_ListView_Js();
	settingCountersInstance.registerEvents();
})

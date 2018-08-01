/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Settings_LoginPage_List_Js",{
	displayPage : function() {
		var instance = this;
		/*Body*/
		jQuery(".select_page_view").change(function() {
		  var tdid = jQuery(this).closest('td').attr('id');
		  var dropdownid = jQuery(this).attr('id');
		  var dropdownvalue = jQuery(this).val();
		  if(tdid != '' && dropdownid != '')
			{
				var aDeferred = jQuery.Deferred();
				var params = {};
				params['module'] = 'LoginPage';
				params['parent'] = 'Settings';
				params['action'] = 'SaveAjax';
				params['dropdownid'] = dropdownid;
				params['item'] = dropdownvalue;
				
				AppConnector.request(params).then(
					function(data) {
						jQuery('#div_'+dropdownid).empty();
						jQuery('#div_'+dropdownid).append(data.result.htmldata);
						jQuery('#div_'+dropdownid).append(data.result.content);
					}, function(error, err) {
						 
					}
				);
			}
		});
		/*Header*/
		jQuery("#idSelectHeaderRight").change(function() {
			 
			 var selectHeaderRightItem = jQuery('#idSelectHeaderRight').val();
			 instance.getHtmlCommenFun(selectHeaderRightItem,'idTdRightHeader','rightHeader');
			
		});  
		jQuery("#idSelectHeaderLeft").change(function() {  	  
			var idSelectHeaderLeft = jQuery('#idSelectHeaderLeft').val();
			instance.getHtmlCommenFun(idSelectHeaderLeft,'idTdLeftHeader','leftHeader');
		   
		});
		/*Footer*/
		jQuery("#idSelectFooterRight").change(function() {  
			 var selectFooterRightItem = jQuery('#idSelectFooterRight').val();
			 instance.getHtmlCommenFun(selectFooterRightItem,'idTdRightFooter','rightFooter');
			
		}); 
		jQuery("#idSelectFooterLeft").change(function() {  		 
			 
			var selectFooterLeftItem = jQuery('#idSelectFooterLeft').val();
			instance.getHtmlCommenFun(selectFooterLeftItem,'idTdLeftFooter','leftFooter');
		   
		});
		
		
	},
	getHtmlCommenFun: function (selectTargetItem,idTdTarget,svalue){ 
		 
			if(selectTargetItem != '')
			{ 	
				var aDeferred = jQuery.Deferred();
				var params = {};
				params['module'] = 'LoginPage';
				params['parent'] = 'Settings';
				params['action'] = 'SaveAjax';
				params['dropdownid'] = svalue;
				params['item'] = selectTargetItem;
				
				AppConnector.request(params).then(
					function(data) {  
						 
						jQuery('#'+idTdTarget).html();
						jQuery('#'+idTdTarget).html(data.result.htmldata); 
					}, function(error, err) {
					}
				);
			}
		},
	clickCreate : function(){
		var instance = this;
		jQuery('#create').on('click',function(){
			var rows	=	jQuery('#row').val();
			var columns	=	jQuery('#column').val();
			var url	=	window.location;
			var newurl = url + '&x=' + rows + '&y=' + columns;
			window.location.href=newurl;
		});
	},
	 
	 //To Save Name in Database
	 saveTheme : function(){
		var instance = this;
		jQuery('#save').on('click',function(){
			var name = jQuery('input[name="loginpagename"]').val();
			if(name == ''){
				bootbox.alert("Please give name of Theme");
				return false;
			}
			//manasa
			jQuery.ajax({
				method: "POST",
				url: "index.php",
				  data: { module: "LoginPage", parent: "Settings",view:"ThemeSave",themename: name}
				}).done(function(data) {
					if(data.result == 1){
						bootbox.alert("Same name theme already exists. Please Give different name.");
						return false;
					}else{
						window.location.href='index.php?module=LoginPage&parent=Settings&view=List';
					}
				});		//ended
			});
	},
	registerBeforeSubmit: function(){
		var instance = this;
		jQuery("#LoginPage").on('submit',function(){
				var name = jQuery('input[name="loginpagename"]').val();
				if(name == ''){
					bootbox.alert("Please give name of Theme");
					return false;
				}
		});
	},

	/**
	 * Function to register events
	 */
	registerEvents : function(){
		//this.triggerGenerate();
		this.displayPage();
		this.clickCreate();
		this.saveTheme();
		this.registerBeforeSubmit();
	}
	
});
jQuery(document).ready(function(){
	var settingCountersInstance = new Settings_LoginPage_List_Js();
	settingCountersInstance.registerEvents();
})

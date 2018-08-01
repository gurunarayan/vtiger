/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Edit_Js("PointSale_Settings_Js",{
},{
	
	registerEventToSaveSettings : function () {
        jQuery('#btnSavePOSSettings').on('click', function(e) {
			var checked = jQuery("input[type=checkbox]:checked").length;
			if(!checked) {
				alert("You must check at least one checkbox.");
				return false;
			}
			if(checked && checked >3 ){
				alert("You must check only three checkbox.");
				return false;
			}
			if(checked && checked <3 ){
				alert("You must check atleast three checkbox.");
				return false;
			}
            e.preventDefault();
            var progressIndicatorElement = jQuery.progressIndicator();
            var params={};
            params = jQuery("#formSettings").serializeFormData();
            var selected_modules=[];
            jQuery('input.BoxSelect').each(function() {
                if(jQuery(this).is(':checked')) {
                    selected_modules.push(jQuery(this).val());
                }
            });
            params.selected_modules = selected_modules;
			
			console.log(params);
            AppConnector.request(params).then(
                function(data) {
					console.log(data);
                    progressIndicatorElement.progressIndicator({'mode':'hide'});
                    if(data.success == true){
                        var params = {};
                        params['text'] = 'Settings Saved';
                        //Settings_Vtiger_Index_Js.showMessage(params);
						//params['text'] = 'Settings Saved';
                        Vtiger_Helper_Js.showPnotify(params);
						window.location.href='index.php?module=Vtiger&parent=Settings&view=Index';
                    }
                },
                function(error,err){
                    progressIndicatorElement.progressIndicator({'mode':'hide'});
                }
            );
        });
    },


	registerEvents : function(){
		this.registerEventToSaveSettings();
	},

});

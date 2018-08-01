/* ********************************************************************************
 * The content of this file is subject to the Time Tracker ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */


Vtiger.Class("Settings_OS2TimeTracker_Settings_Js",{
    instance:false,
    getInstance: function(){
        if(Settings_OS2TimeTracker_Settings_Js.instance == false){
            var instance = new Settings_OS2TimeTracker_Settings_Js();
            Settings_OS2TimeTracker_Settings_Js.instance = instance;
            return instance;
        }
        return Settings_OS2TimeTracker_Settings_Js.instance;
    }
},{
    
    registerEventToSaveSettings : function () {
        jQuery('#btnSaveSettings').on('click', function(e) {
            e.preventDefault();
            var progressIndicatorElement = jQuery.progressIndicator();
            var params={};
            params = jQuery("#formSettings").serializeFormData();
            var selected_modules=[];
            jQuery('input.selectedModules').each(function() {
                if(jQuery(this).is(':checked')) {
                    selected_modules.push(jQuery(this).val());
                }
            });
            params.selected_modules = selected_modules;
			
			console.log(params);
            AppConnector.request(params).then(
                function(data) {
                    progressIndicatorElement.progressIndicator({'mode':'hide'});
                    if(data.success == true){
                        var params = {};
                        params['text'] = 'Settings Saved';
                        Settings_Vtiger_Index_Js.showMessage(params);
                    }
                },
                function(error,err){
                    progressIndicatorElement.progressIndicator({'mode':'hide'});
                }
            );
        });
    },

    registerEvents: function(){
        this.registerEventToSaveSettings();
    }
});

/* ********************************************************************************
 * The content of this file is subject to the Login ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */

jQuery.Class("OS2UserSettings_Js",{

        editInstance:false,
        getInstance: function(){
            if(OS2UserSettings_Js.editInstance == false){
                var instance = new OS2UserSettings_Js();
                OS2UserSettings_Js.editInstance = instance;
                return instance;
            }
            return OS2UserSettings_Js.editInstance;
        }
    },
    {
        docheckLogin:function(form){
            form.submit(function(e){
                e.preventDefault();
				e.stopImmediatePropagation();
                var username=$('#username').val();
                var user_pass=$('#password').val();

			    var convertlead_url='index.php?module=OS2UserSettings&action=CheckLogin&username='+username+'&password='+user_pass;
			AppConnector.request(convertlead_url).then(			
				function(res) {  
					if(res) {
						if(res.result.lock=='0') 
						{
							
							// setTimeout(function(){window.location.href='index.php?module=Users&parent=Settings&view=SystemSetup';},200); return;
							//window.location.href='index.php?module=Users&parent=Settings&view=SystemSetup';
							window.location.href='index.php';
						}
						else if(res.result.lock=='1') {
							Vtiger_Helper_Js.showPnotify(res.result.message);
						}else if(res.result.lock=='2') {
							Vtiger_Helper_Js.showPnotify(res.result.message);
                        }else if(res.result.lock=='3') {
							Vtiger_Helper_Js.showPnotify(res.result.message);
                        }else if(res.result.lock=='4') {
							Vtiger_Helper_Js.showPnotify(res.result.message);
                        }
						else Vtiger_Helper_Js.showPnotify("Login username or password wrong.Please try again");
					}else{
						
					}
				},
				function(error,err){
				}
			);
            });
        },

        registerEvents : function() {
            var thisInstance=this;
            var form = jQuery(document).find('form');
            if(form.hasClass('login-form')){
                this.docheckLogin(form);
            }
        }
    });



jQuery(document).ready(function(){

    var instance = new OS2UserSettings_Js();

    instance.registerEvents();


});




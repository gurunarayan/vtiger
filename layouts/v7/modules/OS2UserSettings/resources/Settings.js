jQuery.Class("LoginSecurity_Settings_Js",{
	ToggleAction : function(userid,toggle){
		
		Vtiger_Helper_Js.showConfirmationBox({'message' : 'Are you confirm?'}).then(
			function(e) {
				// Ajax Call
				var params = {};
				params['module'] = "OS2UserSettings";
				params['view'] = 'WidgetController';
				params['action'] = 'ToggleAction';
				params['toggle'] = toggle;
				params['userid'] = userid;
				AppConnector.request(params).then(function(data){
				var returnedData = JSON.parse(data);
				var param = {text:'User Updated'};
                Vtiger_Helper_Js.showMessage(param);
				window.location.reload();
				
				});
				// Ajax Call
			},
			function(error, err) {
				
			}
		);
		
	},
	
	},{
	triggerHourFormatChangeEvent : function(form) {
		this.hourFormatConditionMapping = jQuery('input[name="timeFormatOptions"]',form).data('value');
		this.changeStartHourValuesEvent(form);
		this.changeEndHourValuesEvent(form);
		jQuery('select[name="hour_format"]',form).trigger('change');
	},
	changeStartHourValuesEvent : function(form){
		var thisInstance = this;
		form.on('change','select[name="hour_format"]',function(e){
			var hourFormatVal = jQuery(e.currentTarget).val();
			var startHourElement = jQuery('select[name="login_starts_at"]',form);
			var conditionSelected = startHourElement.val();
			var list = thisInstance.hourFormatConditionMapping['hour_format'][hourFormatVal]['start_hour'];
			var options = '';
			for(var key in list) {
				//IE Browser consider the prototype properties also, it should consider has own properties only.
				if(list.hasOwnProperty(key)) {
					var conditionValue = list[key];
					options += '<option value="'+key+'"';
					if(key == conditionSelected){
						options += ' selected="selected" ';
					}
					options += '>'+conditionValue+'</option>';
				}
			}
			startHourElement.html(options).trigger("liszt:updated");
		});
		
		
	},
	changeEndHourValuesEvent : function(form){
		var thisInstance = this;
		form.on('change','select[name="hour_format"]',function(e){
			var hourFormatVal = jQuery(e.currentTarget).val();
			var startHourElement = jQuery('select[name="login_ends_at"]',form);
			var conditionSelected = startHourElement.val();
			var list = thisInstance.hourFormatConditionMapping['hour_format'][hourFormatVal]['start_hour'];
			var options = '';
			for(var key in list) {
				//IE Browser consider the prototype properties also, it should consider has own properties only.
				if(list.hasOwnProperty(key)) {
					var conditionValue = list[key];
					options += '<option value="'+key+'"';
					if(key == conditionSelected){
						options += ' selected="selected" ';
					}
					options += '>'+conditionValue+'</option>';
				}
			}
			startHourElement.html(options).trigger("liszt:updated");
		});
		
		
	},
	allToggleLinks : function(container){
		var userid=jQuery('#recordId').val();
		
		
		var html='';
		var linkname1='Toggle Status';
		var toggleaction1='ToggleStatus';
		html +='<li><a href="javascript:void(0);" onclick=\'LoginSecurity_Settings_Js.ToggleAction("'+userid+'","'+toggleaction1+'")\'>'+linkname1+'</a></li>';
		
		var linkname2='Toggle Admin';
		var toggleaction2='ToggleAdmin';
		html +='<li><a href="javascript:void(0);" onclick=\'LoginSecurity_Settings_Js.ToggleAction("'+userid+'","'+toggleaction2+'")\'>'+linkname2+'</a></li>';
		container.find('.detailViewButtoncontainer:last-child').find('.dropdown-menu').append(html);
		
		
	},
    // Create Create Project
	registerEvents : function() {
	 // var thisInstance = this;
	  var thisInstance = this;
	  var viewName = app.getViewName();
	  var modulename=app.getModuleName();
	  console.log(modulename+' > '+viewName);
	  if(modulename=='Users' && viewName=='Edit'){
	  var form = jQuery('#EditView');
	  this.triggerHourFormatChangeEvent(form);
	  }
	  if(modulename=='Users' && viewName=='Detail'){
		  var form = jQuery('.detailViewContainer');
		  this.allToggleLinks(form);
	  }
	}
	
})


jQuery(document).ready(function() {
	var instance = new LoginSecurity_Settings_Js();
	instance.registerEvents();
	
	
})
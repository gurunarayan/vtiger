<?php

class OS2UserSettings_CheckLogin_Action extends Vtiger_Action_Controller {
	
	protected $FAILED_LOGINS_CRITERIA_P;	
	protected $MAX_LOGIN_ATTEMPTS_P;
	protected $HOLIDAYS_P;
	protected $UC_NAME_ONE_P;
	protected $UC_EMAIL_ID_ONE_P;
	protected $UC_NAME_TWO_P;
	protected $UC_EMAIL_ID_TWO_P;
	protected $WORKING_HOURS_START_P;
	protected $WORKING_HOURS_END_P;
	protected $WORKING_WEEK_DAYS_P;
	
	protected $USERNAME_P;
	
	
	function checkPermission(Vtiger_Request $request) {
        return true;
    }
	
	function process(Vtiger_Request $request) 
	{
		global $adb,$log;
		require_once('modules/Emails/mail.php');
        require_once 'include/utils/utils.php';
        require_once 'include/utils/VtlibUtils.php';
        require_once 'modules/Emails/class.phpmailer.php';
		//include 'config.user.php';
		
		$sql = $adb->pquery("SELECT * FROM vtiger_user_config WHERE status=1",array());
		$userinfo = $adb->fetch_array($sql);
		
		$failed_logins_criteria = $userinfo["failed_logins_criteria"];
		$max_login_attempts = $userinfo["max_login_attempts"];		
		$UC_NAME_ONE = $userinfo["uc_name_one"];
		$UC_EMAIL_ID_ONE = $userinfo["uc_email_id_one"];
		$UC_NAME_TWO = $userinfo["uc_name_two"];
		$UC_EMAIL_ID_TWO = $userinfo["uc_email_id_two"];
		$Working_Hours_start = $userinfo["working_Hours_start"];
		$Working_Hours_end = $userinfo["working_Hours_end"];

		$working_week_days = $userinfo["weeks"];	 
		$working_week_days = json_decode(stripslashes(html_entity_decode($working_week_days)), true);

		$Holidays = $userinfo["holiday_lbl_val"];
		
		$this->FAILED_LOGINS_CRITERIA_P = $failed_logins_criteria;
		$this->MAX_LOGIN_ATTEMPTS_P = $max_login_attempts;
		$this->HOLIDAYS_P = $Holidays;
		$this->UC_NAME_ONE_P = $UC_NAME_ONE;
		$this->UC_EMAIL_ID_ONE_P = $UC_EMAIL_ID_ONE;
		$this->UC_NAME_TWO_P = $UC_NAME_TWO;
		$this->UC_EMAIL_ID_TWO_P = $UC_EMAIL_ID_TWO;
		$this->WORKING_HOURS_START_P = $Working_Hours_start;
		$this->WORKING_HOURS_END_P = $Working_Hours_end;
		$this->WORKING_WEEK_DAYS_P = $working_week_days;
 		
		
		$db = PearDatabase::getInstance();
		$this->USERNAME_P = $request->get('username');
		$password= $request->get('password');
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$user = CRMEntity::getInstance('Users');
		$user->column_fields['user_name'] = $this->USERNAME_P;
		$response = new Vtiger_Response();
		$response->setEmitType(Vtiger_Response::$EMIT_JSON);
		$user_id = $this->check_username($this->USERNAME_P);
		 
		if ($user_id > 0 && $user_id!=null) 
		{
			if($user->doLogin($password)) 
			{	// username and password were good, we need to enforce the IP and Calendar 
				$userid = $user->retrieve_user_id($this->USERNAME_P);				 
				$timezone  = $this->getTimezoneForUser($userid);
				date_default_timezone_set($timezone);				
				$response = $this->determine_if_user_can_login($userid,$response);
			} else if($user_id > 0) 
			{
				if(in_array($this->FAILED_LOGINS_CRITERIA_P,array(4,5,6,7))){
					$msg = $this->updateLoginFails($user_id,'');
					$response->setResult(array("lock"=>"2","message"=>$msg));						
				}else{
					$msg=" Invalid username or password. ";
					$response->setResult(array("lock"=>"2","message"=>$msg));		 
				}				
			}
			else 
			{
				$response->setResult(array("lock"=>"2","message"=>'Login Failure.'));
			}
		} 
		else 
		{
			$response->setResult(array("lock"=>"2","message"=>'Your login credentials have been revoked. Please contact the System Administrator'));
		}
		$response->emit();
    } // function process

	function determine_if_user_can_login($userid,$response)
	{
		global $adb,$log;	 
		// 0 = No check for failed login
		// 1 = IP Check
		// 2 = Calendar Check
		// 3 = Calendar and IP Check
		// 4 = Password Check
		// 5 = PW and IP Check
		// 6 = PW and Calendar Check
		// 7 = PW, Calendar and IP Check
		 
		switch ($this->FAILED_LOGINS_CRITERIA_P) {
			case 1:
				if(!$this->ip_checking($userid)) 
				{ 			
					$response->setResult(array("lock"=>"1","message"=>"Client IP is blocked. "));
				}else{
					$response = $this->set_session($userid,$response);
				}				 
				break;
			case 2:		
				if(!$this->calendar_check($userid))
				{
					#added updateLoginFails by virat Date :April 2018
					$msg = $this->updateLoginFails($userid,'Login time is wrong / login restricted in holidays or Non Working Days.');
					$response->setResult(array("lock"=>"3","message"=>$msg));
				}
				else
				{
					$response = $this->set_session($userid,$response);
				}				 
				break;
			case 3:		
				if(!$this->ip_checking($userid)) 
					{ 			
						$response->setResult(array("lock"=>"1","message"=>"Client IP is blocked. "));
					}
				elseif(!$this->calendar_check($userid))
					{
						#added updateLoginFails by virat Date :April 2018
						$msg = $this->updateLoginFails($userid,'Login time is wrong / login restricted in holidays or Non Working Days.');
						$response->setResult(array("lock"=>"3","message"=>$msg));
					}
				else
					{
						$response = $this->set_session($userid,$response);
					}					 
				break;
			case 5:				 
				if(!$this->ip_checking($userid)) 
				{ 			
					$msg = $this->updateLoginFails($userid,'Client IP is blocked. ');
					$response->setResult(array("lock"=>"1","message"=>$msg));
				}else{
					$response = $this->set_session($userid,$response);
				}				 
				break;
				 
			case 6:	
				if(!$this->calendar_check($userid))
				{
					$msg = $this->updateLoginFails($userid,'Login time is wrong / login restricted in holidays or Non Working Days.');
					$response->setResult(array("lock"=>"3","message"=>$msg));
				}
				else
				{
					$response = $this->set_session($userid,$response);
				}				 
				break;
			case 7:				 
				if(!$this->ip_checking($userid)) 
					{ 	
						$msg = $this->updateLoginFails($userid,'Client IP is blocked. ');
						$response->setResult(array("lock"=>"1","message"=>$msg));	
					}
				elseif(!$this->calendar_check($userid))
					{
						$msg = $this->updateLoginFails($userid,'Login time is wrong / login restricted in holidays or Non Working Days.');
						$response->setResult(array("lock"=>"3","message"=>$msg));						
					}
				else
					{
						$response = $this->set_session($userid,$response);
					}	
				break;
			case 0:
				$response = $this->set_session($userid,$response);
			break;
			default:
				$response = $this->set_session($userid,$response);	 
		}	 
		return $response;
	} // function determine_if_user_can_login
	
	
	function set_session($userid,$response){
		global $adb,$log;	
		session_regenerate_id(true); 
		Vtiger_Session::set('AUTHUSERID', $userid);
		$_SESSION['authenticated_user_id'] = $userid;
		$_SESSION['app_unique_key'] = vglobal('application_unique_key');
		$_SESSION['authenticated_user_language'] = vglobal('default_language');
		$_SESSION['KCFINDER'] = array(); 
		$_SESSION['KCFINDER']['disabled'] = false; 
		$_SESSION['KCFINDER']['uploadURL'] = "test/upload"; 
		$_SESSION['KCFINDER']['uploadDir'] = "../test/upload";
		$deniedExts = implode(" ", vglobal('upload_badext'));
		$_SESSION['KCFINDER']['deniedExts'] = $deniedExts;
		$moduleModel = Users_Module_Model::getInstance('Users');
		$moduleModel->saveLoginHistory($this->USERNAME_P);
		if(isset($_SESSION['return_params'])){ 
			$return_params = $_SESSION['return_params'];
		}
		$adb->pquery("DELETE FROM vk_login_failed_attempt WHERE userid=?",array($userid));
		#$adb->pquery("UPDATE vk_login_failed_attempt SET total_attempt=?,failed_date_n_time=? WHERE userid=?",array(0,'',$user_id));
		$response->setResult(array("lock"=>"0","message"=>"Login success"));
		return $response;
	}
	
	function ip_checking($userid)
	{
		global $adb,$log;
		$sql= $adb->pquery("SELECT allowed_ips FROM vtiger_users WHERE id=?",array($userid));
		$resultinfo = $adb->fetch_array($sql);
		$allowed_ips=$resultinfo["allowed_ips"];
		if($allowed_ips=="") return true;
		else{
			$allowed_ips_xplode=explode(",",$allowed_ips);
			
			if (sizeof($allowed_ips_xplode)==0) $allowed_ips_xplode=array($allowed_ips);
			$client_ip=$this->get_client_ip();
			//echo $client_ip;
			if(sizeof($allowed_ips_xplode)>1){
				for($i=0;$i<sizeof($allowed_ips_xplode);$i++){
					if($client_ip==$allowed_ips_xplode[$i]){
						return true;
					}
				}
				return false;
			}
			if (in_array($client_ip,$allowed_ips_xplode)) return true;
			else return false;
		}
	} // function ip_checking

	function get_client_ip()
	{
		  $ipaddress = '';
		  if (getenv('HTTP_CLIENT_IP'))
			  $ipaddress = getenv('HTTP_CLIENT_IP');
		  else if(getenv('HTTP_X_FORWARDED_FOR'))
			  $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		  else if(getenv('HTTP_X_FORWARDED'))
			  $ipaddress = getenv('HTTP_X_FORWARDED');
		  else if(getenv('HTTP_FORWARDED_FOR'))
			  $ipaddress = getenv('HTTP_FORWARDED_FOR');
		  else if(getenv('HTTP_FORWARDED'))
			  $ipaddress = getenv('HTTP_FORWARDED');
		  else if(getenv('REMOTE_ADDR'))
			  $ipaddress = getenv('REMOTE_ADDR');
		  else
			  $ipaddress = 'UNKNOWN';
	
		  return $ipaddress;
	} // function get_client_ip

	function calendar_check($userid)
	{		
		global $adb,$log;
		
		$sql= $adb->pquery("SELECT login_starts_at, login_ends_at, login_allow_holiday, login_dow, hour_format FROM vtiger_users WHERE id=?",array($userid));
		$resultinfo = $adb->fetch_array($sql);
		
		$login_allow_holiday=$resultinfo["login_allow_holiday"];
		$login_dow=$resultinfo["login_dow"];
		 
		if($login_dow == ""){
			$login_dow = $this->WORKING_WEEK_DAYS_P;
			$login_dow = implode(',',$login_dow);
		}else{
			$login_dow=explode(' |##| ',$login_dow);
			$login_dow=implode(',',$login_dow);
		}
		
		//time
		$login_starts_at = $resultinfo["login_starts_at"];
		if($login_starts_at == ''){
			$login_starts_at = $this->WORKING_HOURS_START_P;
		}else{
			$login_starts_at = date("H:i", strtotime($login_starts_at));
		}
		
		$login_ends_at = $resultinfo["login_ends_at"];
		if($login_ends_at == ''){
			$login_ends_at = $this->WORKING_HOURS_END_P;
		}else{
			$login_ends_at = date("H:i", strtotime($login_ends_at));
		}
		 
		return $this->dow_check($login_dow) && $this->holiday_check($login_allow_holiday) && $this->tod_check($login_starts_at, $login_ends_at);
		 
		
	} // function calendar_check

	function dow_check($allowedon){
		global $log;
		 
 		if (strpos($allowedon,date('l')) !== false){
			return true;
		}else{
			return false;
		}
	} // function dow_check

	function holiday_check($allow){
		$date = date("d")."-".date("m");
		 
		if ($allow){
			 
			return true;

		} else 	{
			$holidays = $this->HOLIDAYS_P;
			$holidays = json_decode(stripslashes(html_entity_decode($holidays)), true);
			 
			$holiday = array();
		 
			foreach($holidays as $fname=>$fdate){				 
				$dt = new DateTime($fdate); 
				$holiday[] = $dt->format('d-m');
			}		 
			  
			if(in_array($date, $holiday)){
				return false;
			}else{
				return true;
			}
		}
			
	} // function holiday_check

	function tod_check($starts_at, $ends_at)
	{
		if ($starts_at=="" || $starts_at=="00:00" || $ends_at=="" || $ends_at=="00:00") return true;

		$loginTime = strtotime(date("H:i"));
		$fromTime = strtotime($starts_at);
		$toTime = strtotime($ends_at);

		if ($toTime > $fromTime)	// same day shift
		{
			if ($loginTime > $fromTime && $loginTime < $toTime){
				return true;
			} else {
				return false;
			}
		} else {					// midnight crossover
			if ($loginTime > $toTime && $loginTime < $fromTime){
				return false;
			} else {
				return true;
			}
		}
	} // function tod_check

	function loginRequired() 
	{
		return false;
	} // function loginRequired

	function check_username()
	{
		global $adb;
		$sql= $adb->pquery("SELECT id FROM vtiger_users WHERE user_name=? AND status=? AND deleted=?",array($this->USERNAME_P,"Active",0));
		if($adb->num_rows($sql)>0) 
		{
			$resultinfo = $adb->fetch_array($sql);
			return $resultinfo["id"];
		}
		else return 0;
	} // function check_username

	function failed_attempt($userid)
	{
		global $adb;
		$sql= $adb->pquery("SELECT total_attempt FROM vk_login_failed_attempt WHERE userid=?",array($userid));
		if($adb->num_rows($sql)>0) return false;
		else return true;
	} # function failed_attempt
	
	function getTimezoneForUser($userid){
			global $adb;
		$sql= $adb->pquery("SELECT time_zone FROM `vtiger_users` where id = ?",array($userid));
		$timezone = $adb->fetch_array($sql);
		return $timezone["time_zone"];
	}
	
	function updateLoginFails($user_id,$addMassage = '') { 
		 
		global $adb;
		$atmpt_cnt=1;
		 
		$current_dt=date("Y-m-d H:i:s"); 
		$sql= $adb->pquery("SELECT * FROM vk_login_failed_attempt WHERE userid=?",array($user_id));
		if ($adb->num_rows($sql) > 0) 
		{
			$resultinfo = $adb->fetch_array($sql);
			 
			$total_attempt=$resultinfo["total_attempt"];
			$atmpt_cnt=$total_attempt+1;
			# changed <= to < by virat because it is coming 6 in the wrong attempts;  Date :April 2018
			if($atmpt_cnt < $this->MAX_LOGIN_ATTEMPTS_P){
				$adb->pquery("UPDATE vk_login_failed_attempt SET total_attempt=?,failed_date_n_time=? WHERE userid=?",array($atmpt_cnt,$current_dt,$user_id));
			}
		} else {
			$adb->pquery("INSERT INTO vk_login_failed_attempt SET total_attempt=?,failed_date_n_time=?,userid=?",array($atmpt_cnt,$current_dt,$user_id));
		}
		if ($this->MAX_LOGIN_ATTEMPTS_P == $atmpt_cnt )
		{
			# Make user inactive
			$f = new Users();
			$f->retrieve_entity_info($user_id, "Users");
			$f->column_fields['status'] = 'Inactive';
			$f->mode='edit';
			$f->id=$user_id;
			$f->save("Users",$user_id); 
 
			$result = $adb->pquery('select email1,first_name,last_name from vtiger_users where id= ? ', array($user_id));
			if ($adb->num_rows($result) > 0) 
			{
				$email = $adb->query_result($result, 0, 'email1');
				$first_name = $adb->query_result($result, 0, 'first_name');
				$last_name = $adb->query_result($result, 0, 'last_name');
				$content = 'Dear '.$last_name.' '.$first_name.',<br><br> 
							Your CRM Account   has login failure from the following details  .<br> 
							<br><br> Date: ' . $current_dt . '<br>Number of attempts: '. $atmpt_cnt.'<br>IP: '. $this->get_client_ip(). '<br><br>Note:Please contact your Administrator for Activation of your Account<br><br>
							Regards,<br> 
							CRM Team.<br>' ;
				$mail = new PHPMailer();
				$query = "select from_email_field,server_username from vtiger_systems where server_type=?";
				$params = array('email');
				$result = $adb->pquery($query,$params);
				$from = $adb->query_result($result,0,'from_email_field');
				if ($from == '') { $from =$adb->query_result($result,0,'server_username'); }
				$subject='Alert : Login Failure - CRM';
				setMailerProperties($mail,$subject, $content, $from, $this->USERNAME_P, $email);
				if(isset($this->UC_EMAIL_ID_ONE_P) && $this->UC_EMAIL_ID_ONE_P !=''){
					setCCAddress($mail,'cc',$this->UC_EMAIL_ID_ONE_P);
				}
				if(isset($this->UC_EMAIL_ID_TWO_P) && $this->UC_EMAIL_ID_TWO_P !=''){ 
					setCCAddress($mail,'bcc',$this->UC_EMAIL_ID_TWO_P);
				}
				$status = MailSend($mail);
			}
		}
		
		$msg = $addMassage ."You have made $atmpt_cnt unsuccessful attempt(s).\nThe maximum retry attempts allowed for login are ".$this->MAX_LOGIN_ATTEMPTS_P;
		 
		return $msg;
	}
	
	
}  

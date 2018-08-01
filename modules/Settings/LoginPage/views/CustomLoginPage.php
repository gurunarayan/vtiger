<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_LoginPage_CustomLoginPage_View extends Settings_Vtiger_Index_View {

	function __construct() {
		parent::__construct();
	}

	public function process(Vtiger_Request $request) {
		$viewer = $this->getViewer($request);
		$rows	=	$request->get('x');
		$columns	=	$request->get('y');

		if(file_exists($_FILES['logo']['tmp_name'])) 
		{
			$logo = $this->uploadImage('logo');
		}
	 
		$finaldata = array('x'=>$rows,'y'=>$columns,'logo' => $logo);
		for($x=1;$x<=$rows;$x++)
		{
			for($y=1;$y<=$columns;$y++)
				{
					$finaldata['Data'][$x.$y] = $this->generateData($request,$x.$y);
				}
		}		
	 
		if($request->get('footer')){
			$finaldata['footer'] = array(
				'left'=> array(
								'type' => $request->get('select_footer_left'),
								'data' => $this->getFootData($request->get('select_footer_left'),$request,'leftFooter') ),
				'right'=> array(
								'type'=> $request->get('select_footer_right'),
								'data'=> $this->getFootData($request->get('select_footer_right'),$request,'rightFooter') )
								
			); 
		}
		 
		$this->GenerateLoginPage($finaldata,$request);
		//echo '<pre>';print_r($finaldata);die;
		$name = $request->get('loginpagename');
		header('Location:index.php?module=LoginPage&parent=Settings&view=Preview&name='.$name);
		
		
	}

	function uploadImage($filename){
		
		$target_dir = "loginimages/";
		if(!is_dir($target_dir)){ mkdir($target_dir,0777); }
		
		$target_file = $target_dir . basename($_FILES[$filename]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES[$filename]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
		}
		// Check if file already exists
		if (file_exists($target_file)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES[$filename]["size"] > 10240000) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "bmp" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG,bmp & GIF files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			$temp = explode(".", $_FILES[$filename]["name"]);
			$newfilename = $temp[0].'_'.round(microtime(true)) . '.' . end($temp);			
			if(move_uploaded_file($_FILES[$filename]["tmp_name"], $target_dir . $newfilename)) {
				return $target_dir . $newfilename;
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}

	function generateData($request,$xy)
	{
		$selecteditem = $request->get($xy);
		switch ($selecteditem) {
			case LoginBox:
				return array('type' => $selecteditem,'data' => array('loginbox_title'=> $request->get('loginbox_title'),'loginbox_sub_title'=> $request->get('loginbox_sub_title'),
				'loginbox_font_color'=> $request->get('loginbox_font_color')));
				break;
			case ImageSlider:
				$imgdata = $this->uploadImages('sliderimages_'.$xy);				
				return array('type' => $selecteditem,'data'=>$imgdata);
				break;			 
			 
			default:
				return array();
			}
	}

	function uploadImages($filename)
	{
		$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
		$max_file_size = 1024*10000; //100 kb
		$path = "loginimages/"; // Upload directory
		if(!is_dir($path)){ mkdir($path,0777); }
		$imagesName = array();
		foreach ($_FILES[$filename]['name'] as $f => $name) {     
			if ($_FILES[$filename]['error'][$f] == 4) {
				continue; // Skip file if any error found
			}	       
			if ($_FILES[$filename]['error'][$f] == 0) {	           
				if ($_FILES[$filename]['size'][$f] > $max_file_size) {
					$message[] = "$name is too large!.";
					continue; // Skip large files
				}
				elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
					$message[] = "$name is not a valid format";
					continue; // Skip invalid file formats
				}
				else{ // No error found! Move uploaded files 
					$temp = explode(".", $_FILES[$filename]["name"][$f]);
					$newfilename = $temp[0].'_'.round(microtime(true)) . '.' . end($temp);		
					if(move_uploaded_file($_FILES[$filename]["tmp_name"][$f], $path . $newfilename)) {
					//if(move_uploaded_file($_FILES[$filename]["tmp_name"][$f], $path.$name)){ 
						$imagesName[] = $path.$newfilename;
					}
					
				}
			}
		}
		return $imagesName;
	}

	public function getFootData($selecteditem,$request,$position){
		 
		switch ($selecteditem) {
			case Content: 				 
				return $request->get('Content_'.$position);
				break;
			case socialicons:				 			
				return array(
							'facebook'=>$request->get('si_facebook_'.$position),
							'twitter'=>$request->get('si_twitter_'.$position),
							'linkedin'=>$request->get('si_linkedin_'.$position)
							);
				break;
			case WebsiteLinks:
				return array(
							$request->get('wlTitle1_'.$position) => $request->get('wlUrl1_'.$position),
							$request->get('wlTitle2_'.$position) => $request->get('wlUrl2_'.$position),
							$request->get('wlTitle3_'.$position) => $request->get('wlUrl3_'.$position)
							);
				break;
			default:
				return array();
			}
	}

	function GenerateLoginPage($filedata,$request)
	{
		$rows = $filedata['x'];
		$columns = $filedata['y'];
		$header = $this->getHeaderScript();
		 
		$footer = $this->getFooterScript();

		$temp_file = 'layouts/vlayout/modules/Settings/LoginPage/';
		$mainPath = 'layouts/vlayout/modules/Users/';
		$filename = 'Login.Custom.tpl';
	 
		chmod($temp_file.$filename, 0777);
		$loginpagename = $request->get('loginpagename');//manasa
		if(file_exists($temp_file.$filename)){
			#rename(oldname,newname,context)
			rename($temp_file.$filename, $temp_file.$loginpagename.'.tpl');
		}
		$file = $temp_file.$filename;
		#write content on file  

		if (file_exists($file)) {
		  $fh = fopen($file, 'a');
		} else {
		  $fh = fopen($file, 'w');
		} 			
		fwrite($fh, $header); 

		#write logo
		$logoHtml = $this->getLogoHtmls($filedata['logo']);
		fwrite($fh, $logoHtml);
		fwrite($fh, '<div class="row-fluid">'); 
		for($x=1;$x<=$rows;$x++)
		{	 
			for($y=1;$y<=$columns;$y++)
			{	 
				$body = $this->getHtmls($filedata,"$x$y");
				fwrite($fh, $body);			  
			} 			 
		} 
		 
		fwrite($fh, '</div>'); 
		fwrite($fh, $footer);
		fclose($fh);
		 
	}
	function getHeaderScript(){
		return file_get_contents('layouts/vlayout/modules/Settings/LoginPage/header.tpl', FILE_USE_INCLUDE_PATH);			
	}
	
	 function getFooterScript(){
		return file_get_contents('layouts/vlayout/modules/Settings/LoginPage/footer.tpl', FILE_USE_INCLUDE_PATH);	
	}

	function getLogoHtmls($logo){

		return '<div class="logo"><img src="'.$logo.'" /></div>';
	}

	function getHtmls($model,$position)
	{
		$f = $model['Data'][$position];
		$footerdata = $model['footer'];
		$mtype = $f['type'];
		$mdata = $f['data'];
		
		switch ($mtype) {
			case LoginBox:
				return $this->loginPageHtml($mdata,$footerdata);
				break;
			case ImageSlider:
				return $this->generateImageSliderHtml($mdata);
				break; 
			default:
				return '';
			}
		
	} 

	function loginPageHtml($data,$footerdata){

		$title = ( $data['loginbox_title'] == "")?"Login":$data['loginbox_title'];
		$sub_title = ( $data['loginbox_sub_title'] == "")?"":$data['loginbox_sub_title'];
		$color  = ($data['loginbox_font_color'] == '')?'black':$data['loginbox_font_color'];

		$html =  '<style> .login-header, .subtitle { color:'.$color.'; } .signin-button .btn { border-color:'.$color.'; background-color:'.$color.'; } .login-more-info .copy-right small{  color: '.$color.' } h3.forgot-password {  color: '.$color.'  !important;  }</style>
				<div class="span6 login-area">
				<div class="span12 site-info">
				<h1 class="login-header">'.$title.'</h1>
				<p class="subtitle">'.$sub_title.'</p>
				</div>
				<div class="span12 login-box" id="loginDiv">
				<form class="form-horizontal login-form" action="index.php?module=Users&action=Login" method="POST">
					 
					<div class="row">
						<div class="span6 username"><input type="text" id="username" name="username" placeholder="Username" value=""></div>
						<div class="span6 password"><input type="password" id="password" name="password" placeholder="Password" value=""></div>
					</div>
					<div class="row control-group signin-button pull-right">
						<div class="span12" id="forgotPassword"><a>Forgot Password ?</a>&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-primary sbutton">Sign in</button></div>
					</div>
				</form>
				</div>
				<div class="span12 login-box hide" id="forgotPasswordDiv">
				<form class="form-horizontal login-form" action="forgotPassword.php" method="POST">
					 
					<div class="row">
						<h3 class="forgot-password">Forgot Password</h3>
					</div>
					<div class="row">
						<div class="span6 username"><input type="text" id="user_name" name="user_name" placeholder="Username"></div>
						<div class="span6 password"><input type="text" id="emailId" name="emailId"  placeholder="Email"></div>
					</div>
					<div class="row control-group signin-button">
						<div class="" id="backButton"><input type="button" class="btn btn-back sbutton pull-left" value="Back" style="color:white;"><input type="submit" class="btn btn-primary sbutton pull-right" value="Submit" name="retrievePassword"></div>
					</div>
				</form>
				</div>
				<div class="span12 login-more-info vte-user-login">
				<div class="row">';

		$footerData = $this->generateFooterHtmls($footerdata);
		 
				
	$html .=	$footerData;
	$html .= '</div>
				</div>
			</div>';
	return $html;
		
	}

	function generateFooterHtmls($data)
	{ 

		$html = '';
			foreach($data as $key => $value)
			{
				$c = $value['data'];
				$t = ($key == 'left')?'':'copy-right';
				
				switch ($value['type']) 
				{
					case Content: 						
						$html .='<div class="span6 '.$t.'" ><small>'.$c.'</small></div>';
						break;
					case socialicons:	
						$fb = $c['facebook'];
						$twitter = $c['twitter'];
						$linkedin = $c['linkedin'];
						 
						$html .='<div class="span4 social" >
						<a class="social" target="_blank" href="'.$twitter.'"><i class="icon-social-twitter icons"></i></a>
						<a class="social" target="_blank" href="'.$linkedin.'"><i class="icon-social-linkedin icons"></i></a>
						<a class="social" target="_blank" href="'.$fb.'"><i class="icon-social-facebook icons"></i></a>
								</div>';
						break;
					 
					default:
						$html .='';
				}
			} 
		return $html;
	} 
	
	function generateImageSliderHtml($data){
		$html = '<div class="span6 slideshow">
						<div class="carousal-container">
							<ul class="bxslider">';
		foreach($data as $key=>$image){
			$html .='<li> <div id="slide'.$key.'" class="slide"><img class="pull-left" src="'.$image.'"></div></li>';
		}
		$html .= 	'</ul>
				</div>
			</div>';
		
		
		 
		return $html;
	}
	#end  
	
	
}

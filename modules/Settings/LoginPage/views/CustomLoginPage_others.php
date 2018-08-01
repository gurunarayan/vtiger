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
		if(file_exists($_FILES['backgroundimage']['tmp_name'])) 
		{
			$backgroundimage = $this->uploadImage('backgroundimage');
		}
		//echo '<pre>';print_r($request);die;
		$finaldata = array('x'=>$rows,'y'=>$columns,'backgroundimage' => $backgroundimage);
		for($x=1;$x<=$rows;$x++)
		{
			for($y=1;$y<=$columns;$y++)
				{
					$finaldata['Data'][$x.$y] = $this->generateData($request,$x.$y);
				}
		}		
		//footer
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
		//header
		if($request->get('header')){
			$finaldata['header'] = array(
				'left'=> array(
								'type' => $request->get('select_header_left'),
								'data' => $this->getHeaderData($request->get('select_header_left'),$request,'leftHeader') ),
				'right'=> array(
								'type'=> $request->get('select_header_right'),
								'data'=> $this->getHeaderData($request->get('select_header_right'),$request,'rightHeader') )
								
			); 
		}
		$this->GenerateLoginPage($finaldata,$request);
		$name = $request->get('loginpagename');
		header('Location:index.php?module=LoginPage&parent=Settings&view=Preview&name='.$name);
		
		//echo '<pre>';print_r($finaldata);
	}
	
	public function getHeaderData($selecteditem,$request,$position){
		 
		switch ($selecteditem) {
			case Logo: 					 	
				return  $this->uploadImage('logo_'.$position);	
				break;
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
	
	
	function generateData($request,$xy)
	{
		$selecteditem = $request->get($xy);
		switch ($selecteditem) {
			case LoginBox:
				return array('type' => $selecteditem,'data' => array('loginbox_title'=> $request->get('loginbox_title'),
				'loginbox_font_color'=> $request->get('loginbox_font_color')));
				break;
			case ImageSlider:
				$imgdata = $this->uploadImages('sliderimages_'.$xy);				
				return array('type' => $selecteditem,'data'=>$imgdata);
				break;			 
			case Content: 				 
				return array('type' => $selecteditem,'data' => $request->get('Content_'.$xy));
				break;
			case Image:
				$imgdata = $this->uploadImage('image_'.$xy);				
				return array('type' => $selecteditem,'data'=>$imgdata);
				break;
			case Logo:
				$imgdata = $this->uploadImage('logo_'.$xy);				
				return array('type' => $selecteditem,'data'=>$imgdata);
				break;
			case socialicons:				 			
				return array('type' => $selecteditem,'data'=>array(
								'facebook'=>$request->get('si_facebook_'.$xy),
								'twitter'=>$request->get('si_twitter_'.$xy),
								'linkedin'=>$request->get('si_linkedin_'.$xy)
								));
				break;
			case WebsiteLinks:
				return array('type' => $selecteditem,'data'=>array(
								$request->get('wlTitle1_'.$xy) => $request->get('wlUrl1_'.$xy),
								$request->get('wlTitle2_'.$xy) => $request->get('wlUrl2_'.$xy),
								$request->get('wlTitle3_'.$xy) => $request->get('wlUrl3_'.$xy)
								));
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
		if(!is_dir($path)){ mkdir($path); }
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
	
	function uploadImage($filename){
		
		$target_dir = "loginimages/";
		if(!is_dir($target_dir)){ mkdir($target_dir); }
		
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
			//rename(oldname,newname,context)
			 rename($temp_file.$filename, $temp_file.$loginpagename.'.tpl');
		}
		$file = $temp_file.$filename;//.'.tpl'.;
		//write content on file  

		if (file_exists($file)) {
		  $fh = fopen($file, 'a');
		} else {
		  $fh = fopen($file, 'w');
		} 			
		fwrite($fh, $header);
		
		if($filedata['backgroundimage'] != ''){ 
			$background_image = $filedata['backgroundimage'];
			fwrite($fh,'<style>body {
							background-image: url("'.$background_image.'");
							background-color: #cccccc;
						}</style>');
		}
		if($filedata['header']){
			$headerData = $this->generateHeaderHtmls($filedata['header']);
			fwrite($fh, $headerData);
		}
		for($x=1;$x<=$rows;$x++)
		{	
			//$body .= '<div class="row-fluid">';
			fwrite($fh, '<div class="row-fluid">' );
			for($y=1;$y<=$columns;$y++)
			{				
				//$body .= '<div class="span'.(12/$x).'">';
				$span_no = 12/$columns;
				fwrite($fh, '<div class="span'.$span_no.'" style="padding: 2px;">');
				$body = $this->getHtmls($filedata['Data'][$x.$y]);
				fwrite($fh, $body);
				//$body .= '</div>';
				fwrite($fh, '</div>');
			}
			//$body .= '</div>';
			fwrite($fh, '</div>');
		}
		
		if($filedata['footer']){
			$footerData = $this->generateFooterHtmls($filedata['footer']);
			fwrite($fh, $footerData);
		}
		
		
		
		
		fwrite($fh, $footer);
		fclose($fh);
		//echo $body;
	}
	
	function getHtmls($model)
	{
		$mtype = $model['type'];
		$mdata = $model['data'];
		
		switch ($mtype) {
			case LoginBox:
				return $this->loginPageHtml($mdata);
				break;
			case ImageSlider:
				return $this->generateImageSliderHtml($mdata);
				break;			 
			case Content: 				 
				return '<p>'.$mdata.'</p>';
				break;
			case Image:
				return '<img src="'.$mdata.'" class="">';
				break;
			case Logo:
				return '<div class="logo"><img src="'.$mdata.'">
						<br />
						<a target="_blank" href="http://{$COMPANY_DETAILSCOMPANY_DETAILS.website}">{$COMPANY_DETAILS.name}</a>
					</div>';
				break;
			case socialicons:	 
				return $this->getSocialLinksHtml($mdata);
				break;
			case WebsiteLinks:
				return $this->getWebsiteLinksHtml($mdata);
				break;
			default:
				return '';
			}
		
	} 
	
	 function getHeaderScript(){
		return file_get_contents('layouts/vlayout/modules/Settings/LoginPage/header.tpl', FILE_USE_INCLUDE_PATH);			
	}
	
	 function getFooterScript(){
		return file_get_contents('layouts/vlayout/modules/Settings/LoginPage/footer.tpl', FILE_USE_INCLUDE_PATH);	
	}
	
	 function generateImageSliderHtml($data){
		$html = ' 
		<div class="carousal-container">	 
		<ul class="bxslider">';
		foreach($data as $key=>$image){
			$html .='<li>
						<div id="slide'.$key.'" class="slide">
							<img class="pull-left"  src="'.$image.'">
							
						</div>
					</li>';
		}
		
		$html .= '</ul>	 </div>';
		return $html;
	}
	
	 function getSocialLinksHtml($data){
		$html = '';
		$surls = array(
				'facebook'=>array('url'=>'https://www.facebook.com/','imglink'=>'layouts/vlayout/skins/images/facebook.png'),
				'twitter'=>array('url'=>'https://twitter.com/','imglink'=>'layouts/vlayout/skins/images/twitter.png'),
				'linkedin'=>array('url'=>'https://www.linkedin.com/','imglink'=>'ns/images/linkedin.png')
				);			 
		
		foreach($data as $sname => $url){
			if($name == 'facebook' && $url != ''){
				$html .= '<a href="'.$surls[$sname]['url'].$url.'"><img src="'.$urls[$sname]['imglink'].'" name="fb_icon"></a>';
			}
		}
		return $html;	
	}
	
	 function getWebsiteLinksHtml($data){
		$html = '';
		foreach($data as $sname => $url){
			if($sname != ''){
				$html .= '<a href="'.$url.'">'.$sname.'</a>';
			}
			
		}
		return $html;							
	}
	 function loginPageHtml($data){
		$name = ( $data['loginbox_title'] == "")?"Login":$data['loginbox_title'];
		$color  = ($data['loginbox_font_color'] == '')?'black':$data['loginbox_font_color'];
		return '<style>.login-area .login-header, label, a { color:'.$color.'; }</style>
				<div class="login-area">
					<div class="" id="loginDiv" style="padding: 6px 0 0 0;">
						 <div class="">
								<h3 class="login-header">'.$name.'</h3>
							</div>
						<form class="form-horizontal login-form" style="margin:0;" action="index.php?module=Users&action=Login" method="POST">
							{if isset($smarty.request.error)}
							<div class="alert alert-error">
								<p>Invalid username or password.</p>
							</div>
							{/if}
							{if isset($smarty.request.fpError)}
								<div class="alert alert-error">
									<p>Invalid Username or Email address.</p>
								</div>
							{/if}
							{if isset($smarty.request.status)}
								<div class="alert alert-success">
									<p>Mail has been sent to your inbox, please check your e-mail.</p>
								</div>
							{/if}
							{if isset($smarty.request.statusError)}
								<div class="alert alert-error">
									<p>Outgoing mail server was not configured.</p>
								</div>
							{/if}
							<div class="control-group">
								<label class="control-label" for="username"><b>User name</b></label>
								<div class="controls">
									<input type="text" id="username" name="username" placeholder="Username">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="password"><b>Password</b></label>
								<div class="controls">
									<input type="password" id="password" name="password" placeholder="Password">
								</div>
							</div>
							<div class="control-group signin-button">
								<div class="controls" id="forgotPassword">
									<button type="submit" class="btn btn-primary sbutton">Sign in</button>
									&nbsp;&nbsp;&nbsp;<a>Forgot Password ?</a>
								</div>
							</div>
							{* Retain this tracker to help us get usage details *}
							<img src="//stats.vtiger.com/stats.php?uid={$APPUNIQUEKEY}&v={$CURRENT_VERSION}&type=U" alt="" title="" border=0 width="1px" height="1px">
						</form>
						 
					</div>
					<div class="hide" id="forgotPasswordDiv">
						<form class="form-horizontal login-form" style="margin:0;" action="forgotPassword.php" method="POST">
							<div class="">
								<h3 class="login-header">Forgot Password</h3>
							</div>
							<div class="control-group">
								<label class="control-label" for="user_name"><b>User name</b></label>
								<div class="controls">
								<input type="text" id="user_name" name="user_name" placeholder="Username">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="email"><b>Email</b></label>
								<div class="controls">
								<input type="text" id="emailId" name="emailId"  placeholder="Email">
								</div>
							</div>
							<div class="control-group signin-button">
								<div class="controls" id="backButton">
									<input type="submit" class="btn btn-primary sbutton" value="Submit" name="retrievePassword">
									&nbsp;&nbsp;&nbsp;<a>Back</a>
								</div>
							</div>
						</form>
					</div>
				</div>';
		
	}
	
	function generateFooterHtmls($data)
	{
				
		$html = '<style> .footer-content a { color : black; }</style><div class="navbar navbar-fixed-bottom">
					<div class="navbar-inner">
						<div class="container-fluid">
							<div class="row-fluid">';
			foreach($data as $key => $value)
			{
				$c = $value['data'];
				$t = ($key == 'left')?'pull-left':'pull-right';
				
				switch ($value['type']) 
				{
					case Content: 						
						$html .='<div class="span6 '.$t.'" >
									<div class="footer-content">
										<small>'.$c.'</small>
									</div>
								</div>
								';
						break;
					case socialicons:	
						$fb = $c['facebook'];
						$twitter = $c['twitter'];
						$linkedin = $c['linkedin'];
						 
						$html .='<div class="span6 '.$t.'" >
									<div class="'.$t.' footer-icons">
										<a href="https://www.facebook.com/$fb"><img src="layouts/vlayout/skins/images/facebook.png"></a>
										&nbsp;
										<a href="https://twitter.com/$twitter"><img src="layouts/vlayout/skins/images/twitter.png"></a>
										&nbsp;
										<a href="https://www.linkedin.com/$linkedin"><img src="layouts/vlayout/skins/images/linkedin.png"></a>
										&nbsp;
										<a href="http://www.youtube.com/user/vtigercrm"><img src="layouts/vlayout/skins/images/youtube.png"></a> 
									</div>
								</div>';
						break;
					case WebsiteLinks:
						 
						$html .='<div class="span6 '.$t.'" >
									<div class="footer-content">';
						foreach($c as $title=>$turl) {
						
						$html .='<a href="'.$turl.'">'.$title.'</a> | ';	
						
						}
										 
							$html .='		</div>
								</div>
								';
						break;
					default:
						$html .='';
				}
			}
			$html .='</div>   
								</div>    
							</div>   
						</div>';
			
		 
		
		return $html;
	}
	function generateHeaderHtmls($data)
	{
				
		$html = '<div class="row-fluid">';
					
		if($data['left']['type'] == 'Logo' && $data['left']['data'] != ''){
			$logo = $data['left']['data'];
			$html .='<div class="span3">
					<div class="logo"><img src="'.$logo.'">
					<br /><a target="_blank" href="http://{$COMPANY_DETAILSCOMPANY_DETAILS.website}">{$COMPANY_DETAILS.name}</a>
					</div>
				</div>';
		}
		
		if($data['right']['data'] != '')
		{
			switch ($data['right']['type']) 
				{
					case socialicons:	
						$fb = $c['facebook'];
						$twitter = $c['twitter'];
						$linkedin = $c['linkedin'];
						 
						$html .='<div class="span9" >
									<div class="helpLinks">
										<a href="https://www.facebook.com/$fb"><img src="layouts/vlayout/skins/images/facebook.png"></a>
										&nbsp;
										<a href="https://twitter.com/$twitter"><img src="layouts/vlayout/skins/images/twitter.png"></a>
										&nbsp;
										<a href="https://www.linkedin.com/$linkedin"><img src="layouts/vlayout/skins/images/linkedin.png"></a>
										&nbsp;
										<a href="http://www.youtube.com/user/vtigercrm"><img src="layouts/vlayout/skins/images/youtube.png"></a> 
									</div>
								</div>';
						break;
					case WebsiteLinks:
						 
						$html .='<div class="span9" >
									<div class="helpLinks">';
						foreach($data['right']['data'] as $title=>$turl) {
						
						$html .='<a href="'.$turl.'">'.$title.'</a> | ';	
						
						}
										 
							$html .='		</div>
								</div>
								';
						break;
					default:
						$html .='';
				}
		}
			
			
			 
				
			 
			$html .='</div>';
			
		 
		
		return $html;
	}
	
	
}

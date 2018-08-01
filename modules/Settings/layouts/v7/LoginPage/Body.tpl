{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
{strip}
<!DOCTYPE html>
<html>
	<head>
		<title>Vtiger login page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- for Login page we are added -->
		<link href="libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="libraries/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
		<link href="libraries/bootstrap/css/jqueryBxslider.css" rel="stylesheet" />
		<script src="libraries/jquery/jquery.min.js"></script>
		<script src="libraries/jquery/boxslider/jqueryBxslider.js"></script>
		<script src="libraries/jquery/boxslider/respond.min.js"></script>
		<script>
			jQuery(document).ready(function(){
				scrollx = jQuery(window).outerWidth();
				window.scrollTo(scrollx,0);
				slider = jQuery('.bxslider').bxSlider({
				auto: true,
				pause: 4000,
				randomStart : true,
				autoHover: true
			});
			jQuery('.bx-prev, .bx-next, .bx-pager-item').live('click',function(){ slider.startAuto(); });
			}); 
		</script>
		<style>
		.bx-wrapper, .bx-viewport {
			height: 200px !important; 
		}
		.bx-wrapper .bx-next {
			right: 6px;
		}
		.bx-wrapper .bx-prev {
			left: -6px;
		}
		.slide {
			padding: 0%;
		}
		</style>
	</head>
	<body>
		<div class="container-fluid login-container">
		
		
			<!-- Row1 starts-->
			<div class="row-fluid">
				<!-- Section1 Starts-->
				<div class="span4">
					<div class="logo"><img src="layouts/vlayout/skins/images/logo.png">
						<br />
						<a target="_blank" href="http://{$COMPANY_DETAILSCOMPANY_DETAILS.website}">{$COMPANY_DETAILS.name}</a>
					</div>
				</div>
				<!-- Section1 Ends-->
				
				<!-- Section2 Starts-->
				<div class="span4">
				
				</div>
				<!-- Section2 Ends-->
				
				<!-- Section3 Starts-->
				<div class="span4">
						
				</div>
				<!-- Section3 Ends-->
			</div>
			<!-- Row1 Ends -->
			
			<!-- Row2 starts-->
			<div class="row-fluid">
				<!-- Section1 Starts-->
				<div class="span4"></div>
				<!-- Section1 Ends-->
				
				<!-- Section2 Starts-->
				<div class="span4">
					<div class="login-area" style="margin-left: -36px;margin-top:0%;">
						<div class="login-box" id="loginDiv" style="padding: 6px 0 0 0;">
							<div class=""><h3 class="login-header">Login to Vtiger CRM</h3></div>
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
								<img src='//stats.vtiger.com/stats.php?uid={$APPUNIQUEKEY}&v={$CURRENT_VERSION}&type=U' alt='' title='' border=0 width='1px' height='1px'>
							</form>
							<div class="login-subscript" style="margin:-10px 20px 0 0">
								<small> Powered by vtiger CRM {$CURRENT_VERSION}</small>
							</div>
						</div>
						<div class="login-box hide" id="forgotPasswordDiv">
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
					</div>
					</div>
				</div>
				<!-- Section2 Ends-->
				
				<!-- Section3 Starts-->
				<div class="span4"></div>
				<!-- Section3 Ends-->
				
				<!-- Row2 Ends-->
				
				<!-- Row3 starts-->
			<div class="row-fluid">
				
				<!-- Section1 Starts-->
				<div class="span4">
					<div class="carousal-container" style="width:380px;">
						<div><h2> Get more out of Vtiger </h2></div>
						<ul class="bxslider">
								<li>
									<div id="slide01" class="slide">
										<img class="" src="layouts/images/Image_from_Skype.jpg">
										
									</div>
								</li>
								<li>
									<div id="slide02" class="slide">
										<img class="" src="layouts/images/house-in-alwar.png"/>
										
									</div>
								</li>
								<li>
									<div id="slide03" class="slide">
										<img class="" src="layouts/images/Image_from_Skype.jpg"/>
										
									</div>
								</li>
								<li>
									<div id="slide04" class="slide">
										<img class="" src="layouts/images/house-in-alwar.png"/>
										
									</div>
								</li>
								<li>
									<div id="slide05" class="slide">
										<img class="" src="layouts/images/Image_from_Skype.jpg"/>
										
									</div>
								</li>
							</ul>
					</div>
				</div>
				<!-- Section1 Ends-->
				
				<!-- Section2 Starts-->
				<div class="span4"></div>
				<!-- Section2 Ends-->
				
				<!-- Section3 Starts-->
				<div class="span4"></div>
				<!-- Section3 Ends-->
			</div>
			<!-- Row3 Ends-->
		</div>
	</body>
	<script>
		jQuery(document).ready(function(){
			jQuery("#forgotPassword a").click(function() {
				jQuery("#loginDiv").hide();
				jQuery("#forgotPasswordDiv").show();
			});
			
			jQuery("#backButton a").click(function() {
				jQuery("#loginDiv").show();
				jQuery("#forgotPasswordDiv").hide();
			});
			
			jQuery("input[name='retrievePassword']").click(function (){
				var username = jQuery('#user_name').val();
				var email = jQuery('#emailId').val();
				
				var email1 = email.replace(/^\s+/,'').replace(/\s+$/,'');
				var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
				var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
				
				if(username == ''){
					alert('Please enter valid username');
					return false;
				} else if(!emailFilter.test(email1) || email == ''){
					alert('Please enater valid email address');
					return false;
				} else if(email.match(illegalChars)){
					alert( "The email address contains illegal characters.");
					return false;
				} else {
					return true;
				}
				
			});
		});
	</script>
</html>	
{/strip}

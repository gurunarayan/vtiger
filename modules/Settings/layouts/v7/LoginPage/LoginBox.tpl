<div class="login-area">
	<div class="" id="loginDiv" style="padding: 6px 0 0 0;">
		 
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
				<!---<label class="control-label" for="username"><b>User name</b></label>---->
				<div class="controls">
					<input type="text" id="username" name="username" placeholder="Username">
				</div>
			</div>

			<div class="control-group">
				<!--<label class="control-label" for="password"><b>Password</b></label>-->
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
</div>
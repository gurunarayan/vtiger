
      </div>
      
   </body>
   <script type="text/javascript">CsrfMagic.end();</script>
   <script>jQuery(document).ready(function () {
	jQuery("#forgotPassword a").click(function () {
		jQuery("#loginDiv").hide();
		jQuery("#forgotPasswordDiv").show();
	});
	jQuery("#backButton .btn-back").click(function () {
		jQuery("#loginDiv").show();
		jQuery("#forgotPasswordDiv").hide();
	});
	jQuery("input[name='retrievePassword']").click(function () {
		var username = jQuery('#user_name').val();
		var email = jQuery('#emailId').val();
		var email1 = email.replace(/^\s+/, '').replace(/\s+$/, '');
		var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/;
		var illegalChars = /[\(\)\<\>\,\;\:\\\"\[\]]/;
		if (username == '') {
			alert('Please enter valid username');
			return false;
		} else if (!emailFilter.test(email1) || email == '') {
			alert('Please enater valid email address');
			return false;
		} else if (email.match(illegalChars)) {
			alert("The email address contains illegal characters.");
			return false;
		} else {
			return true;
		}
	});
});
</script>
</html>
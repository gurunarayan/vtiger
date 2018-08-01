<!DOCTYPE html>
<html>
   <head>
      <title></title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- for Login page we are added -->
      <link href="libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="libraries/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
      <link href="libraries/bootstrap/css/jqueryBxslider.css" rel="stylesheet" />
      <link href="layouts/vlayout/modules/Settings/LoginPage/resources/LoginPage.css" rel="stylesheet" />
      <link href="layouts/vlayout/modules/Settings/LoginPage/resources/LoginPagemain.css" rel="stylesheet" />	  
      <script src="libraries/jquery/jquery.min.js"></script><script src="libraries/jquery/boxslider/jqueryBxslider.js"></script>
	  <script src="libraries/jquery/boxslider/respond.min.js"></script>
	  <script>
	  jQuery(document).ready(function () {
			scrollx = jQuery(window).outerWidth();
			window.scrollTo(scrollx, 0);
			slider = jQuery('.bxslider').bxSlider({
				mode: 'horizontal',
				auto: true,
				randomStart: false,
				autoHover: false,
				controls: false,
				pager: false,
				speed: '1500',
				easing: 'linear',
				onSliderLoad: function () {}
			});
		});
	  </script>
   </head>
   <body>
      <div class="vte-login-container">
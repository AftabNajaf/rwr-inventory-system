<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<meta name="description" content="<?php echo $settings['siteName']; ?>" />
	<meta name="author" content="Asad Islam : asadislam.com" />
	<title><?php echo $settings['siteName']; ?> </title>
	<!-- icons -->
	<link href="<?=base_url()?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?=base_url()?>assets/plugins/iconic/css/material-design-iconic-font.min.css">
	<!-- bootstrap -->
	<link href="<?=base_url()?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!-- style -->
	<link rel="stylesheet" href="<?=base_url()?>assets/css/pages/extra_pages.css">
	<!-- favicon -->
	<link rel="shortcut icon" href="<?=base_url().'assets/img/'.$settings['icon'];?>" />
</head>

<body>
	<div class="limiter">

        <div class="container-login100 page-background">
			<div class="wrap-login100">
				<form class="login100-form validate-form"  action="<?=base_url();?>Login/authenticate_login" method="post">
					<span class="login100-form-logo" style="margin-bottom:15px; display:none">
						<!--/*<i class="zmdi zmdi-flower"></i>*/-->
                        <img src="<?=base_url().'assets/img/'.$settings['image'];?>" height="120" alt="Dashboard"/>
					</span>
<!--					<span class="login100-form-title p-b-34 p-t-27">
						Log in
					</span>-->
                    
					<div class="wrap-input100 validate-input" data-validate="Enter username">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100" style="color:#00F" data-placeholder="&#xf207;"></span>
					</div>
					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button> 
					</div>
                    <div class="text-center p-t-60" style="min-height:72px; padding-top:2px">
                    <?php echo show_flash_data();  ?>&nbsp;
                    </div>
					<div class="text-left" style="float:left">
					<a class="btn btn-danger btn-xs" href="<?=base_url()?>signup"><i class="fa fa-user "></i> Signup </a>
                    </div>
                    <div class="text-right">
                    <a class="btn btn-primary btn-xs" href="<?=base_url()?>forgot"><i class="fa fa-user "></i> Forgot Password?</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- start js include path -->
	<script src="<?=base_url()?>assets/plugins/jquery/jquery.min.js"></script>
	<!-- bootstrap -->
	<script src="<?=base_url()?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/extra_pages/login.js"></script>
	<!-- end js include path -->
</body>
</html>
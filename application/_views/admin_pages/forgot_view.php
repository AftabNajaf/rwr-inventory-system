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
				<form class="login100-form validate-form"  onsubmit="return form_validation()" method="post">
					<span class="login100-form-logo" style="margin-bottom:15px;display:none">
						<!--/*<i class="zmdi zmdi-flower"></i>*/-->
                        <img src="<?=base_url().'assets/img/'.$settings['image'];?>" height="120" alt="Dashboard"/>
					</span>
<!--					<span class="login100-form-title p-b-34 p-t-27">
						Log in
					</span>-->
                    
					<div class="wrap-input100 validate-input" data-validate="Enter Email">
						<input class="input100" type="email" id="email" name="email" placeholder="Enter Your Email" required>
						<span class="focus-input100" style="color:#00F" data-placeholder="&#xf207;"></span>
					</div>
					<div class="container-login100-form-btn">
						<input type="submit" class="btn btn-primary btn-xs " value="Reset Password"> 
                        <img src="<?=base_url()?>assets/img/xm.jpg" id="spinning" alt="" style="width:50px; display:none">
					</div>
                    
                    <div class="text-center p-t-60" style="min-height:164px; padding-top:2px" id="uniqueuser">
                    
                    <?php echo show_flash_data();  ?>&nbsp;
                    </div>
					<div class="text-left" style="float:left">
					<a class="btn btn-danger btn-xs" href="<?=base_url()?>signup"><i class="fa fa-user "></i> Signup </a>
                    </div>
                    <div class="text-right">
                    <a class="btn btn-warning btn-xs" href="<?=base_url()?>login"><i class="fa fa-user "></i> Sign in</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- start js include path -->
	<script src="<?=base_url()?>assets/plugins/jquery/jquery.min.js"></script>
	<script src="<?=base_url()?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	function form_validation() 
	{  
		var uname = $("#email").val();
		$("#uniqueuser").html("");
		$("#spinning").show();
		$.post("<?=base_url()?>Registration/emailValidity",{uname}, function(data){	
							$("#spinning").show();												 
						if(data=='success'){
							
							$.post("<?=base_url()?>Registration/resetPassword",{uname}, function(xdata){
									//alert(xdata);															 
									location.reload();
							});
						}
						else{ 
						$("#spinning").hide();
						$("#uniqueuser").html(data);
						}
		});
		  return false;
	}  
</script>
	<!-- end js include path -->
</body>
</html>
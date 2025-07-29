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
    <div class="wrap-login1001">
     	<span class="login100-form-logo" style="margin-bottom:15px; display:none">
          <img src="<?=base_url().'assets/img/'.$settings['image'];?>" height="120" alt="Dashboard"/>
		</span>
	
        
	 <div class="row">
      <div class="col-md-12">
        <div class="card card-topline-aqua">
          <?php echo show_flash_data(); ?>
          <div class="card-body ">
			<form name="signupForm" id="signupForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()" >
  			<div class="row">
            	<div class="form-group col-md-3">
                	<label class="control-label">Name</label>
                	<input type="text" id="name" name="name" class="form-control"   autocomplete="off" required />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Email</label>
                	<input type="email" id="email" name="email" class="form-control"   autocomplete="off" required />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Contact No</label>
                	<input type="text" id="contactno" name="contactno" class="form-control"   autocomplete="off"  />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Emergency Contact No</label>
                	<input type="text" id="emergency" name="emergency" class="form-control"   autocomplete="off"  />
              	</div>
                <div class="form-group col-md-12">
                	<label class="control-label">Residence Address</label>
                	<input type="text" id="resaddress" name="resaddress" class="form-control"   autocomplete="off"  />
              	</div>
                
                <div class="form-group col-md-3">
                  <label>Department</label>
                  <select class="form-control" name="departmentid" id="departmentid" onChange="getsingleFieldAjax('sectionid','parentid',this.value,'e_departments')" required >
                      <option value=""></option>
                      <?=getOption('e_departments','id','title',0,array('parentid'=>0))?>
                  </select>
              	</div>                
                <div class="form-group col-md-3">
                  <label>Section/Branch</label>
                  <select class="form-control" name="sectionid" id="sectionid"  >
                      <option value="0"></option>
                      <?=getOption('e_departments','id','title')?>
                  </select>
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Intercom No</label>
                	<input type="text" id="intercom" name="intercom" class="form-control"   autocomplete="off"  />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Designation</label>
                    <select class="form-control" name="designation" id="designation"  required >
                      <option value=""></option>
                      <?=getOption('e_designations','id','title',0)?>
                  </select>
              	</div>
             	
                <div class="form-group col-md-3">
                	<label class="control-label">User LoginID</label>
                	<input type="text" id="username" name="username" class="form-control"   autocomplete="off" onKeyUp="usernameAvailability()" required />
              	</div>
                <div class="form-group col-md-3" id="uniqueuser">
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">&nbsp;</label>
                	<button type="submit" class="btn btn-primary btn-xs">Submit</button>
              	</div>
                <div class="form-group col-md-3" id="spinning" style="display:none">
                <br><img src="<?=base_url()?>assets/img/xm.jpg" alt="" style=" width:30px">
              	</div>
  			</div>
			</form>
          </div>
        </div>
      </div>
    </div>
        <div class="text-right">
        <a class="btn btn-warning btn-xs" href="<?=base_url()?>login"><i class="fa fa-user "></i> Back to Login</a>
        </div>
	</div>
	</div>
	</div>
    <div id="er"></div>
    <script type="text/javascript">

	function getsingleFieldAjax(inputfield,targetfield,value,table){
		//alert(inputfield+"  "+targetfield+"  "+value+"  "+table);
		$.post("<?=base_url()?>Registration/getsingleFieldAjax",{targetfield,value,table}, 
				function(data)
				{   //alert(data);
					$("#"+inputfield).html(data);
				});
	}

function usernameAvailability(){
	var uname =$("#username").val();
	if(uname.length<=5){	
	$("#uniqueuser").html('<br><small style="color:red">Min 6 character required</small>');
	}
	else{
		$("#uniqueuser").html('<br><small style="color:blue">Validating...</small>'+uname.length);
		
		$.post("<?=base_url()?>Registration/usernameAvailability",{uname}, 
				function(data)
				{	
					$("#uniqueuser").html(data);
				});
	}
}
function form_validation() 
{   var xcount =0;
    
	if(xcount==0){
		var form = new FormData($('#signupForm')[0]);
		$("#spinning").show();
    $.ajax({
      type: "POST",
      url: "<?=base_url()?>Registration/crudSimple",
      data: form,
      cache: false,
      contentType: false,
      processData: false,
      success: function(res){ 
	  	//alert(res);
		location.reload();
      }
      });
   }
      return false;
}  
 
</script>
	<!-- start js include path -->
	<script src="<?=base_url()?>assets/plugins/jquery/jquery.min.js"></script>
	<!-- bootstrap -->
	<script src="<?=base_url()?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/extra_pages/login.js"></script>
    
    

	<!-- end js include path -->
</body>
</html>
<?php $this->load->view('admin_layout/header');?>
<?php $data=$cmsUser; extract($userData); ?>
<div class="page-content-wrapper">
  <div class="page-content">
    <div class="page-bar">
      <div class="page-title-breadcrumb">
        <div class=" pull-left">
          <div class="page-title">Manage Profile</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
          <li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
										href="<?=base_url()?>">Home</a>&nbsp; </li>
        </ol>
      </div>
    </div>
    <!-- ====================Form Controls ===================== -->
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="card card-box">
          
          <div class="card-body " id="bar-parent2">
          	<?php echo show_flash_data();?>
		 	<span id="flash_data"></span>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="e_admin" id="tableName" name="tableName">
  <div class="row">
            	<div class="form-group col-md-3">
                	<label class="control-label">Name</label>
                	<input type="text" id="name" name="name" class="form-control" value="<?=$data['name']?>"   autocomplete="off" required />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Email</label> 
                	<input type="email" id="email" name="email" class="form-control" value="<?=$data['email']?>" disabled="disabled" />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Contact No</label>
                	<input type="text" id="contactno" name="contactno" class="form-control"  value="<?=$data['contactno']?>"  autocomplete="off" required />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Emergency Contact No</label>
                	<input type="text" id="emergency" name="emergency" class="form-control" value="<?=$data['emergency']?>"   autocomplete="off" required />
              	</div>
                <div class="form-group col-md-6">
                	<label class="control-label">Residence Address</label>
                	<input type="text" id="resaddress" name="resaddress" class="form-control"  value="<?=$data['resaddress']?>"  autocomplete="off" required />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">User LoginID</label>
                	<input type="text" id="username" name="username" class="form-control"  value="<?=$data['username']?>"  autocomplete="off" disabled />
              	</div>
                <div class="form-group col-md-3">
                    <label class="control-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" value=""  />
                  </div>
                
                
                <div class="form-group col-md-3">
                	<label class="control-label">Intercom No</label>
                	<input type="text" id="intercom" name="intercom" class="form-control" value="<?=$data['intercom']?>"   autocomplete="off" required />
              	</div>
                
  			
  
      
      
      
	<div class="form-group col-md-3">
        <label class="control-label">Image</label>
        <input type="file" id="image" name="image" class="form-control" />
      </div>
      <div class="form-group col-md-3">
        <?php if (!empty($data['image'])): ?>
		           	 		<img class="img-thumbnail" src="<?=base_url()?>assets/img/<?=$data['image']?>" style="float:left; width:60px">
		       <?php endif ?>
      </div>
      
      
      <div class="form-group col-md-3">
        <label class="control-label">&nbsp;</label><br>
                	<button type="submit" class="btn btn-primary btn-xl">Save</button>
      </div>
  </div>
</form> 
            
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

function form_validation() 
{   var form = new FormData($('#myForm')[0]); 
    $.ajax({
      type: "POST",
      url: '<?php echo base_url(); ?>Function_control/updateCmsUserPassword',
      data: form,
      cache: false,
      contentType: false,
      processData: false,
      success: function(res)
      { //alert(res);
       location.reload();
      }
       
    });
      return false;
}   
</script>

<?php $this->load->view('admin_layout/footer');?>

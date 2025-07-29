<?php $this->load->view('admin_layout/header');?>
<?php extract($settings);  //extract($userData); ?>
<div class="page-content-wrapper">
  <div class="page-content">
    <div class="page-bar">
      <div class="page-title-breadcrumb">
        <div class=" pull-left">
          <div class="page-title">General Settings</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
          <li><i class="fa fa-home"></i>&nbsp;<a class="parent-item" href="<?=base_url()?>">Home</a>&nbsp; </li>
        </ol>
      </div>
    </div>
    <!-- ====================Form Controls ===================== -->
    <div class="row" >
      <div class="col-md-12 col-sm-12">
        <div class="card card-box">          
          <div class="card-body " id="bar-parent2">
          <?php echo show_flash_data();?>
		 <span id="flash_data"></span>
            <form name="updateForm" id="updateForm" method="post" enctype="multipart/form-data"  onsubmit="return update_validation()">
              <!-- hidden input -->
              <input type="hidden" value="<?=$id?>" name="id">
              <input type="hidden" value="<?=$userData['userid']?>" name="userid">
              <!-- End hidden input -->
              <div class="row" >
                <div class="col-lg-3 p-t-20">
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                <input class="mdl-textfield__input" type="text" id="siteName" name="siteName" required  value="<?=$siteName?>"  >
                <label class="mdl-textfield__label">Company Name</label>
              </div>
            </div>
            
            <div class="col-lg-2 p-t-20">
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                <input class="mdl-textfield__input" type="text" id="phone" name="phone" required value="<?=$phone?>" >
                <label class="mdl-textfield__label">Contact Number</label>
              </div>
            </div>
            
            <div class="col-lg-2 p-t-20">
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                <input class="mdl-textfield__input" type="email" id="email" name="email" required value="<?=$email?>"  >
                <label class="mdl-textfield__label">Official Email</label>
              </div>
            </div>
            
            
            <div class="col-lg-5 p-t-20">
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                <input class="mdl-textfield__input" type="text" id="address" name="address" value="<?=$address?>" >
                <label class="mdl-textfield__label">Address</label>
              </div>
            </div>
            
            <!--<div class="col-lg-12 p-t-20" style="display:none">
              <div class="mdl-textfield mdl-js-textfield txt-full-width">
                <textarea class="mdl-textfield__input" rows="2" id="about" name="about"><?=$about?></textarea>
                <label class="mdl-textfield__label" for="text7">Inroduction</label>
              </div>
            </div>-->
            
            <div class="col-lg-2 p-t-20">
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
        <input class="mdl-textfield__input" type="text"  id="contact_personal" name="contact_person" required value="<?=$contact_person?>"  >
                <label class="mdl-textfield__label">Contact Person</label>
              </div>
            </div>
            
			<div class="col-lg-2 p-t-20">
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width"><!--$otfactor-->
        <input class="mdl-textfield__input" type="text" id="designation" name="designation" required value="<?=$designation?>"  >
		
                <label class="mdl-textfield__label">Designation</label>
              </div>
            </div>
            
            <div class="col-lg-4 p-t-20">
              <label class="mdl-textfield mdl-textfield__label" style="text-align:right">Company Logo </label>
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                <input class="mdl-textfield__input" type="file" id="image" name="image" >
              </div>
            </div>
            
            <div class="col-lg-4 p-t-20">
              <label class="mdl-textfield mdl-textfield__label" style="text-align:right">Company Icon </label>
              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                <input class="mdl-textfield__input" type="file" id="icon" name="icon" >
                
              </div>
            </div>
            <div class="col-lg-4  p-t-20  ">
            <button type="submit" class="btn btn-info"> Update </button>
            </div>
            <div class="col-lg-4 ">
              <?php if (!empty($image)): ?>
		           	 		<img class="img-thumbnail" src="<?=base_url()?>assets/img/<?=$image?>" style="float:left; width:80px">
		       <?php endif ?>
            </div>
            <div class="col-lg-4 ">
			   <?php if (!empty($icon)): ?>
		           	 		<img class="img-thumbnail" src="<?=base_url()?>assets/img/<?=$icon?>" style="float:left; width:80px;">
		       <?php endif ?> 
            </div>
      
            
            </form>
          </div>

        </div>
      </div>
    </div>
    <!-- =============== End Form Control =============================== -->
  </div>
</div>

<script type="text/javascript">
function update_validation() 
{   var form = new FormData($('#updateForm')[0]); 
    $.ajax({
      type: "POST",
      url: '<?php echo base_url(); ?>Function_control/updateSetting',
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

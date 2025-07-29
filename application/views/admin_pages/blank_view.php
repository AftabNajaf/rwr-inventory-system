<?php $this->load->view('admin_layout/header');?>
<?php extract($userData); ?>
<div class="page-content-wrapper">
  <div class="page-content">
    <div class="page-bar">
      <div class="page-title-breadcrumb">
        <div class=" pull-left">
          <div class="page-title">Employee Categories / Roles</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
          <li><i class="fa fa-home"></i>&nbsp;<a class="parent-item" href="<?=base_url()?>">Home</a>&nbsp; </li>
        </ol>
      </div>
    </div>
    <!-- ====================Form Controls ===================== -->
    <div class="row full-width" style="margin-top:70px;">
        <div class="card  card-box">
          <div class="card-head">
            <div style="padding:0px 20px">
              <button type="button" class="btn btn-round btn-warning" id="btn_popup">Add New</button>
            </div>
          </div>
          <div class="card-body "  style=" overflow-y: scroll; height:64vh"> <?php echo show_flash_data();?> <span id="flash_data"></span>
            g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />g<br />
          </div>
        </div>
    </div>
    <div id="popup" style="display:none; position:absolute;top:0px;z-index:9999;">
      <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="card card-box">
            <div class="card-head" style="width:100%; position:relative;top:0; height:34px;cursor:move" id="popup_bar">
              <header id="exTitle">Add New</header>
              <span id="btn_close" style="float:right;cursor:pointer;padding-right:6px;">[X]</span> </div>
            <div class="card-body " id="bar-parent2">
              <form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
                <input type="hidden" value="<?=$userData['userid']?>" id="userid" name="userid">
                <input type="hidden" value="e_roles" id="tableName" name="tableName">
                <div class="row">
                  <div class="col-lg-4 p-t-20">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                      <input class="mdl-textfield__input" type="text" id="title" name="title" required  >
                      <label class="mdl-textfield__label">Category Title</label>
                    </div>
                  </div>
                  <div class="col-lg-4 p-t-20">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
                      <input class="mdl-textfield__input" type="text" id="shortcode" name="shortcode" >
                      <label class="mdl-textfield__label">Short Code</label>
                    </div>
                  </div>
                  <div class="col-lg-4 p-t-20">
                    <button type="submit" class="btn btn-info"> Save </button>
                    <label class="mdl-textfield__label">&nbsp;</label>
                  </div>
                  <div class="col-lg-12 p-t-20"><br />
                    &nbsp;</div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<?php $this->load->view('admin_layout/footer');?>

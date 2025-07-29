<?php  $CI =& get_instance(); ?>


<?php if ($viewType == "Users"):  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  <div class="row">
            	<div class="form-group col-md-3">
                	<label class="control-label">Name</label>
                	<input type="text" id="name" name="name" class="form-control" value="<?=$data['name']?>"   autocomplete="off" required />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Email</label> 
                    <small style="float:right"><strong> <?php if($data['emailverified']!='') echo 'Verified :'.$data['emailverified'];?></strong></small>
                	<input type="email" id="email" name="email" class="form-control" value="<?=$data['email']?>"   autocomplete="off" required />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Contact No</label>
                	<input type="text" id="contactno" name="contactno" class="form-control"  value="<?=$data['contactno']?>"  autocomplete="off" required />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Emergency Contact No</label>
                	<input type="text" id="emergency" name="emergency" class="form-control" value="<?=$data['emergency']?>"   autocomplete="off"  />
              	</div>
                <div class="form-group col-md-6">
                	<label class="control-label">Residence Address</label>
                	<input type="text" id="resaddress" name="resaddress" class="form-control"  value="<?=$data['resaddress']?>"  autocomplete="off"  />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">User LoginID</label>
                	<input type="text" id="username" name="username" class="form-control"  value="<?=$data['username']?>"  autocomplete="off" readonly required />
              	</div>
                <div class="form-group col-md-3">
                    <label class="control-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" value=""  />
                  </div>
                
                <div class="form-group col-md-3">
                  <label>Department</label>
                  <select class="form-control" name="departmentid" id="departmentid" onChange="getsingleFieldAjax('sectionid','parentid',this.value,'e_departments')" required >
                      <option value=""></option>
                      <?=getOption('e_departments','id','title',$data['departmentid'],array('parentid'=>0))?>
                  </select>
              	</div>                
                <div class="form-group col-md-3">
                  <label>Section/Branch</label>
                  <select class="form-control" name="sectionid" id="sectionid"  >
                      <option value=""></option>
                      <?=getOption('e_departments','id','title',$data['sectionid'], array('parentid'=>$data['departmentid']))?>
                  </select>
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Intercom No</label>
                	<input type="text" id="intercom" name="intercom" class="form-control" value="<?=$data['intercom']?>"   autocomplete="off" required />
              	</div>
                <div class="form-group col-md-3">
                	<label class="control-label">Designation</label>
                    <select class="form-control" name="designation" id="designation"  required >
                      <option value=""></option>
                      <?=getOption('e_designations','id','title',$data['designation'])?>
                  </select>
              	</div>
  			
  
      <div class="form-group col-md-3">
        <label class="control-label">Date of Hiring</label>
        <input type="text" id="hiredon" name="hiredon" class="form-control default_datetimepicker" value="<?=$data['hiredon']?>"  required autocomplete="off"   />
      </div>
     
      <div class="form-group col-md-3">
        <label>Role</label>
        <select class="form-control" name="role" id="role" required >
          <option value=""></option>
          <?=getOption('e_roles','title','title',$data['role'])?>
        </select>
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
        <label>Status</label>
        <select class="form-control" name="status" id="status" required >
          <option value="Active">Active</option>
          <option value="Inactive" <?php if($data['status']=='Inactive') echo 'selected' ?>>Inactive</option>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label>Email Verified</label>
        <select class="form-control" name="emailverified" id="emailverified" required >
          <option value="no">No</option>
          <option value="yes" <?php if($data['emailverified']=='yes') echo 'selected' ?>>Yes</option>
        </select>
      </div>
      <?php /*?><!--Dashboard, items,bom,bmlib,mat,po,pol,grn,advbooking,release,relreqbyuser,tempissue,
restock,dc,mmov,fixed,suppliers,customers,departments,projects,categories,units,
feedbackform,feedbackrpt--><?php */?>

      <div class="form-group col-md-12">
      <label ><h5><u>System Access</u></h5></label><br />

      <?php  if($data['privileges']!="") $privileges = explode(',', $data['privileges']); else $privileges=array(); ?>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label"> BOM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="bom" <?php if(in_array('bom',$privileges)) echo 'checked' ?>> View BOM </label> &nbsp;&nbsp;&nbsp;
          <label class="form-check-label">
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="bomcreate" <?php if(in_array('bomcreate',$privileges)) echo 'checked' ?>> Add New BOM </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label"> MAT &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="mat" <?php if(in_array('mat',$privileges)) echo 'checked' ?>> View MAT </label> &nbsp;&nbsp;&nbsp;
          <label class="form-check-label">
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="matcreate" <?php if(in_array('matcreate',$privileges)) echo 'checked' ?>> Add New MAT </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label"> PO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="po" <?php if(in_array('po',$privileges)) echo 'checked' ?>> View P.O </label> &nbsp;&nbsp;&nbsp;
          <label class="form-check-label">
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="pocreate" <?php if(in_array('pocreate',$privileges)) echo 'checked' ?>> Add New P.O </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label"> Adv. Booking &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="advbooking" <?php if(in_array('advbooking',$privileges)) echo 'checked' ?>> View Adv Bookings </label> &nbsp;&nbsp;&nbsp;
          <label class="form-check-label">
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="advcreate" <?php if(in_array('advcreate',$privileges)) echo 'checked' ?>> Add New Adv.Booking </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label"> Releases &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="release" <?php if(in_array('release',$privileges)) echo 'checked' ?>> View Releases </label> &nbsp;&nbsp;&nbsp;
          <label class="form-check-label">
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="releasecreate" <?php if(in_array('releasecreate',$privileges)) echo 'checked' ?>> Add New Releases </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px;">
        <label class="form-check-label"> Mat. Mov &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="mmov" <?php if(in_array('mmov',$privileges)) echo 'checked' ?>> View MMO </label> &nbsp;&nbsp;&nbsp;
          <label class="form-check-label">
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="mmovcreate" <?php if(in_array('mmovcreate',$privileges)) echo 'checked' ?>> Add New MMO </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label"> Restock &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="restock" <?php if(in_array('restock',$privileges)) echo 'checked' ?>> View Restock </label> &nbsp;&nbsp;&nbsp;
          <label class="form-check-label">
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="restockcreate" <?php if(in_array('restockcreate',$privileges)) echo 'checked' ?>> Add New Restock </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label"> Temp. Issue &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="tempissue" <?php if(in_array('tempissue',$privileges)) echo 'checked' ?>> View Temp. Issues </label> &nbsp;&nbsp;&nbsp;
          <label class="form-check-label">
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="tempissuecreate" <?php if(in_array('tempissuecreate',$privileges)) echo 'checked' ?>> Add New Temp. Issue </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label"> Del. Challan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="dc" <?php if(in_array('dc',$privileges)) echo 'checked' ?>> View Del. Challan </label> &nbsp;&nbsp;&nbsp;
          <label class="form-check-label">
          <input class="form-check-input" type="checkbox"  name='privileges[]' value="dccreate" <?php if(in_array('dccreate',$privileges)) echo 'checked' ?>> Add New Del. Challan </label>
      </div>
      <hr />
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="bmlib" <?php if(in_array('bmlib',$privileges)) echo 'checked' ?>> BOM Library 
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="items" <?php if(in_array('items',$privileges)) echo 'checked' ?>> Items Management
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="pol" <?php if(in_array('pol',$privileges)) echo 'checked' ?>> POL
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="grn" <?php if(in_array('grn',$privileges)) echo 'checked' ?>> GRN
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="relreqbyuser" <?php if(in_array('relreqbyuser',$privileges)) echo 'checked' ?>> RE.Req By User
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="suppliers" <?php if(in_array('suppliers',$privileges)) echo 'checked' ?>> Suppliers Management
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="customers" <?php if(in_array('customers',$privileges)) echo 'checked' ?>> Customers Management
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="departments" <?php if(in_array('departments',$privileges)) echo 'checked' ?>> Departments Management
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="projects" <?php if(in_array('projects',$privileges)) echo 'checked' ?>> Projects Management
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="categories" <?php if(in_array('categories',$privileges)) echo 'checked' ?>> Categories Management
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="units" <?php if(in_array('units',$privileges)) echo 'checked' ?>> Units Management
        </label>
      </div>      
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="fixed" <?php if(in_array('fixed',$privileges)) echo 'checked' ?>> Fixed Assets
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="reqs" <?php if(in_array('reqs',$privileges)) echo 'checked' ?>> Store Requests
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="feedbackform" <?php if(in_array('feedbackform',$privileges)) echo 'checked' ?>> Feedback
        </label>
      </div>
      <div class="form-check form-check-inline" style="background-color:#CCC; line-height:20px; padding:5px; margin:5px">
        <label class="form-check-label">
        <input class="form-check-input" type="checkbox"  name='privileges[]' value="feedbackrpt" <?php if(in_array('feedbackrpt',$privileges)) echo 'checked' ?>> Feedbback Report
        </label>
      </div>
      </div>
      
      <div class="form-group col-md-3">
        <label class="control-label">&nbsp;</label><br>
                	<button type="submit" class="btn btn-primary btn-xl">Save</button>
      </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>

<?php if ($viewType == "Customer"):  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="form-group col-md-3">
        <label class="control-label">Name/Title</label>
        <input type="text" id="title" name="title" class="form-control" value="<?=$data['title']?>" required />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" value="<?=$data['email']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Phone No</label>
        <input type="text" id="contactno" name="contactno" class="form-control" value="<?=$data['contactno']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Website</label>
        <input type="text" id="website" name="website" class="form-control" value="<?=$data['website']?>"  />
      </div>
      <div class="form-group col-md-9">
        <label class="control-label">Address</label>
        <input type="text" id="address" name="address" class="form-control" value="<?=$data['address']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label>Active</label>
        <select class="form-control" name="status" id="status" required >
          <option value="Yes">Yes</option>
          <option value="No"  <?php if($data['status']=='No') echo 'selected' ?>>No</option>
        </select>
      </div>
      <div class="form-group col-md-12">
        <label>Remarks/Additional Info</label>
        <textarea class="form-control" name="remarks" id="summernote"><?=$data['remarks']?></textarea>
      </div>
      
    </div>
    <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>

<?php if ($viewType == "Supplier"):  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="form-group col-md-3">
        <label class="control-label">Name/Title</label>
        <input type="text" id="title" name="title" class="form-control" value="<?=$data['title']?>" required />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" value="<?=$data['email']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Phone No</label>
        <input type="text" id="contactno" name="contactno" class="form-control" value="<?=$data['contactno']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Website</label>
        <input type="text" id="website" name="website" class="form-control" value="<?=$data['website']?>"  />
      </div>
      <div class="form-group col-md-9">
        <label class="control-label">Address</label>
        <input type="text" id="address" name="address" class="form-control" value="<?=$data['address']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label>Active</label>
        <select class="form-control" name="status" id="status" required >
          <option value="Yes">Yes</option>
          <option value="No"  <?php if($data['status']=='No') echo 'selected' ?>>No</option>
        </select>
      </div>
      <div class="form-group col-md-12">
        <label>Remarks/Additional Info</label>
        <textarea class="form-control" name="remarks" id="summernote"><?=$data['remarks']?></textarea>
      </div>
      
    </div>
    <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>

<?php if ($viewType == "Departments"):  //extract($data);

?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  <div class="row">
  	<div class="form-group col-md-5">
        <label class="control-label">Department Title</label>
        <input type="text" id="title" name="title" class="form-control" value="<?=$data['title']?>" required   />
      </div>
      
      <div class="form-group col-md-5">
        <label>Parent Departments</label>
        <select class="form-control" name="parentid" id="parentid" required >
            <option value="0"></option>
          	<?= getOption('e_departments','id','title',$data['parentid'])?>
            </select>
    </div>
     
      <div class="form-group col-md-2">
        <label class="control-label"></label><br />
        <button type="submit" class="btn btn-info">Save</button>
      </div>
      
   
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>

<?php if ($viewType == "Units")://extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  <div class="row">
  	<div class="form-group col-md-5">
        <label class="control-label">Unit Title</label>
        <input type="text" id="title" name="title" class="form-control" value="<?=$data['title']?>" required   />
      </div>
     
      <div class="form-group col-md-2">
        <label class="control-label"></label><br />
        <button type="submit" class="btn btn-info">Save</button>
      </div>
      
   
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>

<?php if ($viewType == "Categories"):  if($action=='add' && $val>0){$data['parentid']=$val; $val=0;  $data['title']='';}//extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  <div class="row">
  	<div class="form-group col-md-5">
        <label class="control-label">Categories Title</label>
        <input type="text" id="title" name="title" class="form-control" value="<?=htmlspecialchars($data['title'])?>" required   />
      </div>
      
      <div class="form-group col-md-5">
        <label>Parent Categories</label>
        <select class="form-control" name="parentid" id="parentid" required >
            <option value="0"></option>
          	<?= getOption('e_categories','id','title',$data['parentid'])?>
            </select>
    </div>
     
      <div class="form-group col-md-2">
        <label class="control-label"></label><br />
        <button type="submit" class="btn btn-info">Save</button>
      </div>
      
   
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>

<?php if ($viewType == "Items"):  if($action=='add' && $val>0){$data['item_category']=$val; $val=0;  $data['item_name']='';}//extract($data);?>

<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  <!--
   ``, ``, ``, ``, ``, `item_image`, `item_document`, `item_addl_remarks`SELECT * FROM `e_items` WHERE 1
  -->
  <div class="row">
    <div class="form-group col-md-3">
        <label>Item Category</label>
        <input type="text" disabled class="form-control" value="<?=getField('e_categories',array("id"=>$data['item_category']),'title')?>"  />
        <input type="hidden" id="item_category" name="item_category" class="form-control" value="<?=$data['item_category']?>" required   />
      
    </div>
    <div class="form-group col-md-3">
        <label>New Parent Node/Category&nbsp;<small class="btn btn-sm btn-info" onclick="getNewPath()" style="float:right"><i class="fa fa-refresh"></i></small></label>
        <select class="form-control" name="newparentid" id="newparentid" onchange="getNewPath(this.value)" >
            <option value="0"></option>
          	<?=getOption('e_categories','id','title',$data['item_category'])?>
            </select>
      
    </div>
    <div class="form-group col-md-6">
        <label><strong>&nbsp;</strong></label><br />
        <span  id="newpath"></span>
    </div>
    <div class="form-group col-md-12">
        <label><strong>Path</strong></label><br />
        <span><?=$this->AdminModel->getCatPath($data['item_category'])?> </span>
    </div>
  	<div class="form-group col-md-3">
        <label class="control-label">Item Name</label>
        <input type="text" id="item_name" name="item_name" class="form-control" value="<?=htmlspecialchars($data['item_name'])?>" required   />
    </div>
    <div class="form-group col-md-3">
        <label>Supplier</label>
        <select class="form-control" name="supplier_id" id="supplier_id" required >
            <option value=""></option>
            <?=getOption('e_suppliers','id','title',$data['supplier_id'])?>
            </select>
    </div>
  	<div class="form-group col-md-3">
        <label class="control-label">Supplier Ref</label>
        <input type="text" id="supplier_ref" name="supplier_ref" class="form-control" value="<?=$data['supplier_ref']?>"    />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Part No</label>
        <input type="text" id="part_number" name="part_number" class="form-control" value="<?=$data['part_number']?>"    />
    </div>
    <div class="form-group col-md-3" >
        <label class="control-label">Item Serial No</label>
        <input type="text" id="item_serial_no" name="item_serial_no" class="form-control" value="<?=$data['item_serial_no']?>"    />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Item Asset No</label>
        <input type="text" id="item_asset_no" name="item_asset_no" class="form-control" value="<?=$data['item_asset_no']?>"    />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Functionality</label>
        <input type="text" id="item_functionality" name="item_functionality" class="form-control" value="<?=$data['item_functionality']?>"    />
    </div>
    <div class="form-group col-md-6">
        <label class="control-label">Description</label>
        <input type="text" id="item_description" name="item_description" class="form-control" value="<?=$data['item_description']?>"    />
    </div>
    <div class="form-group col-md-2">
        <label class="control-label">Minimum Qty</label>
        <input type="number" id="min_qty" name="min_qty" class="form-control" value="<?=$data['min_qty']?>"    />
    </div>
    <div class="form-group col-md-2">
        <label class="control-label">Maximum Qty</label>
        <input type="number" id="max_qty" name="max_qty" class="form-control" value="<?=$data['max_qty']?>"    />
    </div>
    <div class="form-group col-md-2">
        <label>Item Unit</label>
        <select class="form-control" name="item_unit" id="item_unit" required >
            <option value=""></option>
            <?= getOption('e_units','id','title',$data['item_unit'])?>
            </select>
    </div>
    <div class="form-group col-md-6">
        <label class="control-label">Weblink/URL</label>
        <input type="text" id="weblink" name="weblink" class="form-control" value="<?=$data['weblink']?>"    />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Image</label>
        <input type="file" id="image" name="image" class="form-control" />
      </div>
      
      
    
    
      <div class="form-group col-md-3">
        <?php if (!empty($data['image'])): ?>
		           	 		<img class="img-thumbnail" src="<?=base_url()?>assets/img/itmimages/<?=$data['image']?>" style="float:left; width:60px">
		       <?php endif ?>
      </div>
      
      <div class="form-group col-md-3">
        <label class="control-label">Document/Datasheet</label>
        <input type="file" id="doc_list" name="doc_list" class="form-control" />
      </div>
      <div class="form-group col-md-3">
        <?php if (!empty($data['doc_list'])): ?>
			 <a href="<?=base_url()?>assets/lib/<?=$data['doc_list']?>" title="<?=$data['doc_list']?>" target="blank">
             <img src="<?=base_url()?>assets/img/docicon.png"  style="float:left; width:60px"></a> 
		       <?php endif ?>
      </div>
      
     
      <div class="form-group col-md-2">
        <label class="control-label"></label><br />
        <button type="submit" class="btn btn-info">Save</button>
      </div>
      
   
  </div>
</form>

<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>

<?php endif ?>

<?php if ($viewType == "ItemsStock"):  if($action=='add' && $val>0){$data['item_id']=$val; $val=0; }//extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  <!--
   `id`, `item_id`, ``, ``, ``, `item_quantity`, `pkr_unit_price`, `usd_unit_price`, `item_value`, `purchase_date`, `expiry_date`, ``
  -->
  <div class="row">
    <div class="form-group col-md-3">
        <label>Item Name</label>
        <input type="text" disabled class="form-control" value="<?=getField('e_items',array("id"=>$data['item_id']),'item_name')?>"  />
        <input type="hidden" id="item_id" name="item_id" class="form-control" value="<?=$data['item_id']?>" required   />
      
    </div>
    <div class="form-group col-md-9">
        <label><strong>Path</strong></label><br />
        <?=$this->AdminModel->getItemPath($data['item_id'])?>
    </div>
    
    
    <div class="form-group col-md-3">
        <label class="control-label">GRN No</label>
        <input type="text" id="grn_no" name="grn_no" class="form-control" value="<?=$data['grn_no']?>"    />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Quantity</label>
        <input type="number" id="item_quantity" name="item_quantity" class="form-control" value="<?=$data['item_quantity']?>" required   />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Price PKR/Unit</label>
        <input type="number" id="pkr_unit_price" name="pkr_unit_price" class="form-control" value="<?=$data['pkr_unit_price']?>" required   />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Price USD/Unit</label>
        <input type="number" id="usd_unit_price" name="usd_unit_price" class="form-control" value="<?=$data['usd_unit_price']?>" required   />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Item Value($)</label>
        <input type="number" id="item_value" name="item_value" class="form-control" value="<?=$data['item_value']?>" required readonly="readonly" onfocus="getItemValue()" onblur="getItemValue()"   />
    </div>
    
    <div class="form-group col-md-3">
        <label class="control-label">Purchase Date</label>
        <input type="text" id="purchase_date" name="purchase_date" class="form-control default_datetimepicker" value="<?=$data['purchase_date']?>"  required autocomplete="off"   />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Expiry Date</label>
        <input type="text" id="expiry_date" name="expiry_date"  class="form-control default_datetimepicker" value="<?=$data['expiry_date']?>" />
    </div>

      <div class="form-group col-md-2">
        <label class="control-label"></label><br />
        <button type="submit" class="btn btn-info">Save</button>
      </div>
      
   
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>


<?php if ($viewType == "GrnItemsStock"):  
if($action=='add' && $val>0){
	$data['item_id']		= $val; $val=0; 
	$qn 					= getField('e_poitems',array("po_id"=>$data['grn_no'],'item_id'=>$data['item_id']),'qty_needed');
	$data['supplier_id']	= getField('e_po',array("id"=>$data['grn_no']),'supplier_id');
}
else if($action=='edit' && $val>0){
	$data['item_id']		= $val; $val=$data['id']; 
	$qn 					= getField('e_poitems',array("po_id"=>$data['grn_no'],'item_id'=>$data['item_id']),'qty_needed');
	$data['supplier_id']	= getField('e_po',array("id"=>$data['grn_no']),'supplier_id');
}//extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="grn_by" name="grn_by">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">

  <div class="row">
    <div class="form-group col-md-3">
        <label>Item Name</label>
        <input type="text" disabled class="form-control" value="<?=getField('e_items',array("id"=>$data['item_id']),'item_name')?>"  />
        <input type="hidden" id="item_id" name="item_id" class="form-control" value="<?=$data['item_id']?>" required   />
      
    </div>
    <div class="form-group col-md-9">
        <label><strong>Path</strong></label><br />
        <?=$this->AdminModel->getItemPath($data['item_id'])?>
    </div>
    <div class="form-group col-md-3">
        <label>Supplier</label>
        <select class="form-control" name="supplier_id" id="supplier_id" disabled >
            <option value=""></option>
            <?= getOption('e_suppliers','id','title',$data['supplier_id'])?>
            </select>
    </div>
  
    
    <div class="form-group col-md-3">
        <label class="control-label">GRN No</label>
        <input type="text" id="grn_no" name="grn_no" class="form-control" value="<?=$data['grn_no']?>"    />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Qty Recived out off <?=$qn?> </label>
        <input type="number" id="item_quantity" min="1" max="<?=$qn?>" name="item_quantity" class="form-control" value="<?=$data['item_quantity']?>" required   />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Price PKR/Unit</label>
        <input type="number" id="pkr_unit_price" name="pkr_unit_price" class="form-control" value="<?=$data['pkr_unit_price']?>" required   />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Price USD/Unit</label>
        <input type="number" id="usd_unit_price" name="usd_unit_price" class="form-control" value="<?=$data['usd_unit_price']?>" required   />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Item Value($)</label>
        <input type="number" id="item_value" name="item_value" class="form-control" value="<?=$data['item_value']?>" required readonly="readonly"onfocus="getItemValue()" onblur="getItemValue()"   />
    </div>
    
    <div class="form-group col-md-3">
        <label class="control-label">Purchase Date</label>
        <input type="text" id="purchase_date" name="purchase_date" class="form-control default_datetimepicker" value="<?=$data['purchase_date']?>"  required autocomplete="off"   />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Expiry Date</label>
        <input type="text" id="expiry_date" name="expiry_date"  class="form-control default_datetimepicker" value="<?=$data['expiry_date']?>" />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Serial No(s)</label>
        <input type="text" id="serial_no" name="serial_no" class="form-control" />
    </div>
	<div class="form-group col-md-3">
        <label class="control-label">Store Room No</label>
        <input type="text" id="store_room_no" name="store_room_no" class="form-control" value="<?=$data['store_room_no']?>"   />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Rack No</label>
        <input type="text" id="rack_no" name="rack_no" class="form-control" value="<?=$data['rack_no']?>"   />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Cabin No</label>
        <input type="text" id="cabin_no" name="cabin_no" class="form-control" value="<?=$data['cabin_no']?>"  />
    </div>
      <div class="form-group col-md-2">
        <label class="control-label"></label><br />
        <button type="submit" class="btn btn-info">Save</button>
      </div>
      
   
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<?php endif ?>

<?php if ($viewType == "GrnPo"):  
 if($data['generated_on']=='') $data['generated_on']=date("Y-m-d H:i",time());
  if(!$data['generated_by']>0) $data['generated_by']=$_SESSION['userid']; 
  if(!$data['approved_by']>0) $data['approved_by']=0; 
  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  
  <div class="row">
      <div class="form-group col-md-4">
          <label>Supplier</label>
          <select class="form-control" name="supplier_id" id="supplier_id"  required >
              <option value=""></option>
              <?=getOption('e_suppliers','id','title',$data['supplier_id'])?>
          </select>
        </div>       
      
      <div class="form-group col-md-4">
        <label class="control-label">Generated By</label>
        <?php $gb=getField('e_admin',array("userid"=>$data['generated_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$gb?>"  />
        <input type="hidden" id="generated_by" name="generated_by" class="form-control" value="<?=$data['generated_by']?>"  />
      </div>
      <div class="form-group col-md-4">
        <label class="control-label">Genereated On</label>
        <input type="text" id="generated_on" name="generated_on" class="form-control default_datetimepicker" value="<?=$data['generated_on']?>" />
      </div>
      <div class="form-group col-md-3" style="display:none">
        <label class="control-label">PO Status</label>
        <select class="form-control" name="approved_status" id="approved_status" required >
          <option value="Approved"  <?php if($data['approved_status']=='Approved') echo 'selected' ?>>Approved</option>
        </select>
      </div>
      <div class="form-group col-md-3" style="display:none">
        <label class="control-label">Approved By</label>
        <?php $ab=''; if($data['approved_by']>0) $ab=getField('e_admin',array("userid"=>$data['approved_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$ab?>"/>
        <input type="hidden" id="approved_by" name="approved_by" class="form-control" value="<?=$this->session->userid?>"  /><!--data['approved_by']-->
      </div>

      
    <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>

<?php if ($viewType == "Po"):  
 if($data['generated_on']=='') $data['generated_on']=date("Y-m-d H:i",time());
  if(!$data['generated_by']>0) $data['generated_by']=$_SESSION['userid']; 
  if(!$data['approved_by']>0) $data['approved_by']=0; 
  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  
  <div class="row">
      <div class="form-group col-md-3">
          <label>Supplier</label>
          <select class="form-control" name="supplier_id" id="supplier_id"  required >
              <option value=""></option>
              <?=getOption('e_suppliers','id','title',$data['supplier_id'])?>
          </select>
        </div>       
      
      <div class="form-group col-md-3">
        <label class="control-label">Generated By</label>
        <?php $gb=getField('e_admin',array("userid"=>$data['generated_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$gb?>"  />
        <input type="hidden" id="generated_by" name="generated_by" class="form-control" value="<?=$data['generated_by']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Genereated On</label>
        <input type="text" id="generated_on" name="generated_on" class="form-control default_datetimepicker" value="<?=$data['generated_on']?>" />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">PO Status</label>
        <select class="form-control" name="approved_status" id="approved_status" required >
          <option value="Draft"  <?php if($data['approved_status']=='Draft') echo 'selected' ?>>Draft</option>
          <option value="Submitted"  <?php if($data['approved_status']=='Submitted') echo 'selected' ?>>Submitted</option>
          <?php if($this->session->role!='level1'):?>
          <option value="Approved"  <?php if($data['approved_status']=='Approved') echo 'selected' ?>>Approved</option>
          <?php endif?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Approved By</label>
        <?php $ab=''; if($data['approved_by']>0) $ab=getField('e_admin',array("userid"=>$data['approved_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$ab?>"/>
        <input type="hidden" id="approved_by" name="approved_by" class="form-control" value="<?=$data['approved_by']?>"  />
      </div>

      
    <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>
<?php if ($viewType == "AdvBooking"):  
 if($data['generated_on']=='') $data['generated_on']=date("Y-m-d H:i",time());
  if(!$data['generated_by']>0) $data['generated_by']=$_SESSION['userid']; 
  if(!$data['approved_by']>0) $data['approved_by']=0; 
  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  
  <div class="row">
      <div class="form-group col-md-3">
          <label>Department</label>
          <?php if($this->session->role=='admin'):?>          
          <select class="form-control" name="department_id" id="department_id" onChange="getsingleFieldAjax('section_id','parentid',this.value,'e_departments')" required >
              <option value=""></option>
              <?=getOption('e_departments','id','title',$data['department_id'],array('parentid'=>0))?>
          </select>
          <?php else :?>
          <input class="form-control" disabled="disabled" value="<?=getField('e_departments',array('id'=>$this->session->departmentid),'title')?>"  />
          <input class="form-control" type="hidden" id="department_id" name="department_id" value="<?=$this->session->departmentid?>" required />
          <?php endif ?>
        </div>                
        <div class="form-group col-md-3">
          <label>Section/Branch</label>
          <select class="form-control" name="section_id" id="section_id"  >
              <option value="0"></option>
              <?=getOption('e_departments','id','title',$data['section_id'],array('parentid'=>$this->session->departmentid))?>
          </select>
        </div>
        <div class="form-group col-md-3">
          <label>Project</label>
          <select class="form-control" name="project_id" id="project_id" required  >
              <option value=""></option>
              <?=getOption('e_projects','id','title',$data['project_id'])?>
          </select>
        </div>
      
      <div class="form-group col-md-3">
        <label class="control-label">Generated By</label>
        <?php $gb=getField('e_admin',array("userid"=>$data['generated_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$gb?>"  />
        <input type="hidden" id="generated_by" name="generated_by" class="form-control" value="<?=$data['generated_by']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Genereated On</label>
        <input type="text" id="generated_on" name="generated_on" class="form-control default_datetimepicker" value="<?=$data['generated_on']?>" />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Request Status</label>
        <select class="form-control" name="approved_status" id="approved_status" required >
          <option value="Draft">Draft</option>
          <option value="Submitted"  <?php if($data['approved_status']=='Submitted') echo 'selected' ?>>Submitted</option>
          <?php if($this->session->role!='level1'):?>
          <option value="Approved"  <?php if($data['approved_status']=='Approved') echo 'selected' ?>>Approved</option>
          <?php endif?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Approved By</label>
        <?php $ab=''; if($data['approved_by']>0) $ab=getField('e_admin',array("userid"=>$data['approved_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$ab?>"/>
        <input type="hidden" id="approved_by" name="approved_by" class="form-control" value="<?=$data['approved_by']?>"  />
      </div>
      <div class="form-group col-md-12">
        <label>Remarks/Additional Info</label>
        <textarea class="form-control" name="bom_remarks" id="bom_remarks"><?=$data['bom_remarks']?></textarea>
      </div>
      
    <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<?php endif ?>


<?php if ($viewType == "Restock"):  
 if($data['generated_on']=='') $data['generated_on']=date("Y-m-d H:i",time());
  if(!$data['generated_by']>0) $data['generated_by']=$_SESSION['userid']; 
  if(!$data['approved_by']>0) $data['approved_by']=0; 
  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  
  <div class="row">
      <div class="form-group col-md-3">
          <label>Restock Request Against:</label>
          <select class="form-control" name="instrument" id="instrument" onChange="getsingleFieldAjax('instrument_no','parentid',this.value,'notable')" required >
              <option value=""></option>
              <option value="MATS" <?php if($data['instrument']=='MATS') echo 'selected'?>>MAT</option>
              <option value="TMPISSUES" <?php if($data['instrument']=='TMPISSUES') echo 'selected'?>>TempIssue(s)</option>
              <option value="MISNO" <?php if($data['instrument']=='MISNO') echo 'selected'?>>MIS No</option>
          </select>
        </div>                
        <div class="form-group col-md-3">
          <label id="xlbl">Select</label>
          <select class="form-control" name="instrument_no" id="instrument_no" required  >
              <option value="<?=$data['instrument_no']?>"><?=$data['instrument_no']?></option>
          </select>
        </div>
        
      
      <div class="form-group col-md-3">
        <label class="control-label">Generated By</label>
        <?php $gb=getField('e_admin',array("userid"=>$data['generated_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$gb?>"  />
        <input type="hidden" id="generated_by" name="generated_by" class="form-control" value="<?=$data['generated_by']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Genereated On</label>
        <input type="text" id="generated_on" name="generated_on" class="form-control default_datetimepicker" value="<?=$data['generated_on']?>" />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Request Status</label>
        <select class="form-control" name="approved_status" id="approved_status" required >
          <option value="Draft">Draft</option>
          <option value="Submitted"  <?php if($data['approved_status']=='Submitted') echo 'selected' ?>>Submitted</option>
          <?php if($this->session->role!='level1'):?>
          <option value="Approved"  <?php if($data['approved_status']=='Approved') echo 'selected' ?>>Approved</option>
          <?php endif?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Approved By</label>
        <?php $ab=''; if($data['approved_by']>0) $ab=getField('e_admin',array("userid"=>$data['approved_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$ab?>"/>
        <input type="hidden" id="approved_by" name="approved_by" class="form-control" value="<?=$data['approved_by']?>"  />
      </div>
      <div class="form-group col-md-12">
        <label>Remarks/Additional Info</label>
        <textarea class="form-control" name="bom_remarks" id="bom_remarks"><?=$data['bom_remarks']?></textarea>
      </div>
      
    <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<?php endif ?>


<?php if ($viewType == "Release"):  
 if($data['generated_on']=='') $data['generated_on']=date("Y-m-d H:i",time());
  if(!$data['generated_by']>0) $data['generated_by']=$_SESSION['userid']; 
  if(!$data['approved_by']>0) $data['approved_by']=0; 
  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  
  <div class="row">
  
  <div class="form-group col-md-3">
        <label class="control-label">Ask from User</label>
        <select class="form-control" name="askfrom" id="askfrom" required >
              <option value=""></option>
              <?=getOption('e_admin','userid','name',$data['askfrom'],array('userid>'=>1))?>
          </select>
      </div>
      
      <div class="form-group col-md-3">
          <label>Restock Request Against:</label>
          <select class="form-control" name="instrument" id="instrument" onChange="getsingleFieldAjaxR('instrument_no','parentid',this.value,'notable')" required >
              <option value=""></option>
              <option value="ADV" <?php if($data['instrument']=='ADV') echo 'selected'?>>Adv. Booking(s)</option>
              <!--<option value="MMR" <?php if($data['instrument']=='MMR') echo 'selected'?>>Mat. Mov(s)</option>-->
          </select>
        </div>                
        <div class="form-group col-md-3">
          <label id="xlbl">Select</label>
          <select class="form-control" name="instrument_no" id="instrument_no" required  >
              <option value="<?=$data['instrument_no']?>"><?=$data['instrument_no']?></option>
          </select>
        </div>
        
        
      
      <div class="form-group col-md-3">
        <label class="control-label">Generated By</label>
        <?php $gb=getField('e_admin',array("userid"=>$data['generated_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$gb?>"  />
        <input type="hidden" id="generated_by" name="generated_by" class="form-control" value="<?=$data['generated_by']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Genereated On</label>
        <input type="text" id="generated_on" name="generated_on" class="form-control default_datetimepicker" value="<?=$data['generated_on']?>" />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Request Status</label>
        <select class="form-control" name="approved_status" id="approved_status" required >
          <option value="Draft">Draft</option>
          <option value="Submitted"  <?php if($data['approved_status']=='Submitted') echo 'selected' ?>>Submitted</option>
          <?php if($this->session->role!='level1'):?>
          <option value="Approved"  <?php if($data['approved_status']=='Approved') echo 'selected' ?>>Approved</option>
          <?php endif?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Approved By</label>
        <?php $ab=''; if($data['approved_by']>0) $ab=getField('e_admin',array("userid"=>$data['approved_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$ab?>"/>
        <input type="hidden" id="approved_by" name="approved_by" class="form-control" value="<?=$data['approved_by']?>"  />
      </div>
      <div class="form-group col-md-12">
        <label>Remarks/Additional Info</label>
        <textarea class="form-control" name="bom_remarks" id="bom_remarks"><?=$data['bom_remarks']?></textarea>
      </div>
      
    <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<?php endif ?>

<?php if ($viewType == "Matmove"):  
 if($data['generated_on']=='') $data['generated_on']=date("Y-m-d H:i",time());
  if(!$data['generated_by']>0) $data['generated_by']=$_SESSION['userid']; 
  if(!$data['approved_by']>0) $data['approved_by']=0; 
  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  
  <div class="row">
      <div class="form-group col-md-3">
          <label>Title/Name (Optional)</label>
          <input type="text" id="mm_title" name="mm_title" class="form-control" value="<?=$data['mm_title']?>" />
        </div>                
	<div class="form-group col-md-3">
        <label class="control-label">Ask from User</label>
        <select class="form-control" name="askfrom" id="askfrom" >
              <option value="">Ask From Store</option>
              <?=getOption('e_admin','userid','name',$data['askfrom'],array('userid>'=>1))?>
          </select>
      </div>
        
      
      <div class="form-group col-md-3">
        <label class="control-label">Generated By</label>
        <?php $gb=getField('e_admin',array("userid"=>$data['generated_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$gb?>"  />
        <input type="hidden" id="generated_by" name="generated_by" class="form-control" value="<?=$data['generated_by']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Genereated On</label>
        <input type="text" id="generated_on" name="generated_on" class="form-control default_datetimepicker" value="<?=$data['generated_on']?>" />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Request Status</label>
        <select class="form-control" name="approved_status" id="approved_status" required >
          <option value="Draft">Draft</option>
          <option value="Submitted"  <?php if($data['approved_status']=='Submitted') echo 'selected' ?>>Submitted</option>
          <?php if($this->session->role!='level1'):?>
          <option value="Approved"  <?php if($data['approved_status']=='Approved') echo 'selected' ?>>Approved</option>
          <?php endif?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Approved By</label>
        <?php $ab=''; if($data['approved_by']>0) $ab=getField('e_admin',array("userid"=>$data['approved_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$ab?>"/>
        <input type="hidden" id="approved_by" name="approved_by" class="form-control" value="<?=$data['approved_by']?>"  />
      </div>
      <div class="form-group col-md-12">
        <label>Remarks/Additional Info</label>
        <textarea class="form-control" name="bom_remarks" id="bom_remarks"><?=$data['bom_remarks']?></textarea>
      </div>
      
    <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<?php endif ?>
<?php if ($viewType == "FixedAssets"):  
 if($data['generated_on']=='') $data['generated_on']=date("Y-m-d",time());
  if(!$data['generated_by']>0) $data['generated_by']=$_SESSION['userid']; 
  if(!$data['approved_by']>0) $data['approved_by']=0; 
  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  
  <div class="row">

        <div class="form-group col-md-3">
          <label>For Period</label>
          <input type="text" id="period" name="period" class="form-control" value="<?=$data['period']?>" required  />
        </div>
      
      <div class="form-group col-md-3">
        <label class="control-label">Generated By</label>
        <?php $gb=getField('e_admin',array("userid"=>$data['generated_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$gb?>"  />
        <input type="hidden" id="generated_by" name="generated_by" class="form-control" value="<?=$data['generated_by']?>"  />
      </div>
      <div class="form-group col-md-2">
        <label class="control-label">Genereated On</label>
        <input type="text" id="generated_on" name="generated_on" class="form-control default_datetimepicker" value="<?=$data['generated_on']?>" />
      </div>
      <div class="form-group col-md-2">
        <label class="control-label">Request Status</label>
        <select class="form-control" name="approved_status" id="approved_status" required >
          <option value="Draft">Draft</option>
          <option value="Submitted"  <?php if($data['approved_status']=='Submitted') echo 'selected' ?>>Submitted</option>
          <?php if($this->session->role!='level1'):?>
          <option value="Approved"  <?php if($data['approved_status']=='Approved') echo 'selected' ?>>Approved</option>
          <?php endif?>
        </select>
      </div>
      <div class="form-group col-md-2">
        <label class="control-label">Approved By</label>
        <?php $ab=''; if($data['approved_by']>0) $ab=getField('e_admin',array("userid"=>$data['approved_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$ab?>"/>
        <input type="hidden" id="approved_by" name="approved_by" class="form-control" value="<?=$data['approved_by']?>"  />
      </div>
      <div class="form-group col-md-12">
        <label>Remarks/Additional Info</label>
        <textarea class="form-control" name="bom_remarks" id="bom_remarks"><?=$data['bom_remarks']?></textarea>
      </div>
      
    <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<?php endif ?>

<?php if ($viewType == "DelChallan"):  
 if($data['generated_on']=='') $data['generated_on']=date("Y-m-d H:i",time());
 if($data['dc_date']=='') $data['dc_date']=date("Y-m-d",time()); else $data['dc_date']=date("Y-m-d",strtotime($data['dc_date']));
  if(!$data['generated_by']>0) $data['generated_by']=$_SESSION['userid']; 
  if(!$data['approved_by']>0) $data['approved_by']=0; 
  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  
  <div class="row">
  		<div class="form-group col-md-3">
        <label class="control-label">DC Date</label>
        <input type="text" id="dc_date" name="dc_date" class="form-control default_datetimepicker" value="<?=$data['dc_date']?>" />
      	</div>
      
      	<div class="form-group col-md-3">
        <label class="control-label">Sale Order No</label>
        <input type="text" id="sale_order_no" name="sale_order_no" class="form-control" value="<?=$data['sale_order_no']?>" />
      	</div>
        
      	<div class="form-group col-md-3">
          <label>Customer</label>
          <select class="form-control" name="customer_id" id="customer_id"  required >
              <option value=""></option>
              <?=getOption('e_customers','id','title',$data['customer_id'],array())?>
          </select>
        </div>
        
        <div class="form-group col-md-3">
        <label class="control-label">Carrier Name</label>
        <input type="text" id="carrier_name" name="carrier_name" class="form-control" value="<?=$data['carrier_name']?>" />
      	</div>
      
      	<div class="form-group col-md-3">
        <label class="control-label">Generated By</label>
        <?php $gb=getField('e_admin',array("userid"=>$data['generated_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$gb?>"  />
        <input type="hidden" id="generated_by" name="generated_by" class="form-control" value="<?=$data['generated_by']?>"  />
      	</div>
      	<div class="form-group col-md-3">
        <label class="control-label">Genereated On</label>
        <input type="text" id="generated_on" name="generated_on" class="form-control default_datetimepicker" value="<?=$data['generated_on']?>" />
      	</div>
      	<div class="form-group col-md-3">
        <label class="control-label">DC Status</label>
        <select class="form-control" name="approved_status" id="approved_status" required >
          <option value="Draft">Draft</option>
          <option value="Submitted"  <?php if($data['approved_status']=='Submitted') echo 'selected' ?>>Submitted</option>
          <?php if($this->session->role!='level1'):?>
          <option value="Approved"  <?php if($data['approved_status']=='Approved') echo 'selected' ?>>Approved</option>
          <?php endif?>
        </select>
      	</div>
      	<div class="form-group col-md-3">
        <label class="control-label">Approved By</label>
        <?php $ab=''; if($data['approved_by']>0) $ab=getField('e_admin',array("userid"=>$data['approved_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$ab?>"/>
        <input type="hidden" id="approved_by" name="approved_by" class="form-control" value="<?=$data['approved_by']?>"  />
      	</div>
      	<div class="form-group col-md-12">
        <label>Remarks/Additional Info</label>
        <textarea class="form-control" name="bom_remarks" id="bom_remarks"><?=$data['bom_remarks']?></textarea>
      	</div>
      
    	<div class="buttons" style="margin: 0 auto;">
      	<button type="submit" class="btn btn-info">Save</button>
    	</div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<?php endif ?>

<?php if ($viewType == "Bom"):  
 if($data['generated_on']=='') $data['generated_on']=date("Y-m-d H:i",time());
  if(!$data['generated_by']>0) $data['generated_by']=$_SESSION['userid']; 
  if(!$data['approved_by']>0) $data['approved_by']=0; 
  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  
  <div class="row">
      <div class="form-group col-md-3">
          <label>Department</label>
          <?php if($this->session->role=='admin'):?>          
          <select class="form-control" name="department_id" id="department_id" onChange="getsingleFieldAjax('section_id','parentid',this.value,'e_departments')" required >
              <option value=""></option>
              <?=getOption('e_departments','id','title',$data['department_id'],array('parentid'=>0))?>
          </select>
          <?php else :?>
          <input class="form-control" disabled="disabled" value="<?=getField('e_departments',array('id'=>$this->session->departmentid),'title')?>"  />
          <input class="form-control" type="hidden" id="department_id" name="department_id" value="<?=$this->session->departmentid?>" required />
          <?php endif ?>
        </div>                
        <div class="form-group col-md-3">
          <label>Section/Branch</label>
          <select class="form-control" name="section_id" id="section_id"  >
              <option value="0"></option>
              <?=getOption('e_departments','id','title',$data['section_id'],array('parentid'=>$this->session->departmentid))?>
          </select>
        </div>
        <div class="form-group col-md-3">
          <label>Project</label>
          <select class="form-control" name="project_id" id="project_id" required  >
              <option value=""></option>
              <?=getOption('e_projects','id','title',$data['project_id'])?>
          </select>
        </div>
      
      <div class="form-group col-md-3">
        <label class="control-label">Generated By</label>
        <?php $gb=getField('e_admin',array("userid"=>$data['generated_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$gb?>"  />
        <input type="hidden" id="generated_by" name="generated_by" class="form-control" value="<?=$data['generated_by']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Genereated On</label>
        <input type="text" id="generated_on" name="generated_on" class="form-control default_datetimepicker" value="<?=$data['generated_on']?>" />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">BOM Status</label>
        <select class="form-control" name="approved_status" id="approved_status" required >
          <option value="Draft"  <?php if($data['approved_status']=='Draft') echo 'selected' ?>>Draft</option>
          <option value="Submitted"  <?php if($data['approved_status']=='Submitted') echo 'selected' ?>>Submitted</option>
          <?php if($this->session->role!='level1'):?>
          <option value="Approved"  <?php if($data['approved_status']=='Approved') echo 'selected' ?>>Approved</option>
          <?php endif?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Approved By</label>
        <?php $ab=''; if($data['approved_by']>0) $ab=getField('e_admin',array("userid"=>$data['approved_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$ab?>"/>
        <input type="hidden" id="approved_by" name="approved_by" class="form-control" value="<?=$data['approved_by']?>"  />
      </div>
      <div class="form-group col-md-12">
        <label>Remarks/Additional Info</label>
        <textarea class="form-control" name="bom_remarks" id="bom_remarks"><?=$data['bom_remarks']?></textarea>
      </div>
      
    <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>

<?php if ($viewType == "Mat"):  
 if($data['generated_on']=='') $data['generated_on']=date("Y-m-d h:i:s",time());
  if(!$data['generated_by']>0) $data['generated_by']=$_SESSION['userid']; 
  if(!$data['approved_by']>0) $data['approved_by']=0; 
  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  
  <div class="row">
      <div class="form-group col-md-3">
          <label>Department</label>
          <?php if($this->session->role=='admin'):?>          
          <select class="form-control" name="department_id" id="department_id" onChange="getsingleFieldAjax('section_id','parentid',this.value,'e_departments')" required >
              <option value=""></option>
              <?=getOption('e_departments','id','title',$data['department_id'],array('parentid'=>0))?>
          </select>
          <?php else :?>
          <input class="form-control" disabled="disabled" value="<?=getField('e_departments',array('id'=>$this->session->departmentid),'title')?>"  />
          <input class="form-control" type="hidden" id="department_id" name="department_id" value="<?=$this->session->departmentid?>" required />
          <?php endif ?>
        </div>                
        <div class="form-group col-md-3">
          <label>Section/Branch</label>
          <select class="form-control" name="section_id" id="section_id"  >
              <option value="0"></option>
              <?=getOption('e_departments','id','title',$data['section_id'],array('parentid'=>$this->session->departmentid))?>
          </select>
        </div>
        <div class="form-group col-md-3">
          <label>Project</label>
          <select class="form-control" name="project_id" id="project_id" required  >
              <option value=""></option>
              <?=getOption('e_projects','id','title',$data['project_id'])?>
          </select>
        </div>
      
      <div class="form-group col-md-3">
        <label class="control-label">Generated By</label>
        <?php $gb=getField('e_admin',array("userid"=>$data['generated_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$gb?>"  />
        <input type="hidden" id="generated_by" name="generated_by" class="form-control" value="<?=$data['generated_by']?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Genereated On</label>
        <input type="text" id="generated_on" name="generated_on" class="form-control default_datetimepicker" value="<?=$data['generated_on']?>" />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">MAT Status</label>
        <select class="form-control" name="approved_status" id="approved_status" required >
          <option value="Draft"  <?php if($data['approved_status']=='Draft') echo 'selected' ?>>Draft</option>
          <option value="Submitted"  <?php if($data['approved_status']=='Submitted') echo 'selected' ?>>Submitted</option>
          <?php if($this->session->role!='level1'):?>
          <option value="Approved"  <?php if($data['approved_status']=='Approved') echo 'selected' ?>>Approved</option>
          <?php endif?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Approved By</label>
        <?php $ab=''; if($data['approved_by']>0) $ab=getField('e_admin',array("userid"=>$data['approved_by']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$ab?>"/>
        <input type="hidden" id="approved_by" name="approved_by" class="form-control" value="<?=$data['approved_by']?>"  />
      </div>
      <div class="form-group col-md-12">
        <label>Remarks/Additional Info</label>
        <textarea class="form-control" name="bom_remarks" id="bom_remarks"><?=$data['bom_remarks']?></textarea>
      </div>
      
    <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>
<?php if ($viewType == "Projects"):  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  <div class="row">
            	<div class="form-group col-md-8">
                	<label class="control-label">Title</label>
                	<input type="text" id="title" name="title" class="form-control" value="<?=$data['title']?>"   autocomplete="off" required />
              	</div>
                <div class="form-group col-md-4">
        <label class="control-label">Date of Execution</label>
        <input type="text" id="executiondate" name="executiondate" class="form-control default_datetimepicker" value="<?=$data['executiondate']?>"   autocomplete="off"   />
      </div>
                
               <!-- <div class="form-group col-md-3">
                  <label>Department</label>
                  <select class="form-control" name="departmentid" id="departmentid" onChange="getsingleFieldAjax('sectionid','parentid',this.value,'e_departments')" required >
                      <option value=""></option>
                      <?=getOption('e_departments','id','title',$data['departmentid'],array('parentid'=>0))?>
                  </select>
              	</div>                
                <div class="form-group col-md-3">
                  <label>Section/Branch</label>
                  <select class="form-control" name="sectionid" id="sectionid"  >
                      <option value=""></option>
                      <?=getOption('e_departments','id','title',$data['sectionid'], array('parentid'=>$data['departmentid']))?>
                  </select>
              	</div>-->
       <div class="form-group col-md-12">
        <label>Description</label>
        <textarea class="form-control" name="description" id="summernote"><?=$data['description']?></textarea>
      </div>
  			
  <div class="form-group col-md-3">
        <label>Status</label>
        <select class="form-control" name="status" id="status" required >
          <option value="New">New</option>
          <option value="In Process" <?php if($data['status']=='In Process') echo 'selected' ?>>In Process</option>
          <option value="Completed" <?php if($data['status']=='Completed') echo 'selected' ?>>Completed</option>
        </select>
      </div>
      
     <div class="form-group col-md-3">
        <label class="control-label">&nbsp;</label><br>
                	<button type="submit" class="btn btn-primary btn-xl">Save</button>
      </div>
      
      
	
      
      
      
      
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>

<?php if ($viewType == "Assemblies"):  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  <div class="row">
  <div class="form-group col-md-3">
        <label>Project</label>
        <input type="hidden" value="<?=$data['projectid']?>" id="projectid" name="projectid">
        <select class="form-control"  disabled >
            <option value="0"></option>
          	<?= getOption('e_projects','id','title',$data['projectid'])?>
            </select>
    </div>
  	<div class="form-group col-md-3">
        <label class="control-label">Assembly Name</label>
        <input type="text" id="title" name="title" class="form-control" value="<?=$data['title']?>" required   />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Serial No</label>
        <input type="text" id="serialno" name="serialno" class="form-control" value="<?=$data['serialno']?>"    />
      </div>
      <div class="form-group col-md-3">
        <label>Parent Assembly</label>
        <select class="form-control" name="parentassembly" id="parentassembly" required >
            <option value="0"></option>
          	<?= getOption('e_assemblies','id','title',$data['parentassembly'],array('projectid'=>$data['projectid']))?>
            </select>
    </div>
       <div class="form-group col-md-12">
        <label>Description</label>
        <textarea class="form-control" name="description" id="summernote"><?=$data['description']?></textarea>
      </div>
      
     
      <div class="form-group col-md-2">
        <label class="control-label"></label><br />
        <button type="submit" class="btn btn-info">Save</button>
      </div>
      
   
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>


<?php if ($viewType == "Feedback"):  //extract($data);?>
<div class="row">
	<div class="form-control" style="margin-bottom:2px">
        <div class="form-group col-md-6">
                <label class="control-label"><strong>Name : </strong></label> <?=$data['name']?>
        </div>
        <div class="form-group col-md-3">
            <label class="control-label"><strong>Email : </strong></label> <?=$data['email']?>
         </div>
         <div class="form-group col-md-3">
            <label class="control-label"><strong>Date : </strong></label> <?=$data['tdate']?>
         </div>
     </div>
     <br /><br />
     <div class="form-control" style="margin-bottom:2px">
         <div class="form-group col-md-12">
            <label class="control-label"><strong>Subject : </strong></label> <?=$data['subject']?>
         </div>
     </div>
     <br /><br />
     <div class="form-control col-md-9" style="margin-bottom:2px">
         <div class="form-group col-md-12" style="min-height:250px">
            <label class="control-label"><strong>Message : </strong></label> <?=$data['message']?>
         </div>
     </div>
     <div class="form-group col-md-3">
        <?php if (!empty($data['image'])): ?>
		           	 		<img class="img-thumbnail" src="<?=base_url()?>assets/img/<?=$data['image']?>" style="float:left; width:100%">
		       <?php endif ?>
      </div>
</div>
<script type="text/javascript">
function printDiv() {
	var data = $("#bar-parent2");
    var mywindow = window.open('', 'PRINT');
    mywindow.document.write('<html><head><title></title>');
    mywindow.document.write('<link rel="stylesheet" href="<?=base_url()?>assets/plugins/select2/css/select2-bootstrap.min.css" />');
    mywindow.document.write('</head><body >');
    mywindow.document.write(data.html());
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
   mywindow.print();
   mywindow.close();
    // window.close('getSlip');
	return true;
}

</script>
<?php endif ?>

<?php if ($viewType == "FeedbackForm"):  //extract($data);?>
<div class="row">
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">
  <div class="row">
      <div class="form-group col-md-3">
        <label class="control-label">Date</label>
        <input type="text" id="tdate" name="tdate" class="form-control default_datetimepicker" value="<?=$data['tdate']?>"  required autocomplete="off"   />
      </div>
                                              
      <div class="form-group col-md-3">
        <label class="control-label">Name</label>
        <?php
		if($data['name']=='')$data['name']=getField('e_admin',array('userid'=>$_SESSION['userid']),'name');
		?>
        <input type="text" id="name" name="name" class="form-control" value="<?=$data['name']?>" readonly required  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Email</label>
        <?php
		if($data['email']=='')$data['email']=getField('e_admin',array('userid'=>$_SESSION['userid']),'email');
		?>
        <input type="email" id="email" name="email" class="form-control" value="<?=$data['email']?>" readonly  required  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Phone</label>
        <?php
		if($data['phone']=='')$data['phone']=getField('e_admin',array('userid'=>$_SESSION['userid']),'contactno');
		?>
        <input type="text" id="phone" name="phone" class="form-control" value="<?=$data['phone']?>"  required  />
      </div>
      <div class="form-group col-md-12">
        <label class="control-label">Subject</label>
        <input type="text" id="subject" name="subject" class="form-control" value="<?=$data['subject']?>"  />
      </div>
      <div class="form-group col-md-12">
        <label class="control-label">Description</label>
        <textarea name="message" id="message" rows="5" class="form-control" style="width:100%"><?=$data['message']?></textarea>
      </div>
      <div class="form-group col-md-6">
        <label class="control-label">Image</label>
        <input type="file" id="image" name="image" class="form-control" />
      </div>
      <div class="form-group col-md-6">
        <?php if (!empty($data['image'])): ?>
		           	 		<img class="img-thumbnail" src="<?=base_url()?>assets/img/<?=$data['image']?>" style="float:left; width:60px">
		       <?php endif ?>
      </div>
      
      <div class="buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
      
   
  </div>
</form>
</div>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
<?php endif ?>




<?php if ($viewType == "ItemAllocation"):  
if($action=='add' && $val>0){
	$data['item_id']		= $val; $val=0; 
}
else if($action=='edit' && $val>0){
	$data['item_id']		= $val; $val=$data['id']; 
}

//extract($data);?>
<form name="allocForm" id="allocForm" method="post" enctype="multipart/form-data"  onsubmit="return form_alloc_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="alloc_by" name="alloc_by">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="<?=$action?>" id="action" name="action">

  <div class="row">
    <div class="form-group col-md-3">
    
        <label>Item Name</label>
        <input type="text" disabled class="form-control" value="<?=getField('e_items',array("id"=>$data['item_id']),'item_name')?>"  />
        <input type="hidden" id="item_id" name="item_id" class="form-control" value="<?=$data['item_id']?>" required   />
      
    </div>
    <div class="form-group col-md-6">
        <label><strong>Path</strong></label><br />
        <span><?=$this->AdminModel->getItemPath($data['item_id'])?></span>
        
    </div>
    
  
    <div class="form-group col-md-3">
        <?php
		$snos= $this->AdminModel->getField('e_items',array('id'=>$data['item_id']),'item_serial_no');
		$assignedSnos= $this->AdminModel->getRows('e_items_alloc',array('item_id'=>$data['item_id'],'instrument<>'=>'restock','serial_no<>'=>""));
		$restockedSnos= $this->AdminModel->getRows('e_items_alloc',array('item_id'=>$data['item_id'],'instrument'=>'restock','serial_no<>'=>""));
		$rStocked= $allocateds='';
		foreach($assignedSnos as $a ){
			if($a['serial_no']!='')
			$allocateds.=$a['serial_no'].',';
		}
		foreach($restockedSnos as $a ){
			if($a['serial_no']!='')
			$rStocked.=$a['serial_no'].',';
		}
		
		
		if($allocateds && ($this->session->departmentid==234 || $this->session->role=='admin')){
		$xxx = explode(",",rtrim($allocateds, ','));
		echo '<label><strong>Avail. To Restock - Item Serial No(s)</strong></label><br />';
			
			foreach($xxx as $s){
				if(!trim($s) || strpos($rStocked, $s) !== false){}
				else
					echo '<div style="padding:5px; float:left; margin:1px" onclick="setSerail(\''.$s.'\')">'.$s.'</div> ';
			}
		}
		$qn=0;
		if($data['instrument']=='mat')
		$qn = getItemAvailQty($data['item_id']);//getField('e_matitem',array('mat_id'=>$data['instrument_no'],'item_id'=>$data['item_id']),'qty_needed');
		if($data['instrument']=='restock')
		$qn = getField('e_restockitems',array('adv_id'=>$data['instrument_no'],'item_id'=>$data['item_id']),'qty_restocked');
		if($data['instrument']=='tmpissue')
		$qn = getItemAvailQty($data['item_id']);//getField('e_tempissueitems',array('adv_id'=>$data['instrument_no'],'item_id'=>$data['item_id']),'qty_needed');
		if($data['instrument']=='adv')
		$qn = getItemAvailQty($data['item_id']);//getField('e_advitems',array('adv_id'=>$data['instrument_no'],'item_id'=>$data['item_id']),'qty_needed');
		if($data['instrument']=='mmov')
		$qn = getField('e_mmitems',array('adv_id'=>$data['instrument_no'],'item_id'=>$data['item_id']),'qty_needed');
		if($data['instrument']=='release')
		$qn = getField('e_releaseitems',array('adv_id'=>$data['instrument_no'],'item_id'=>$data['item_id']),'qty_needed');
        ?>
      
    </div>
    
    <div class="form-group col-md-3">
        <label>Instrument Type</label>
        <input type="text" id="instrument" name="instrument" class="form-control" value="<?=$data['instrument']?>" readonly  />
    </div>
  <div class="form-group col-md-3">
        <label class="control-label">Instrument No</label>
        <input type="text" id="instrument_no" name="instrument_no" class="form-control" value="<?=$data['instrument_no']?>" readonly   />
    </div>
    
    <!--<div class="form-group col-md-2">
        <label class="control-label">Qty Demanded</label>
        <input type="text" class="form-control" value="< ?=$qn?>" disabled   />
    </div>-->
    <div class="form-group col-md-3">
        <label class="control-label">Qty Issued/Alloc  &nbsp;
        <?php 
		$xy = $this->AdminModel->getRow('e_items_alloc',array('item_id'=>$data['item_id'],'instrument'=>$data['instrument'],'instrument_no'=>$data['instrument_no']));
		if($data['item_quantity']=='') $data['item_quantity']=$xy['item_quantity'];
		if($data['serial_no']=='') $data['serial_no']=$xy['serial_no'];
		
		if($this->session->departmentid==234) :?>
        <a href="<?=base_url()?>Home/po">Create PO</a>
        <?php endif?>
        </label>
       <input type="number" id="item_quantity" min="0" max="<?=$qn?>" name="item_quantity" class="form-control" value="<?=$data['item_quantity']?>" required />
    </div>
   <div class="form-group col-md-3">
        <label class="control-label">Enter Serial No</label>
        <input type="text" id="serial_no" name="serial_no" class="form-control" value="<?=$data['serial_no']?>"    /> <br />
        <?php
		//echo $snos.'<br>';
		//echo $allocateds.'...<br>';
		if($this->session->departmentid==234 || $this->session->role=='admin'){
			echo '<label><strong>Avail Item Serial No(s)</strong></label><br />';
			foreach(explode(",",$snos) as $s){
				if($s && strpos($allocateds, $s) !== false && strpos($rStocked, $s) === false){}
				else
					echo '<div style="padding:5px; float:left; margin:1px" onclick="setSerail(\''.$s.'\')">'.$s.'</div> ';
			}
		}
		?>       
    </div>

      <div class="form-group col-md-2">
        <label class="control-label"></label><br />
        <button type="submit" class="btn btn-info">Save</button>
      </div>
      
   
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>

<?php endif ?>

<?php if ($viewType == "Receivings"):  
 if($data['generated_on']=='') $data['generated_on']=date("Y-m-d H:i",time());
  if(!$data['generated_by']>0) $data['generated_by']=$_SESSION['userid']; 
  if(!$data['approved_by']>0) $data['approved_by']=0; 

  //extract($data);?>
<form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="<?=$tableName?>" id="tableName" name="tableName">
  <input type="hidden" value="<?=$key?>" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$val?>" id="<?=$key?>" name="<?=$key?>">
  <input type="hidden" value="edit" id="action" name="action">
  
 
      <div class="form-group col-md-3">
        <label class="control-label">Username </label>
        <?php $gb=getField('e_admin',array("userid"=>$_SESSION['userid']),'name');?>
        <input type="text" disabled="disabled" class="form-control" value="<?=$gb?>"  />
      </div>
      <div class="form-group col-md-3">
        <label class="control-label">Password</label>
        <input type="password" id="passwordxx" name="passwordxx" class="form-control" required />
      </div>
       
       <div class="form-group col-md-3">
        <label class="control-label">Status</label>
        <select id="store_remarks" name="store_remarks" class="form-control" required>
        <option value=""></option>
        <option value="Completed">Received</option>
        <option value="Partially Received">Partially Received</option>
        <option value="Pending">Pendning</option>
        <option value="Restocked">Restocked</option>    
        <option value="Reserved">Reserved</option>     
        </select>
      </div>
           
      <div class="form-group col-md-3">
        <label>Remarks/Additional Info</label>
        <textarea class="form-control" name="bom_remarks" id="bom_remarks" required ><?=$data['bom_remarks']?></textarea>
      </div>
      
    <div class="form-group buttons" style="margin: 0 auto;">
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
<script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
<?php endif ?>

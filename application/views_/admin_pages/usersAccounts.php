<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName = "usersAccounts";
$calledName = "usersAccounts";
$heading = "users Accounts";
$key = "user_id";
$tableName = "e_users";
$fields = array('name','username','email','phone');
$indexes = $fields;

 ?>
<!-- BEGIN .app-main -->
				<div class="app-main">
					<!-- BEGIN .main-heading -->
					<header class="main-heading">
						<div class="container-fluid">
							<div class="row">
								<div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
									<div class="page-icon">
										<i class="icon-laptop_windows"></i>
									</div>
									<div class="page-title">
										<h5><?=ucfirst($heading)?></h5>
										<h6 class="sub-heading">Welcome to <?=ucfirst($heading)?> Management</h6>
									</div>
								</div>
								
							</div>
						</div>
					</header>
					<!-- END: .main-heading -->
					<!-- BEGIN .main-content -->
					<div class="main-content">
						<div class="row gutters">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
								<div class="card">
									<div class="card-header"><?=ucfirst($heading)?> </div>
									<div class="card-body">
										<?php echo show_flash_data();?>
										<span id="flash_data"></span>
										<table id="datatable" class="table table-striped table-bordered">
											<thead class="spThead">
												<tr>
													<?php foreach ($fields as $field): ?>
														<th><?=ucfirst($field)?></th>
													<?php endforeach ?>
													<th>Child Accounts</th>
													<th>Image</th>
													<th></th>
												</tr>
											</thead>
											<tbody class="spTbody">
											<?php foreach ($data as $row):?>
											  <tr id="row<?=$row[$key]?>">
												<?php foreach ($indexes as $index): ?>
													<td><?=$row[$index]?></td>
												<?php endforeach ?>
												<td><?php echo $childs =  countRows('e_childs',array('user_id'=>$row[$key])) ?></td>
												<td style="padding: 0"><img src='<?=base_url().'assets/cms_images/thumbnail/'.$row['image']?>' width=100></td>

												<td style="padding: 2px;text-align: ;" width="10%">
													
														<a href="<?=base_url().'Admin/childs/'.$row[$key]?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Childs"  class="btn btn-outline-success btn-sm"><span class="icon-eye2" ></span>
													</a>
													
													
													<button data-toggle="tooltip" data-placement="top" title="" data-original-title="Update Record"  onclick="update(<?=$row[$key]?>)" class="btn btn-outline-success btn-sm"><span class="icon-enlarge" ></span>
													</button>
													<button class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Record" onclick="deleteRecord('<?=$key?>',<?=$row[$key]?>,'<?=$tableName?>')"><span class="icon-trash2" ></span>
													</button>
															
												</td>
											</tr>
											<?php endforeach ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
					</div>
					<!-- END: .main-content -->
				</div>
				<!-- END: .app-main -->
<script type="text/javascript">
function update(id)
{

	var viewType  = 'edit<?=$calledName?>';
	var tableName = '<?=$tableName?>';
	var key       = '<?=$key?>';
	$("#extraModel").modal();
	$("#extraTitle").html("Edit <?=$heading?>");
	$("#extraBoday").html("<h2 class='text-center'><span class='icon-spinner3'></span></h2>");
	$.post("<?php echo base_url(); ?>Function_control/getView2",{viewType,id,tableName,key}, 
		function(data)
		{
			$("#extraBoday").html(data); 	
			//$('#myModal').modal('hide');
		});

}
function deleteRecord(key,value,tableName)
{
	var file = 'image';
	var file_path = 'assets/cms_images/';
	if (confirm("Are you sure to delete?"))
	{  
		$.post("<?php echo base_url(); ?>Function_control/deleteRecord",{key,value,tableName,file,file_path}, 
			function(data)
			{
				//alert(data);
				$("#row"+value).hide();
				$("#flash_data").html(data); location.reload();
			});
	}
}
function addNew()
{
	$("#addModal").modal();
}

function form_validation() 
{   
   //var value = CKEDITOR.instances['editor1'].getData();
   //$("#editorReplace").val(value);
    var form = new FormData($('#myForm')[0]);
    $.ajax({
      type: "POST",
      url: '<?php echo base_url(); ?>Function_control/saveSimple',
      data: form,
      cache: false,
      contentType: false,
      processData: false,
      success: function(res)
      {
      	//alert(res);
       window.setTimeout(window.location='<?php echo base_url(); ?>Admin/<?=$pageName?>',1000);
      }
       
    });
      return false;
}  

function update_validation() 
{   
   //var value = CKEDITOR.instances['editor1'].getData();
   //$("#editorReplace").val(value);
    var form = new FormData($('#updateForm')[0]);
    $.ajax({
      type: "POST",
      url: '<?php echo base_url(); ?>Function_control/updateSimple',
      data: form,
      cache: false,
      contentType: false,
      processData: false,
      success: function(res)
      {
      	//alert(res);
       window.setTimeout(window.location='<?php echo base_url(); ?>Admin/<?=$pageName?>',1000);
      }
       
    });
      return false;
}    
</script>

<!-- modals area -->
<div class="modal fade " id="extraModel" tabindex="-1" role="dialog" aria-labelledby="extraModel12" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content brad0">
			<div class="modal-header brad0">
				<h5 class="modal-title" id="extraTitle">Add New</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
			</div>
			<div class="modal-body" id="extraBoday">
			</div>					
		</div>
	</div>
</div>

<div class="modal fade " id="addModal" tabindex="-1" role="dialog" aria-labelledby="map12" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content brad0">
			<div class="modal-header brad0">
				<h5 class="modal-title" id="addTitle">Add New</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
			</div>
			<div class="modal-body" id="addBody">
				<form method="POST" name="myForm" id="myForm"  enctype="multipart/form-data" onsubmit="return form_validation()">
					<input type="hidden" value="<?=$tableName?>" name="tableName">
		           
		           <div class="form-group row">
		            <div class="col-md-6">
		              <label class="col-form-label">Name/نام</label>
		              <input type="text" required name="name"  class="form-control-sm form-control">
		            </div>
		            <div class="col-md-6">
		               <label class="col-form-label">Email</label>
		              <input type="email"  name="email"  class="form-control-sm form-control">
		            </div>
		          </div>
		          <div class="form-group row">
		            <div class="col-md-6">
		              <label class="col-form-label">Username</label>
		              <input type="username" required name="username"  class="form-control-sm form-control">
		            </div>
		            <div class="col-md-6">
		               <label class="col-form-label">Password</label>
		              <input type="text" required name="password"  class="form-control-sm form-control">
		            </div>
		          </div>  
		          

		           <div class="form-group row">
		            <div class="col-md-6">
					<label class="col-form-label">Image</label>
		              <input type="file" id="image"  name="files"  class="form-control-sm input-sm form-control">
		            </div>
		             
		           </div>
		           	 <span class="preview-area"></span>
		         
		 		<div class="form-group row">
		            <div class="col-md-12">
		     
		                 <button type="submit" class="float-right btn btn-primary btn-sm ">Save Data</button>
		            </div>
		          </div>  
		        </form>
			</div>					
		</div>
	</div>
</div>


<script type="text/javascript">
  var inputLocalFont = document.getElementById("image");
inputLocalFont.addEventListener("change",previewImages,false);
function previewImages(){
    var fileList = this.files;
    var anyWindow = window.URL || window.webkitURL;
$('.preview-area').html('');
        for(var i = 0; i < fileList.length; i++){
          var objectUrl = anyWindow.createObjectURL(fileList[i]);
          $('.preview-area').append('<span class="span2"><img class="img-thumbnail" src="' + objectUrl + '" alt="" style="width:111px;height:100px"></span>');
          window.URL.revokeObjectURL(fileList[i]);
        }
    }

</script>

<?php $this->load->view('admin_layout/footer');?>

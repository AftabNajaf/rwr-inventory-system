<?php $this->load->view('admin_layout/header');?>

<div class="page-content-wrapper">
  <div class="page-content">
    <div class="page-bar">
      <div class="page-title-breadcrumb">
        <div class=" pull-left">
          <div class="page-title">Employee Categories </div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
          <li><i class="fa fa-home"></i>&nbsp;<a class="parent-item" href="<?=base_url()?>">Home</a>&nbsp; </li>
        </ol>
      </div>
    </div>
    <!-- ====================Form Controls ===================== -->
    <div class="row" style="margin-top:70px">
      <div class="col-md-12 col-sm-12">
        <div class="card  card-box">
          <div class="card-head">
            <div style="padding:0px 20px">
              <button type="button" class="btn btn-round btn-warning" id="btn_popup">Add New</button>
            </div>
          </div>
          <div class="card-body "> <?php echo show_flash_data();?> <span id="flash_data"></span>
            <div class="table-wrap">
              <div class="table-responsive table-bordered" style="font-size:13px">
                <table class="table display product-overview mb-30" id="example2">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Title</th>
                      <th>Short Code</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $count=0; foreach ($eroles as $row): extract($row); $count++;?>
                    <tr id="row<?=$id?>">
                      <td><?=$count?></td>
                      <td><?=$title?></td>
                      <td><?=$shortcode?></td>
                      <td><button class="btn btn-tbl-edit btn-xs" data-original-title="Update Record"  onclick="update(<?=$id?>)"> <i class="fa fa-pencil"></i></button>
                        <button class="btn btn-tbl-delete btn-xs" data-original-title="Delete Record"  onclick="deleteRecord('id',<?=$id?>,'e_roles')"> <i class="fa fa-trash-o "></i> </button></td>
                    </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="popup" style="display:none; position:absolute;top:0px;z-index:9999;">
      <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="card card-box">
            <div class="card-head" style="width:100%; position:relative;top:0; height:34px;cursor:move" id="popup_bar">
              <span id="btn_close" style="float:right;cursor:pointer;padding-right:6px;">[X]</span> 
              <header id="exTitle">Add New</header></div>
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
<script type="text/javascript">
function deleteRecord(key,value,tableName)
{
	if (confirm("Are you sure to delete?"))
	{  
		$.post("<?php echo base_url(); ?>Function_control/deleteRecord",{key,value,tableName}, 
			function(data)
			{
				//alert(data);
				$("#row"+value).hide();
				 location.reload();
			});
	}
}

function update(id)
{
	var viewType = 'editUserRole';
	
	$("#exTitle").html("Edit Category / Role");
	$.post("<?php echo base_url(); ?>Function_control/getView",{viewType,id}, 
		function(data)
		{
			$("#bar-parent2").html(data); 	
			$("#btn_popup").trigger('click');; 
		});

}

function form_validation() 
{   
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
       location.reload();
      }
       
    });
      return false;
}  

function update_validation() 
{   var form = new FormData($('#updateForm')[0]);
    $.ajax({
      type: "POST",
      url: '<?php echo base_url(); ?>Function_control/updateSimple',
      data: form,
      cache: false,
      contentType: false,
      processData: false,
      success: function(res)
      {
      	location.reload();
      }
       
    });
      return false;
} 

</script>
<script>
(function(){
  var SCROLL_WIDTH = 350;

  var btn_popup = document.getElementById("btn_popup");
  var popup = document.getElementById("popup");
  var popup_bar = document.getElementById("popup_bar");
  var btn_close = document.getElementById("btn_close");

  //-- let the popup make draggable & movable.
  var offset = { x: 0, y: 0 };

  popup_bar.addEventListener('mousedown', mouseDown, false);
  window.addEventListener('mouseup', mouseUp, false);

  function mouseUp()
  {
    window.removeEventListener('mousemove', popupMove, true);
  }

  function mouseDown(e){
    offset.x = e.clientX - popup.offsetLeft;
    offset.y = e.clientY - popup.offsetTop;
    window.addEventListener('mousemove', popupMove, true);
  }

  function popupMove(e){
    popup.style.position = 'absolute';
    var top = e.clientY - offset.y;
    var left = e.clientX - offset.x;
    popup.style.top = top + 'px';
    popup.style.left = left + 'px';
  }
  //-- / let the popup make draggable & movable.

  window.onkeydown = function(e){
    if(e.keyCode == 27){ // if ESC key pressed
      btn_close.click(e);
    }
  }

  btn_popup.onclick = function(e){
    // reset div position
    popup.style.top = "50px";
   /* popup.style.left = "240px";
    popup.style.width = window.innerWidth - SCROLL_WIDTH + "px";
    popup.style.height = window.innerHeight - SCROLL_WIDTH + "px";*/
    popup.style.display = "block";
  }

  btn_close.onclick = function(e){
    popup.style.display = "none";
  }

}());
</script>
<?php $this->load->view('admin_layout/footer');?>

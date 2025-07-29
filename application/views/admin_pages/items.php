<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName = "items";
$calledName = "Items";
$heading = "Items";
$key = "id";
$tableName = "e_items";
$fields = array('Category Title');//,'Parent Category'
$indexes  = array('title');//,'parentid'

 ?>

<div class="page-content-wrapper">
  <div class="page-content">
    <div class="page-bar">
      <div class="page-title-breadcrumb">
        <div class=" pull-left">
          <div class="page-title">
            <?=ucfirst($heading)?>
          </div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
          <li><i class="fa fa-home"></i>&nbsp;<a class="parent-item" href="<?=base_url()?>">Home</a>&nbsp; </li>
        </ol>
      </div>
    </div>
    <!-- ====================Form Controls ===================== -->
 <div class="row">
      <div class="col-md-12">
       <div class="card-head card-topline-aqua" style="height:50px">
       <?php echo show_flash_data();  ?>
       <button class="btn btn-xs btn-warning" onclick="searchSelectItem('edititem',event)" style="float:right; margin:5px;"><i class="fa fa-pencil"> Edit Item</i></button>
          	<a href="<?=base_url()?>Home/items"><button class="btn btn-xs btn-info" style="float:right; margin:5px"><i class="fa fa-plus"> Add Item</i></button></a>
      
          </div>
      <div class="card card-body">
        <?php $data = $fdata?>
        <form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
  <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
  <input type="hidden" value="e_items" id="tableName" name="tableName">
  <input type="hidden" value="id" id="keyIndex" name="keyIndex">
  <input type="hidden" value="<?=$data['id']?>" id="id" name="id">
  <input type="hidden" value="<?php if($data['id']>0) echo 'edit'; else echo 'add';?>" id="action" name="action">
  <div class="row">
    <div class="form-group col-md-3" style="display:none">
      <label>
      
      Item Category</label>
      <input type="text" disabled class="form-control" value="<?=getField('e_categories',array("id"=>$data['item_category']),'title')?>"  />
      <input type="text" id="item_category" name="item_category" class="form-control" value="<?=$data['item_category']?>" required   />
    </div>
    <div class="form-group col-md-6"><small class="btn btn-sm btn-info" onclick="getNewPath()" style="float:right"><i class="fa fa-refresh"> Item Path</i></small>
      <label>
      <?php if($data['id']<=0):?>
      <button class="btn btn-xs btn-info" onclick="searchSelectItem('add',event)"><i class="fa fa-search primary"></i></button>
      <?php else:?>
      <button class="btn btn-xs btn-warning" onclick="searchSelectItem('edit',event)"><i class="fa fa-search primary"></i></button>
      <?php endif?>
      Item Category&nbsp; </label>
      <select class="form-control select2" name="newparentid" id="newparentid" onchange="getNewPath(this.value)" required >
        <option value=""></option>
        <?=getOption('e_categories','id','title',$data['item_category'])?>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label><strong>&nbsp;</strong></label>
      <br />
      <span  id="newpath"></span> </div>
    <div class="form-group col-md-12">
      <label><strong>Path</strong></label>
      <br />
      <span>
      <?=$this->AdminModel->getCatPath($data['item_category'])?>
      </span> </div>
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
      <label class="control-label">Part No </label> - <small style="color:#00F" id="uniqueuser"></small> <small class="btn btn-sm btn-info" onclick="partnoAvailability()" style="float:right"><i class="fa fa-refresh"></i></small>
      <input type="text" id="part_number" name="part_number" class="form-control" autocomplete="off" onKeyUp="partnoAvailability()" onblur="partnoAvailability()" value="<?=$data['part_number']?>"    />
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
      <a href="<?=base_url()?>assets/lib/<?=$data['doc_list']?>" title="<?=$data['doc_list']?>" target="blank"> <img src="<?=base_url()?>assets/img/docicon.png"  style="float:left; width:60px"></a>
      <?php endif ?>
    </div>
    <div class="form-group col-md-2">
      <label class="control-label"></label>
      <br />
      <button type="submit" class="btn btn-info">Save</button>
    </div>
  </div>
</form>
        </div>
      </div>
 </div>
 	
    <div class="row" id="popup" style="display:none; position:absolute;top:0px;z-index:9999; width:83%">
      <div class="col-md-12 col-sm-12">
        <div class="card card-box">
          <div class="card-head" style="width:100%; position:relative;top:0; height:34px;cursor:move" id="popup_bar">
                <span id="btn_close" style="float:right;cursor:pointer;padding-right:6px;">[X]</span>
            <header id="exTitle">Item/Item Category</header>
          </div>
          <div class="card-body">
          	<div id="bar-parent2"></div>
            <div class="row">
          <div class="col-md-6">
            <div class="card card-topline-aqua">
              <div class="card-head">
                <!--<header>Items<small class="sub-heading">Search/Select</small></header>-->
                <div style="padding:0px 2px">
                  <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
                </div>
              </div>
              <div class="card-body" id="myscrollx"  style="overflow-y: auto; min-height:320px; max-height:320px">
                <!--<span class="context-menu-one btn btn-neutral">right click me</span>-->
                <?php foreach($treeCats as $tree) : extract($tree); ?>
                <span class="accordion-item class<?=$id?>" onclick="getChilds(<?=$id?>)" data-panel-id="nesting-panel<?=$id?>">
                <button class="btn btn-success btn-sm" onclick="add('add',<?=$id?>)" ><i class="fa fa-certificate"></i></button>
                <?=str_pad($id,5,"0",STR_PAD_LEFT)?>
                ----<i class="fa fa-plus-circle"></i>
                <?=$title?>
                </span>
                <div class="accordion-panel" id="nesting-panel<?=$id?>"></div>
                <?php endforeach ?>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card card-topline-aqua">
              <div class="card-head">
                <header>Item Detailes</header>
                <input type="hidden" id="tempselect" name="tempselect" value="" />
                <!--<button type="button" style="float:right" class="btn btn-xs btn-success" onclick="finalSelectItem()" >SELECT</button>-->
              </div>
              <div class="card-body" id="idetails"  style="overflow-y: auto; min-height:320px; max-height:320px"> </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card card-topline-aqua">
              <div class="card-head">
                <header>Search</header>
              </div>
              <div class="card-body" style="overflow-y: auto; min-height:320px; max-height:320px">
                <div class="row">
                  <form action="" method="post" onsubmit="">
                    <input type="text" class="form-control" name="searchcat" id="searchcat" placeholder="Search ...." onkeyup="getSearchedCats()" />
                    <div class="radio" style="margin:3px; float:left">
                      <input type="radio" name="searchopt" id="searchopt1" value="id"  onchange="getSearchedCats()" >
                      <label for="searchopt1" style="padding-right:5px;">MIS# </label>
                    </div>
                    <div class="radio" style="margin:3px; float:left">
                      <input type="radio" name="searchopt" id="searchopt6" value="item_name" onchange="getSearchedCats()" >
                      <label for="searchopt6" style="padding-right:5px;">Name </label>
                    </div>
                    <div class="radio" style="margin:3px; float:left">
                      <input type="radio" name="searchopt" id="searchopt2" value="item_serial_no"  onchange="getSearchedCats()" >
                      <label for="searchopt2" style="padding-right:5px;">Serial </label>
                    </div>
                    <div class="radio" style="margin:3px; float:left">
                      <input type="radio" name="searchopt" id="searchopt3" value="item_description"  onchange="getSearchedCats()" >
                      <label for="searchopt3" style="padding-right:5px;">Desc </label>
                    </div>
                    <div class="radio" style="margin:3px; float:left">
                      <input type="radio" name="searchopt" id="searchopt4" value="item_functionality"  onchange="getSearchedCats()" >
                      <label for="searchopt4" style="padding-right:5px;">Funct </label>
                    </div>
                    <div class="radio" style="margin:3px; float:left">
                      <input type="radio" name="searchopt" id="searchopt5" value="all" checked  onchange="getSearchedCats()" >
                      <label for="searchopt5" style="padding-right:5px;">All </label>
                    </div>
                  </form>
                </div>
                <ul id="aik" style=" background-color:rgb(0 123 255 / 14%); margin-top:5px">
                </ul>
              </div>
            </div>
          </div>
        </div>
          </div>
        </div>
      </div>
    </div>
    
    
    
</div>



<?php $this->load->view('admin_layout/footer');?>
<script type="text/javascript">
function searchSelectItem(vl,event){
	event.preventDefault(); // Prevent default form submission
	$("#action").val(vl);
	$("#btn_popup").trigger('click');
}
function finalSelectItem(){
	
	$("#btn_close").trigger('click');
	if($("#tempselect").val()>0){
		var itemID=$("#tempselect").val();
		$("#item_id").val($("#tempselect").val());
		$.post("<?php echo base_url(); ?>Function_control/getItemName",{itemID}, 
			function(data)
			{  	$("#itm_display").html(data);
		});
	}
	
}
function setNewItemCat(cid=0){
	$("#newparentid").val(cid);
	getNewPath(cid);
}
function getNewPath(catid=0){
	var catid=$("#newparentid").val();
	$.post("<?php echo base_url(); ?>Function_control/trackTempPath",{catid}, 
			function(data)
			{   //alert(data);
				$("#newpath").html(data);
				$("#item_category").val(catid);
			});
	//$("#newpath").html(catid);
}
  
 function getSearchedCats(){
	var tableName 	= '<?=$tableName?>';
	var searchString = $("#searchcat").val();
	var searchOption = $("input[name=searchopt]:checked").val();//$("#searchopt").val();
	if(searchString.length>0){
		//alert(searchOption+searchString);
		$.post("<?php echo base_url(); ?>Function_control/searchForm",{searchString,searchOption}, 
			function(data)
			{   //alert(data);
				$("#aik").html(data);
			});
	}
}
function searchMagic(x){ 
	var tableName 	= '<?=$tableName?>';
	var catid=x;
	addEditItemStock(x);
	$.post("<?php echo base_url(); ?>Function_control/trackCategory",{catid,tableName}, 
			function(data)
			{   //alert(data);
				var arr = data.split(",");
				myFunction(arr);
			});
}

function myFunction(arr) {
  var count = 1;
  var lastitem = arr[arr.length-1];
  jQuery.each(arr, function(index, value) {
    setTimeout(function() { 
						$(".class"+value).click();
							if(value==lastitem){
								$(".class-"+value).css('background-color','#cfcfcf');
								$(".class"+value).css('background-color','#cfcfcf');
								$("#i"+value).css('background-color','#cfcfcf');

									var myElement = document.getElementById('i'+value);
									if(myElement==null)
										myElement = document.getElementsByClassName("class"+value)[0];
									var topPos = myElement.offsetTop;			
									document.getElementById('myscrollx').scrollTop = topPos;
							}
						}, count * 550);
    count++;  
		
  });  
}


function getChilds(parentid){ 
	var tableName 	= '<?=$tableName?>';
	$.post("<?php echo base_url(); ?>Function_control/getChilds",{parentid,tableName}, 
			function(data)
			{   //alert(data);
				$(".accordion-panel").css("background-color", "#fff")
				$("#nesting-panel"+parentid).html(data);
			});
	
}
function getChildItems(parentid){ 
	var tableName 	= '<?=$tableName?>';
	$.post("<?php echo base_url(); ?>Function_control/getChildItems",{parentid,tableName}, 
			function(data)
			{   //alert(parentid);alert(data);
				$(".accordion-panel").css("background-color", "#fff")
				$("#nesting-panel-x"+parentid).html(data);
				
				
			});
	
}
function getChildItemX(parentid){ 
	var tableName 	= '<?=$tableName?>';
	$.post("<?php echo base_url(); ?>Function_control/getChildItemX",{parentid,tableName}, 
			function(data)
			{   //alert(parentid);alert(data);
				$(".accordion-panel").css("background-color", "#fff")
				$("#nesting-panel-"+parentid).html(data);
				
			});
	
}

function deleteRecord__(key,value,tableName)
{	/**** nested / internal onclcik shoud not efect external *****/
	if (!e) var e = window.event;
    e.cancelBubble = true;
    if (e.stopPropagation) e.stopPropagation();
	/********/
	if (confirm("Are you sure to delete?"))
	{  
		$.post("<?php echo base_url(); ?>Function_control/deleteRecord",{key,value,tableName}, 
			function(data)
			{   //alert(data);
				$("#row"+value).hide();
				$(".class-"+value).hide();// location.reload();
				 $("#btn_close").trigger("click");
					loadFilters();
			});
	}
}
function add(action,vxl)
{   
	var perf=$("#action").val();
	if(perf=='edititem'){
		window.location.href = "<?=base_url()?>Home/items/"+vxl;
	}
	if(action=='add' || action=='edit'){
				$("#item_category").val(vxl);
				$("#newparentid").val(vxl);
				$('#newparentid').val(vxl).trigger('change');
				$("#action").val(perf);
				getNewPath(vxl);
				$("#btn_close").trigger('click');
	}
	/*var viewType 	= 'Items';
	var tableName 	= '<?=$tableName?>';
	var key 		= '<?=$key?>'; 
	$("#exTitle").html(action+' Items');
	$.post("<?php echo base_url(); ?>Function_control/getFormsView",{viewType,action,tableName,key,val}, 
		function(data)
		{  	$("#bar-parent2").html(data); 
			$("#btn_popup").trigger('click');
		});
	*/
}

function addEditItemStock(itemID){
	$("#idetails").html("");
	$.post("<?php echo base_url(); ?>Function_control/getItemDetail",{itemID}, 
		function(data)
		{  	$("#idetails").html(data); 
			
		});
}

function getItemValue(){
	if((($("#usd_unit_price").val())*($("#item_quantity").val())))
	$("#item_value").val( (($("#usd_unit_price").val())*($("#item_quantity").val())) );
}

function form_validation() 
{   var xcount =0;
    //if else validation here	

	if($("#uniqueuser").html()=='' || $("#id").val()>0){} else xcount=1; 
	if(xcount==0){
		var form = new FormData($('#myForm')[0]);
		//alert(form.get('title'));
    $.ajax({
      type: "POST",
      url: '<?php echo base_url(); ?>Function_control/crudSimple',
      data: form,
      cache: false,
      contentType: false,
      processData: false,
      success: function(res){ 
	  	//alert(res);
      	//$("#btn_close").trigger("click");
		//location.reload();
		window.location.href = "<?=base_url()?>Home/items/";
	  	
      }
      });
   }
      return false;
}  
 
 
 function partnoAvailability(){
	var uname =$("#part_number").val();
	
		$.post("<?=base_url()?>Function_control/partnoAvailability",{uname}, 
				function(data)
				{	
					$("#uniqueuser").html(data);
				});
	
}

</script>

<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName 		= "itemqty";
$calledName 	= "ItemsStock";
$heading 		= "Fixed Assets Evaluation - Items";
$key 			= "id";
$tableName 		= "e_items";
$fields 		= array('Ser #','Period','Status');//,'Parent Category'
$indexes  		= array('id','period','approved_status');//,'parentid'

$bom 			= $bom[0];
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
        <div class="card card-topline-aqua">
          <div class="card-head">
            <div class="tools col-md-1">
              <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
              <?php if($this->session->role=='level1') :?>
              <button type="button" class="btn btn-xs btn-success" onclick="submitBom(<?=$bom['id']?>,'Submitted')" >Submit for<br />
              Approval</button>
              &nbsp;
              <?php else :?>
              <button type="button" class="btn btn-xs btn-success" onclick="submitBom(<?=$bom['id']?>,'Approved')" > Approved </button>
              <br />
              <br />
              <button type="button" class="btn btn-xs btn-info" onclick="submitBom(<?=$bom['id']?>,'Draft')" >Sendback</button>
              <?php endif?>
            </div>
            <div class="tools col-md-6"> <H5> For Period: 
              <?=$bom['period']?>
              </H5>
            </div>
            <div class="col-md-4">
              <h5> Evaluation No : 
                <?=$bom['id']?>
              </h5>
            </div>
            <style>
#saveXStage tr th {padding:0px; text-align:center; height:20px}
</style>
            <form name="myForm" id="myForm" method="post" enctype="multipart/form-data"  onsubmit="return form_validation()">
              <input type="hidden" value="<?=$_SESSION['userid']?>" id="userid" name="userid">
              <input type="hidden" value="e_fixeditems" id="tableName" name="tableName">
              <input type="hidden" value="id" id="keyIndex" name="keyIndex">
              <input type="hidden" value="0" id="id" name="id">
              <input type="hidden" value="add" id="action" name="action">
              <input type="hidden" class="form-control" name="fixed_id" id="fixed_id" value="<?=$bom['id']?>" required >
              <table id="saveXStage" class="table table-bordered display full-width" >
                <tr>
                  <th width="15%">Item No
                    <button class="btn btn-xs btn-info" onclick="searchSelectItem()" style="float:right"><i class="fa fa-search primary"></i></button></th>
                  <th width="10%">Original Cost</th>
                  <th width="10%">Acq.Date</th>
                  <th width="10%">Dep. Rate</th>
                  <th width="10%">Acc. Dep</th>
                  <th width="10%">Reval Date</th>
                  <th width="10%">Reval Amount</th>
                  <th width="10%">Dep. of Reval</th>
                  <th width="10%">Acc.Dep of Reval</th>
                  <th width="5%">&nbsp;</th>
                </tr>
                <tr>
                  <td><span  class="form-control" name="itm_display" id="itm_display" onclick="searchSelectItem()">Select Item</span>
                    <input type="hidden" class="form-control" name="item_id" id="item_id" placeholder="select Item" required ></td>
                  
                  <td><input type="number" class="form-control" name="orignal_cost" id="orignal_cost" required ></td>
                  <td><input type="text" class="form-control default_datetimepicker" name="acq_date" id="acq_date" required ></td>
                  <td><input type="number" class="form-control" name="dep_rate" id="dep_rate" required ></td>
                  <td><input type="number" class="form-control" name="acc_dep" id="acc_dep" required ></td>
                  <td><input type="text" class="form-control default_datetimepicker" name="reval_date" id="reval_date" ></td>
                  <td><input type="number" class="form-control" name="reval_amount" id="reval_amount" ></td>
                  <td><input type="number" class="form-control" name="reval_dep" id="reval_dep"  ></td>
                  <td><input type="number" class="form-control" name="reval_acc_dep" id="reval_acc_dep"  ></td>

                  <td><input type="submit" class="btn btn-md btn-warning" value="Save" /></td>
                </tr>
              </table>
            </form>
          </div>
          <div id="ixd" onclick="clearToolTip(0)" style="position:absolute;top:0px;z-index:9999; background-color:#FFC; padding:5px; border:1px solid #222; display:none"></div>
          <div class="card-bodyx " style="overflow-y: auto; min-height:320px; max-height:320px">
            <table class="table table-bordered display full-width" >
              <?php 
		  if($bomi){
			  foreach($bomi as $bi){ extract($bi);?>
              <tr id="row<?=$id?>">
                <td width="15%"><span onmouseover="idetailsToolTip(<?=$item_id?>)" onmouseout="clearToolTip(<?=$item_id?>)">
                  <?=getField('e_items',array("id"=>$item_id),'item_name');?>
                  </span></td>
                <td width="10%"><?=$orignal_cost?></td>
                <td width="10%"><?=$acq_date?></td>
                <td width="10%"><?=$dep_rate?></td>
                <td width="10%"><?=$acc_dep?></td>
                <td width="10%"><?=$reval_date?></td>
                <td width="10%"><?=$reval_amount?></td>
                <td width="10%"><?=$reval_dep?></td>
                <td width="10%"><?=$reval_acc_dep?></td>
                <td width="5%"><button class="btn btn-primary btn-xs" onclick="add('edit',<?=$id?>)" > <i class="fa fa-pencil"></i></button>
                  <button class="btn btn-danger btn-xs" onclick="deletBomItem('id',<?=$id?>,'e_fixeditems')"> <i class="fa fa-trash-o "></i></button></td>
              </tr>
              <?php }
		  }
		  ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row" id="popup" style="display:none; position:absolute;top:0px;z-index:9999; width:83%">
    <div class="col-md-12 col-sm-12">
      <div class="card card-box">
        <div class="card-head" style="width:100%; position:relative;top:0; height:34px;cursor:move" id="popup_bar"> <span id="btn_close" style="float:right;cursor:pointer;padding-right:6px;">[X]</span>
          <header id="exTitle">Items</header>
        </div>
        <div class="card-body " id="bar-parent2"></div>
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
                <button type="button" style="float:right" class="btn btn-xs btn-success" onclick="finalSelectItem()" >SELECT</button>
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
<?php $this->load->view('admin_layout/footer');?>
<script type="text/javascript">
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
									if(myElement==null)
										myElement = document.getElementsByClassName("class"+value)[0];
									var topPos = myElement.offsetTop;			
									document.getElementById('myscrollx').scrollTop = topPos;						
						}
						}, count * 350);
    count++;  
	
  });
}

$(function() {
        $.contextMenu({
            selector: '.context-menu-one', 
            items: {
                "edit": {name: "Edit", icon: "edit"},
                "delete": {name: "Delete", icon: "delete"}
            }
        });  
});
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
function getsingleFieldAjax(inputfield,targetfield,value,table){
		//alert(inputfield+"  "+targetfield+"  "+value+"  "+table);
		$.post("<?=base_url()?>Registration/getsingleFieldAjax",{targetfield,value,table}, 
				function(data)
				{   //alert(data);
					$("#"+inputfield).html(data);
				});
	}
	
function deleteRecord(key,value,tableName)
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
function deletBomItem(key,value,tableName)
{	
	if (confirm("Are you sure to delete?"))
	{  
		$.post("<?php echo base_url(); ?>Function_control/deleteRecord",{key,value,tableName}, 
			function(data)
			{   //alert(data);
				$("#row"+value).hide();
			});
	}
}
function addbom(action,val)
{   var viewType 	= 'Bom';
	var tableName 	= 'e_bom';
	var key 		= 'id'; 
	$("#exTitle").html(action+' BOM');
	$.post("<?php echo base_url(); ?>Function_control/getFormsView",{viewType,action,tableName,key,val}, 
		function(data)
		{  	 
			$("#bar-parent2").html(data); 
			$("#btn_popup").trigger('click');
		});
}

function submitBom(val,action)
{   var tableName 	= 'e_restock';
	var field		= 'approved_status';
	var fieldval	= action;
	if(confirm("Are you sure to prceed?")){
		$.post("<?php echo base_url(); ?>Function_control/submitCase",{tableName, val,field,fieldval}, 
		function(data)
		{  	
			window.location.href = "<?=base_url()?>Home/restock";
		});
	}
}

function addEditItemStock(itemID){
	$("#idetails").html(""); 
	$("#tempselect").val("");
	$.post("<?php echo base_url(); ?>Function_control/getItemDetail",{itemID}, 
		function(data)
		{  	if(data!="")
			$("#tempselect").val(itemID);
			$("#idetails").html(data); 
		});
}
function idetailsToolTip(itemID){ 
	$.post("<?php echo base_url(); ?>Function_control/getItemDetail",{itemID}, 
		function(data)
		{  	$('#ixd').html(data); 
			$('#ixd').show(); 
		});
}
function clearToolTip(itemID){ 
	$('#ixd').html("");
	$('#ixd').hide(); 
}

function searchSelectItem(){
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

function getItemValue(){
	if((($("#usd_unit_price").val())*($("#item_quantity").val())))
	$("#item_value").val( (($("#usd_unit_price").val())*($("#item_quantity").val())) );
}

function form_validation() 
{   var xcount =0;
    //if else validation here	
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
		location.reload();
	  	
      }
      });
   }
      return false;
}  
 
</script>

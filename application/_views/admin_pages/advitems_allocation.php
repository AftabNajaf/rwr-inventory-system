<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName 		= "itemqty";
$calledName 	= "ItemsStock";
$heading 		= "Advance Bookings - Items";
$key 			= "id";
$tableName 		= "e_items";
$fields 		= array('Request #','Department','Section','Project','Status');//,'Parent Category'
$indexes  		= array('id','department_id','section_id','project_id','approved_status');//,'parentid'

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
            <div class="tools col-md-1" style="display:none">
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
            <div class="tools col-md-3"> DEPT / SEC :<br>
              <?=getField('e_departments',array('id'=>$bom['department_id']),'title')?>
              /
              <?=getField('e_departments',array('id'=>$bom['section_id']),'title')?>
            </div>
            <div class="tools col-md-5"> PROJECT :<br>
              <?=getField('e_projects',array('id'=>$bom['project_id']),'title')?>
            </div>
            <div class="col-md-2">
              <b>Req. No : <?=$bom['id']?></b><br>
              [ <strong><?=getField('e_admin',array('userid'=>$bom['generated_by']),'name');?></strong> ]
              </h5>
            </div>
            <style>
#saveXStage tr th {padding:0px; text-align:center; height:20px}
</style>
              <table id="saveXStage" class="table table-bordered display full-width" >
                <tr>
                  <th width="15%">Assembly</th>
                  <th width="15%">Sub Assembly</th>
                  <th width="15%">Item No</th>
                  <th width="10%">Qty Needed</th>
                  <th width="15%">Req. Date</th>
                  <th width="10%">Item Notes</th>
                  <th width="10%">Qty Issued/Alloc</th>
                  <th width="10%">&nbsp;</th>
                </tr>
                
              </table>
          </div>
          <div id="ixd" onclick="clearToolTip(0)" style="position:absolute;top:0px;z-index:9999; background-color:#FFC; padding:5px; border:1px solid #222; display:none"></div>
          <div class="card-bodyx " style="overflow-y: auto; min-height:320px; max-height:320px">
            <table class="table table-bordered display full-width" >
              <?php 
		  if($bomi){
			  foreach($bomi as $bi){ extract($bi);?>
              <tr id="row<?=$id?>">
                <td width="15%"><?=getField('e_assemblies',array("id"=>$assembly_id),'title');?></td>
                <td width="15%"><?=getField('e_assemblies',array("id"=>$sub_assembly_id),'title');?></td>
                <td width="15%"><span onclick="idetailsToolTip(<?=$item_id?>)" onclick="clearToolTip(<?=$item_id?>)">
                  <?=$item_id.'-'.getField('e_items',array("id"=>$item_id),'item_name');?>
                  </span></td>
                <td width="10%"><?=$qty_needed?></td>
                <td width="15%"><?=$req_date?></td>
                <td width="10%"><?=$item_notes?></td>
                <td width="10%"><?=getField('e_items_alloc',array("instrument_no"=>$bom['id'],"item_id"=>$item_id,"instrument"=>"adv"),'item_quantity');?></td>
                <td>
                  
                  <?php if($this->session->role!='level1' || $bom['approved_status']!='Approved') :?>
                  <button class="btn btn-danger btn-xs" onclick="deletBomItem('id',<?=$id?>,'e_advitems')"> <i class="fa fa-trash-o "></i></button>
                  <?php endif?>
                  <button class="btn btn-success btn-xs" onclick="addEditItemAlloc('add',<?=$bom['id']?>,<?=$item_id?>,'adv')" > <i class="fa fa-gear"></i>Alloc. Item</button>
                  </td>
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
{   var tableName 	= 'e_adv';
	var field		= 'approved_status';
	var fieldval	= action;
	if(confirm("Are you sure to prceed?")){
		$.post("<?php echo base_url(); ?>Function_control/submitCase",{tableName, val,field,fieldval}, 
		function(data)
		{  	
			window.location.href = "<?=base_url()?>Home/adv";
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

function addEditItemAlloc(action,pono,itemID,instrument){
	var viewType 	= 'ItemAllocation';
	var tableName 	= 'e_items_alloc';
	var key 		= 'id'; 
	var val 		= itemID;
	$("#exTitle").html(action+' <?=$calledName?>');
	$.post("<?php echo base_url(); ?>Function_control/getFormsView",{viewType,action,tableName,key,val,pono,instrument}, 
		function(data)
		{  	$("#bar-parent2").html(data); 
			$("#btn_popup").trigger('click');
		});
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

function form_alloc_validation() 
{   var xcount =0;
    //if else validation here	
	if(xcount==0){
		var form = new FormData($('#allocForm')[0]);
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
function updateitem(id,tableName)
{   var itemid 	= id;
		$.post("<?php echo base_url(); ?>Function_control/updateSelectedItem",{tableName,id}, 
		function(data)
		{  	 
			var obj = JSON.parse( data ); 
			$("#qty_needed").val(obj.qty_needed);
			$("#assembly_id").val(obj.assembly_id);
			$("#sub_assembly_id").val(obj.sub_assembly_id);
			$("#item_notes").val(obj.item_notes);
			$("#item_id").val(obj.item_id);
			
			$("#id").val(obj.id);
			$("#action").val('edit');
			$("#itm_display").html(obj.item_id+" - "+obj.ititle);
			$("#req_date").val(obj.req_date);
		});
}
</script>

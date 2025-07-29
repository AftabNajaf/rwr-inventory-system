<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName 		= "itemqty";
$calledName 	= "GrnItemsStock";
$heading 		= "Good Received Note - GRN";
$key 			= "id";
$tableName 		= "e_items";
$fields 		= array('BOM #','Department','Section','Project','Status');//,'Parent Category'
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
            
            <div class="col-md-2">
              <h5><strong>GRN No : </strong>
                <?=$bom['id']?>
              </h5>
            </div>
            <div class="col-md-8">
              <h5><strong>Supplier : </strong>
                <?=getField('e_suppliers',array('id'=>$bom['supplier_id']),'title')?>
              </h5>
            </div>
            
          </div>
          <div id="ixd" onclick="clearToolTip(0)" style="position:absolute;top:0px;z-index:9999; background-color:#FFC; padding:5px; border:1px solid #222; display:none"></div>
          <div class="card-bodyx " style="overflow-y: auto; min-height:320px; max-height:320px">
            <table class="table table-bordered display full-width" >
            	<th width="30%">Item Name</th>
            	
                <th width="20%">Supplier Ref</th>
                <th width="20%">Part Number</th>
                <th width="10%">Qty Ordered</th>
                <th width="10%">Qty Received</th>
                <th width="10%">&nbsp;</th>
              <?php 
		  if($bomi){
			  foreach($bomi as $bi){ extract($bi);
			  $recQty = getField('e_items_qty',array("grn_no"=>$bom['id'],'item_id'=>$item_id),'item_quantity');
			  ?>
              <tr id="row<?=$id?>">
                <td width="30%" onclick="idetailsToolTip(<?=$item_id?>)" onclicm="clearToolTip(<?=$item_id?>)"><span>
                  <?=getField('e_items',array("id"=>$item_id),'item_name');?>
                  </span></td>
                
                <td width="20%"><?=getField('e_items',array("id"=>$item_id),'supplier_ref')?></td>
                <td width="20%"><?=getField('e_items',array("id"=>$item_id),'part_number')?></td>
                <td width="10%"><?=$qty_needed?></td>
                <td width="10%"><?=$recQty?></td>
                <td>
                <?php  if($recQty>=1):?>
                <button class="btn btn-info btn-xs" onclick="addEditItemStock('edit',<?=$bom['id']?>,<?=$item_id?>)" > <i class="fa fa-gear"></i>Received Item</button>
                <?php else:?>
                <button class="btn btn-success btn-xs" onclick="addEditItemStock('add',<?=$bom['id']?>,<?=$item_id?>)" > <i class="fa fa-gear"></i>Received Item</button>
				<?php endif ?>
                
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
								myElement = document.getElementsByClassName("class"+value)[0];var myElement = document.getElementById('i'+value);
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
function deletPoItem(key,value,tableName)
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


function add(action,val)
{   var itemID=val;
		$("#item_id").val($("#tempselect").val());
		$.post("<?php echo base_url(); ?>Function_control/getPOItemDetailById",{itemID}, 
			function(data)
			{  	var obj = JSON.parse(data); 
				$("#itm_display").html(obj['item_id']);
				$("#supplier_ref").val(obj['supplier_ref']);
				$("#qty_needed").val(obj['qty_needed']);
				$("#part_number").val(obj['part_number']);
				$("#item_id").val(obj['xitem']);
				$("#action").val('edit');
				$("#id").val(val);
		});
}

function submitBom(val,action)
{   var tableName 	= 'e_po';
	var field		= 'approved_status';
	var fieldval	= action;
	if(confirm("Are you sure to prceed?")){
		$.post("<?php echo base_url(); ?>Function_control/submitCase",{tableName, val,field,fieldval}, 
		function(data)
		{  	
			window.location.href = "<?=base_url()?>Home/po";
		});
	}
}

function addEditItemStock(action,pono,itemID){
	var viewType 	= '<?=$calledName?>';
	var tableName 	= 'e_items_qty';
	var key 		= 'id'; 
	var val 		= itemID;
	$("#exTitle").html(action+' <?=$calledName?>');
	$.post("<?php echo base_url(); ?>Function_control/getFormsView",{viewType,action,tableName,key,val,pono}, 
		function(data)
		{  	$("#bar-parent2").html(data); 
			$("#btn_popup").trigger('click');
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
		$.post("<?php echo base_url(); ?>Function_control/getPOItemDetail",{itemID}, 
			function(data)
			{  	var obj = JSON.parse(data); 
				$("#itm_display").html(obj['item_id']);
				$("#supplier_ref").val(obj['supplier_ref']);
				$("#part_number").val(obj['part_number']);
				
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

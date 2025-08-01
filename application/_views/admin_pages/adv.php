<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName = "itemqty";
$calledName = "ItemsStock";
$heading = "Advance Bookings";
$key = "id";
$tableName = "e_items";
$fields = array('AB Request #','Department','Section','Project','Generated On','Generated By','Status');//,'Parent Category'
$indexes  = array('id','department_id','section_id','project_id','generated_on','generated_by','approved_status');//,'parentid'

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
            <!--<header>< ?=$heading?> <small class="sub-heading">Management</small></header>-->
            <div style="padding:0px 20px">
            <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
            <?php  $privileges=$this->session->privileges;?>
			<?php if(strpos($privileges,'advcreate')!==false):?>
              <button type="button" class="btn btn-round btn-warning" onclick="addbom('add',0)" >Add New</button>
            <?php endif?>
            </div>
            <div class="tools">
            <!--<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>-->
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <!--<a class="t-close btn-color fa fa-times" href="javascript:;"></a> --></div>
          </div>
          <div class="card-body ">
            <table id="saveStage" class="table-bordered display full-width" >
              <thead>
                <tr>
                        <?php foreach ($fields as $field): ?>
                            <th><?=ucfirst($field)?></th>
                        <?php endforeach ?>
                        <th></th>
                 </tr>
              </thead>
              <tbody>
						<?php foreach ($boms as $row):?>
                        	<tr id="row<?=$row[$key]?>">
                        		<?php foreach ($indexes as $index): 
								if(($index=='department_id' || $index=='section_id' )&& $row[$index]>0)
									$row[$index]= getField('e_departments',array('id'=>$row[$index]),'title');
								if($index=='project_id'&& $row[$index]>0) $row[$index]= getField('e_projects',array('id'=>$row[$index]),'title');
								if($index=='generated_by') $row[$index]= getField('e_admin',array("userid"=>$row['generated_by']),'name');
								?>
                        			<td><?=$row[$index]?></td>
                        		<?php endforeach ?>
                        
                                <td style="padding: 2px;text-align: center;" width="15%">
                                
                             
                               
                                 
                                 <?php if($this->session->role!='admin' && $row['approved_status']=='Approved') :?>
                                <button class="btn btn-warning btn-xs" onclick="window.open(
  '<?=base_url()?>Pdfgenerator/downloadAdvBooking/<?=$row[$key]?>',
  '_blank')" >
                                <i class="fa fa-eye"></i></button>
                                
                                <?php if($row['store_remarks']!='') :?>
                                    <button class="btn btn-primary btn-xs" onclick="setReceivings('e_adv','<?=$row['generated_by']?>','id',<?=$row[$key]?>)" >
                                <i class="fa fa-gear"> Receivings  <br />[<?=$row['store_remarks']?>]</i></button>
                                    <?php endif?>
                                    
                                    
                                <?php else : ?>
                                <button class="btn btn-success btn-xs" onclick="location.href = '<?=base_url()?>Home/advitems/<?=$row[$key]?>'" >
                                <i class="fa fa-plus"> Items</i></button>
                                
                                <button class="btn btn-warning btn-xs" onclick="window.open(
  '<?=base_url()?>Pdfgenerator/downloadAdvBooking/<?=$row[$key]?>',
  '_blank')" >
                                <i class="fa fa-eye"></i></button>
                                
                                <button class="btn btn-primary btn-xs" onclick="addbom('edit',<?=$row[$key]?>)" >
                                <i class="fa fa-pencil"></i></button>
                                 
                                 <button class="btn btn-danger btn-xs" onclick="deleteRecordx('<?=$key?>',<?=$row[$key]?>,'e_adv')">
								  <i class="fa fa-trash-o "></i></button>    
                                 <?php endif?>
                        </td>
                        </tr>
                        
                        <?php endforeach ?>
                        </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
  <div class="row" id="popup" style="display:none; position:absolute;top:0px;z-index:9999; width:83%">
      <div class="col-md-12 col-sm-12">
        <div class="card card-box">
          <div class="card-head" style="width:100%; position:relative;top:0; height:34px;cursor:move" id="popup_bar"> <span id="btn_close" style="float:right;cursor:pointer;padding-right:6px;">[X]</span>
            <header id="exTitle">card-title</header>
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
function addbom(action,val)
{   var viewType 	= 'AdvBooking';
	var tableName 	= 'e_adv';
	var key 		= 'id'; 
	$("#exTitle").html(action+' '+viewType);
	$.post("<?php echo base_url(); ?>Function_control/getFormsView",{viewType,action,tableName,key,val}, 
		function(data)
		{  	 
			$("#bar-parent2").html(data); 
			$("#btn_popup").trigger('click');
		});
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

<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName = "itemqty";
$calledName = "ItemsStock";
$heading = "MMR by User";
$key = "id";
$tableName = "e_items";
$fields = array('MM #','Req from','Generated On', ' --');//,'Parent Category'
$indexes  = array('id','generated_by','generated_on','bom_remarks');//,'parentid'

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
								if($index=='generated_by') $row[$index]= getField('e_admin',array("userid"=>$row['generated_by']),'name');
								/*if($index=='store_remarks'){
									$tblx = 'e_mm'; $tblxi='e_mmitems'; $tblxi_id='adv_id';
									$qtNeeded = $this->AdminModel->getSumRows($tblxi,'qty_needed',array($tblxi_id=>$row[$key]));
									$qtIssued = $this->AdminModel->getSumRows('e_items_alloc','item_quantity',array('instrument_no'=>$row[$key],'instrument'=>'mmov'));
									if($qtNeeded==$qtIssued && $qtIssued>0)
										$this->AdminModel->updateRow($tblx ,array('id'=>$row[$key]),array('store_remarks'=>'Completed'));
									if($qtNeeded>$qtIssued && $qtIssued>0)
										$this->AdminModel->updateRow($tblx ,array('id'=>$row[$key]),array('store_remarks'=>'Partially Completed'));
									if($qtIssued==0){
										$this->AdminModel->updateRow($tblx ,array('id'=>$row[$key]),array('store_remarks'=>''));
										
										$row[$index]= getField($tblx ,array('id'=>$row[$key]),'store_remarks');
										if($row[$index]=='')
										$row[$index]='Pending';
										}
								}*/
								
								?>
                        			<td><?=$row[$index]?></td>
                        		<?php endforeach ?>
                        
                                <td style="padding: 2px;text-align: center;" width="15%">
                               <?php if($this->session->role!='admin' && $row['approved_status']=='Approved') :?>
                                <button class="btn btn-warning btn-xs" onclick="window.open(
  '<?=base_url()?>Pdfgenerator/downloadMatmov/<?=$row[$key]?>',
  '_blank')" >
                                <i class="fa fa-eye"></i></button>
                                
                                <?php if($row['approved_status']=='Approved'&& $this->session->userid==$row['askfrom'] && $row['bom_remarks']=='' ) :?>
                                <button class="btn btn-success btn-xs" onclick="location.href='<?=base_url()?>Home/matmoveitems_allocation_user/<?=$row[$key]?>'">
                                <i class="fa fa-plus"> Items</i></button>
                                <?php endif?>                                
                                <?php else:?>
                                <button class="btn btn-success btn-xs" onclick="location.href='<?=base_url()?>Home/matmoveitems/<?=$row[$key]?>'">
                                <i class="fa fa-plus"> Items</i></button>
                                
                                <button class="btn btn-warning btn-xs" onclick="window.open(
  '<?=base_url()?>Pdfgenerator/downloadMatmov/<?=$row[$key]?>',
  '_blank')" >
                                <i class="fa fa-eye"></i></button>
                                
                                <button class="btn btn-primary btn-xs" onclick="addbom('edit',<?=$row[$key]?>)" >
                                <i class="fa fa-pencil"></i></button>
                                 
                                 <button class="btn btn-danger btn-xs" onclick="deleteRecordx('<?=$key?>',<?=$row[$key]?>,'e_mm')">
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

function getsingleFieldAjax(inputfield,targetfield,value,table){
		//alert(inputfield+"  "+targetfield+"  "+value+"  "+table);
		$.post("<?=base_url()?>Registration/getsingleFieldAjax",{targetfield,value,table}, 
				function(data)
				{   //alert(data);
					$("#"+inputfield).html(data);
				});
	}

function addbom(action,val)
{   var viewType 	= 'Matmove';
	var tableName 	= 'e_mm';
	var key 		= 'id'; 
	$("#exTitle").html(action+' Mat.Movement');
	$.post("<?php echo base_url(); ?>Function_control/getFormsView",{viewType,action,tableName,key,val}, 
		function(data)
		{  	 
			$("#bar-parent2").html(data); 
			$("#btn_popup").trigger('click');
		});
}


function deleteRecordx(key,value,tableName)
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

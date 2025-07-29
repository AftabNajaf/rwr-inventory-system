<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName = "feedback";
$calledName = "Feedback";
$heading = "Feedback";
$key = "id";
$tableName = "e_contact_us";
$fields = array('Name','Subject','Email','Phone','Date');
$indexes = array('name','subject','email','phone','tdate');

 ?>
<div class="page-content-wrapper">
  <div class="page-content">
    <div class="page-bar">
      <div class="page-title-breadcrumb">
        <div class=" pull-left">
          <div class="page-title"><?=ucfirst($heading)?></div>
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
            <header><?=$heading?> <small class="sub-heading">Management</small></header>
            <div style="padding:0px 20px">
            <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>            
              <button type="button" class="btn btn-round btn-warning" onclick="addFeedback('add',0)" >Add New</button>
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
						<?php foreach ($data as $row):?>
                        	<tr id="row<?=$row[$key]?>">
                        		<?php foreach ($indexes as $index): 
									if($index == 'image')
										$row[$index] = '<img class="img-thumbnail" src="'.base_url().'assets/img/'.$row[$index].'" style="width:50px">';
								?>
                        			<td><?=$row[$index]?></td>
                        		<?php endforeach ?>
                        
                                <td style="padding: 2px;text-align: center;" width="15%">
                                <button class="btn btn-success btn-xs" onclick="addFeedback('edit',<?=$row[$key]?>)" >
                                <i class="fa fa-pencil"></i></button>
                                
                                <button class="btn btn-primary btn-xs" onclick="add('edit',<?=$row[$key]?>)" >
                                <i class="fa fa-eye"></i></button>
                                 
                                 <button class="btn btn-danger btn-xs" onclick="deleteRecord('<?=$key?>',<?=$row[$key]?>,'<?=$tableName?>')">
								  <i class="fa fa-trash-o "></i></button>                   

                                 <!--<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>-->
                                 
                        </td>
                        </tr>
                        
                        <?php endforeach ?>
                        </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
	
  <!-- =============== End Form Control =============================== -->
    <div class="row" id="popup" style="display:none; position:absolute;top:0px;z-index:9999; width:83%">
      <div class="col-md-12 col-sm-12">
        <div class="card card-box">
          <div class="card-head" style="width:100%; position:relative;top:0; height:34px;cursor:move" id="popup_bar"> <span id="btn_close" style="float:right;cursor:pointer;padding-right:6px;">Close [X]</span>
            <header id="exTitle">card-title</header>
          </div>
          <div class="card-body " id="bar-parent2"></div>
        </div>
      </div>
    </div>
</div>
</div>
<?php $this->load->view('admin_layout/footer');?>
<script type="text/javascript">

function deleteRecord(key,value,tableName)
{
	if (confirm("Are you sure to delete?"))
	{  
		$.post("<?php echo base_url(); ?>Function_control/deleteRecord",{key,value,tableName}, 
			function(data)
			{   //alert(data);
				$("#row"+value).hide();
				// location.reload();
				 $("#btn_close").trigger("click");
					loadFilters();
			});
	}
}
function add(action,val)
{   var viewType 	= '<?=$calledName?>';
	var tableName 	= '<?=$tableName?>';
	var key 		= '<?=$key?>'; 
	$("#exTitle").html('<?=$heading?> <button class="btn btn-warning btn-xs " onclick="printDiv()" ><i class="fa fa-print"></i></button>');
	$.post("<?php echo base_url(); ?>Function_control/getFormsView",{viewType,action,tableName,key,val}, 
		function(data)
		{  	$("#bar-parent2").html(data); 
			$("#btn_popup").trigger('click');
		});
}
function addFeedback(action,val)
{   var viewType 	= '<?=$calledName?>Form';
	var tableName 	= '<?=$tableName?>';
	var key 		= '<?=$key?>'; 
	$("#exTitle").html('<?=$heading?>');
	$.post("<?php echo base_url(); ?>Function_control/getFormsView",{viewType,action,tableName,key,val}, 
		function(data)
		{  	$("#bar-parent2").html(data); 
			$("#btn_popup").trigger('click');
		});
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


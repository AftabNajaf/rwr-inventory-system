<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName 	= "vaccinations";
$calledName = "Vaccinations";
$heading 	= "Daily Vaccinations";
$key 		= "id";
$tableName = "e_vaccinations";
$fields = array('Citizen No.','Passport, Guardian Card No','Name','Gender','Age','Citizen Mobile No.','Vaccination','Dosage','Vaccination Date.');
$indexes  = array('citizenid','passportno','name','gender','age','mobileno', 'vaccineid', 'dosage', 'vaccine_date' );
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
            <!--<header>< ?=$heading?> <small class="sub-heading">Management</small></header>-->
            <div style="padding:0px 20px">
            <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
            <?php if($userData['role']=='admin'):?>
              <button type="button" class="btn btn-round btn-warning" onclick="add('add',0)" >Add New</button>
              <?php endif ?>
             
            </div>
            <div class="tools">
            <!--<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>-->
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <!--<a class="t-close btn-color fa fa-times" href="javascript:;"></a> --></div>
          </div>
          <div class="card-body ">
            <table id="example4" class="table-bordered display full-width" >
              <thead>
                <tr>
                        <?php foreach ($fields as $field): ?>
                            <th><?=ucfirst($field)?></th>
                        <?php endforeach ?>
                        <th></th>
                 </tr>
              </thead>
              <tbody>
						<?php /* foreach ($data as $row):?>
                        	<tr id="row<?=$row[$key]?>">
                        		<?php foreach ($indexes as $index): 
									if($index == 'bookedby')
										$row[$index] = getField('e_admin',array('userid'=>$this->session->userid),'name');
									if($index == 'vaccineid')
										$row[$index] = getField('e_vaccines',array('id'=>$row[$index]),'title');
								?>
                        			<td><?=$row[$index]?></td>
                        		<?php endforeach ?>
                        
                                <td style="padding: 2px;text-align: center;" width="15%">
                                
                                
                                
                                <button class="btn btn-primary btn-xs" onclick="add('edit',<?=$row[$key]?>)" >
                                <i class="fa fa-pencil"></i></button>
                                 <?php //if($userData['role']=='admin'):?>
                                 <button class="btn btn-danger btn-xs" onclick="deleteRecord('<?=$key?>',<?=$row[$key]?>,'<?=$tableName?>')">
								  <i class="fa fa-trash-o "></i></button> 
                                  <?php //endif ?>                  

                                 <!--<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>-->
                                 
                        </td>
                        </tr>
                        
                        <?php endforeach */ ?>
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
          <div class="card-head" style="width:100%; position:relative;top:0; height:34px;cursor:move" id="popup_bar"> <span id="btn_close" style="float:right;cursor:pointer;padding-right:6px;">[X]</span>
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

function deleteRecord(key,value,rowid,tableName)
{
	if (confirm("Are you sure to delete?"))
	{  
		$.post("<?php echo base_url(); ?>Function_control/deleteRecord",{key,value,tableName}, 
			function(data)
			{   //alert(data);
				$("#"+rowid).hide();
				 //location.reload();
					//loadData();
			});
	}
}
function add(action,val)
{   var viewType 	= '<?=$calledName?>';
	var tableName 	= '<?=$tableName?>';
	var key 		= '<?=$key?>'; 
	$("#exTitle").html(action+' <?=$heading?>');
	$.post("<?php echo base_url(); ?>Function_control/getFormsView",{viewType,action,tableName,key,val}, 
		function(data)
		{  	$("#bar-parent2").html(data); 
			$("#btn_popup").trigger('click');
		});
}

function getDoubleFieldAjax(inputfield,inputfieldtwo,targetfield,targetfieldtwo,value,table,keyindex){
	//alert(inputfield+"  "+targetfield+"  "+value+"  "+table+"  "+keyindex);
	$.post("<?php echo base_url();?>Function_control/getDoubleFieldAjax",{targetfield,targetfieldtwo,value,table,keyindex}, 
			function(data)
			{   //alert(data);
				var obj = JSON.parse(data);
				$("#"+inputfield).val(obj[0]);
				$("#"+inputfieldtwo).val(obj[1]);
			});
}
function getsingleFieldAjax(inputfield,targetfield,value,table,keyindex){
	//alert(inputfield+"  "+targetfield+"  "+value+"  "+table+"  "+keyindex);
	$.post("<?php echo base_url();?>Function_control/getsingleFieldAjax",{targetfield,value,table,keyindex}, 
			function(data)
			{   //alert(data);
				$("#"+inputfield).val(data);
			});
}

function setIdtype(){
	var idtype = $("input[name='idtype']:checked").val();
	if(idtype=='Others'){
		$("#citizenid").val("-");
		$('#citizenid').prop('readonly', true);
		$('#fathername').prop('readonly', false);
		$('#passportno').prop('readonly', false);
	}
	else{
		$("#citizenid").val("");
		$('#citizenid').prop('readonly', false);
		$("#fathername").val("");
		$('#fathername').prop('readonly', true);
		$("#passportno").val("");
		$('#passportno').prop('readonly', true);
	}
}

function form_validation() 
{   var xcount =0;

    
    if ($('input[name="idtype"]:checked').val()!="Others" && $("#citizenid").val().length!=13){
        alert("Please Check Citizen Number");
 		xcount=1;
 	}
 /*	if ($('input[name="idtype"]:checked').val()!="Others" && $("#citizenid").val().length!=13){
		if(confirm("Citizen Number is not equal to 13 Characters. Click OK to Procced or CANCEL  to edit Citizen Number")){
		}
		else{
 		xcount=1;}
 	}*/
	
	if ($('input[name="idtype"]:checked').val()=="Others" && $("#passportno").val().length!=13){
		if(confirm("PassportNumber/Spous/Guardian Card Number is not equal to 13 Characters. Click OK to Procced or CANCEL  to Edit")){
		}
		else{
 		xcount=1;}
 	}
    
    //if else validation here	
	if ($('input:checkbox').filter(':checked').length < 1){
        alert("Please Check 'None' if have no disability");
 		xcount=1;
 	}
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
 
function loadData(){  
  
	$("#example4").dataTable().fnDestroy();
    $('#example4').DataTable( {
        "processing": true,
        "serverSide": true,
		"aaSorting": [],/*
				"paging": false,*/
				"scrollX": true,
				dom: 'Bfrtip',
				buttons: ['copy', 'csv', 'excel', 'print', {
				extend: 'pdfHtml5',
				orientation: 'landscape',
				pageSize: 'LEGAL'}
				],
        "ajax": "<?=base_url();?>Home/varchiveData"

    } );

    }  
	
$(document).ready(function() { 
   loadData();
} );
</script>


<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName 	= "vialsrpt";
$calledName = "Vialsrpt";
$heading 	= "Vaccinations Vials Record";
$key 		= "id";
$tableName = "e_vials";
$fields = array('Date','Vaccine ID','Batch No','Received','Consumed','Wasted','Returned');
$indexes  = array('issuancedate','vaccineid','batchno', 'received', 'consumed','wasted', 'returned' );
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
        <div class="card card-topline-yellow">
          <div class="card-head">
           <div class="form-group col-md-4">
            <label>Vaccine ID</label>
        	<select class="form-control" name="vaccineid" id="vaccineid" >
            <option value=""></option>
          	<?= getOption('e_vaccines','id','title',$data['vaccineid'])?>
            </select>
           </div>
           <div class="form-group col-md-3">
        	<label class="control-label">Date (From)</label>
        	<input type="text" id="vaccine_date_from" name="vaccine_date_from" class="form-control default_datetimepicker"/>
      		</div>
            <div class="form-group col-md-3">
        	<label class="control-label">Date (To)</label>
        	<input type="text" id="vaccine_date_to" name="vaccine_date_to" class="form-control default_datetimepicker"/>
      		</div>
            <div class="buttons" style="margin: 0 auto;">
            <br />
          	<button type="submit" class="btn btn-info" onclick="loadData()">Fetch Data</button>
        	</div>
        </div>
      </div>
    </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-topline-aqua">
          
          <div class="card-body ">
            <table id="saveStage" class="table-bordered display full-width" >
              <thead>
                <tr>
                        <?php foreach ($fields as $field): ?>
                            <th><?=ucfirst($field)?></th>
                        <?php endforeach ?>
                        
                 </tr>
              </thead>
              <tbody  class="aikvt">
						<?php foreach ($data as $row):?>
                        	<tr id="row<?=$row[$key]?>">
                        		<?php foreach ($indexes as $index): 
									if($index == 'location')
										$row[$index] = getField('e_locations',array('id'=>$row[$index]),'title');
								    if($index == 'vaccineid')
										$row[$index] = getField('e_vaccines',array('id'=>$row[$index]),'title');
								?>
                        			<td><?=$row[$index]?></td>
                        		<?php endforeach ?>
                        
                                
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
	$("#exTitle").html(action+' <?=$heading?>');
	$.post("<?php echo base_url(); ?>Function_control/getFormsView",{viewType,action,tableName,key,val}, 
		function(data)
		{  	$("#bar-parent2").html(data); 
			$("#btn_popup").trigger('click');
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
	}
	else{
		$("#citizenid").val("");
		$('#citizenid').prop('readonly', false);
		$("#fathername").val="";
		$('#fathername').prop('readonly', true);
	}
}

function form_validation() 
{   var xcount =0;
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

async function loadData(){  
	var date_from 		= $("#vaccine_date_from").val();
	var date_to 		= $("#vaccine_date_to").val();
	var vaccineid 		= $("#vaccineid").val();

	$(".aikvt").html('loading...');
	
	$.post("<?=base_url()?>Function_control/getVialsReport",{date_from,date_to,vaccineid},
	  function(fdata){ 
		// alert(fdata);
		  $(".aikvt").html('');
		  var obj = JSON.parse(fdata); 
		  
		  $("#saveStage").dataTable().fnDestroy();
		  $('#saveStage').DataTable({
				"processing": true,
				"aaSorting": [],/*
				"paging": false,*/
				"scrollX": true,
				dom: 'Bfrtip',
				buttons: ['copy', 'csv', 'excel', 'print', {
				extend: 'pdfHtml5',
				orientation: 'landscape',
				pageSize: 'LEGAL'}
				],
				data:obj,
                columns: [
				  { "data": "issuancedate" },
				  { "data": "vaccineid" },
				  { "data": "batchno" },
				  { "data": "received" },
				  { "data": "consumed" },
				  { "data": "wasted" },
				  { "data": "returned" }
                ]           
            });/**/
	  });

    }  
	
	
</script>


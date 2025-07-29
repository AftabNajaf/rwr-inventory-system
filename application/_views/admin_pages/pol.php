<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName = "pol";
$calledName = "Pol";
$heading = "POL";
$key = "id";
$tableName = "e_items";
$fields = array('MIS#','Item Name','Part Number','Min Qty','Avail. Qty');
$indexes  = array('id','item_name','part_number','min_qty','item_quantity');

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
            </div>
            <div class="tools">
            <!--<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>-->
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <!--<a class="t-close btn-color fa fa-times" href="javascript:;"></a> --></div>
          </div>
          <div class="card-body ">
            <table class="table display product-overview" id="exptable">
              <thead>
                <tr>
                        <?php foreach ($fields as $field): ?>
                            <th><?=ucfirst($field)?></th>
                        <?php endforeach ?>
                 </tr>
              </thead>
              <tbody id="dsheets">
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
	$("#exTitle").html(action+' <?=$calledName?>');
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
 

$(document).ready(function () {
	
	$('#exptable').DataTable({
        aaSorting: [],
        paging: true,
       // processing: true,
        //serverSide: true,
		pageLength: 5,
		/*lengthMenu: [
            [5,10, 25, 50, -1],
            [5,10, 25, 50, 'All'],
        ],*/
        deferRender:    true,
        stateSave: true,
          stateSaveCallback: function(settings,data) {
              localStorage.setItem( 'xptbl_' + settings.sInstance, JSON.stringify(data) )
            },
          stateLoadCallback: function(settings) {
            return JSON.parse( localStorage.getItem( 'xptbl_' + settings.sInstance ) )
            },
		dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel','print',
				{
				extend: 'pdfHtml5',
				orientation: 'landscape',
				pageSize: 'LEGAL' },'pageLength'
        ],
        ajax: "<?=base_url()?>Function_control/getPOL"
    });
	
	
});


</script>


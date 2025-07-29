<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName = "customers";
$calledName = "Customer";
$heading = "ItemsData";
$key = "REFERENCE_NO";
$tableName = "table38";
$fields = array('id', 'item_name', 'item_category', 'supplier_id', 'part_number', 'item_asset_no','item_description', 'item_functionality', 'min_qty', 'max_qty', 'item_serial_no', 'item_asset_no', 'qty', 'unit', 'image', 'doc_list', 'path','price','value', 'location','custodian','supplier_ref',	'supplier_order_code');
$indexes  = array('id', 'item_name', 'item_category', 'supplier_id', 'part_number', 'item_asset_no','item_description', 'item_functionality', 'min_qty', 'max_qty', 'item_serial_no', 'item_asset_no', 'qty', 'unit', 'image', 'doc_list', 'path','price','value', 'location','custodian','supplier_ref',	'supplier_order_code');

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
    <?php if($this->session->role=='admin'):?>
    <div class="col-md-12">  
        <button id="cleanBtn" class="btn btn-danger" >Click hher to <strong>"Clean DB"</strong></button>
     <br /><br /></div>
      <?php endif ?> 
     <div class="col-md-12">   
        <div class="card card-topline-aqua">
          <div class="card-head">
            <div style="padding:0px 20px">
            <form name="myForm" id="myForm" method="post" enctype="multipart/form-data" onsubmit="form_validation()">
              <div class="form-group col-md-10">
                <input type="file" id="doc_list" name="doc_list" class="form-control" title="Excel Data File" />
              </div>                   
              <div class="form-group col-md-2">                
                <button type="submit" class="btn btn-info">upload data from excel file</button>
              </div>
        	</form>
            </div>
            <div class="tools"><!----><a href="<?=base_url()?>Home/ExportData" id='ex' style="float:right" class="btn btn-xl btn-block btn-success" onclick="downloadStarted()" >
               <button type="button"  class="btn btn-xl btn-block btn-success" onclick="downloadStarted()" >Download <br /> Inventory Data</button></a>
               
            </div>
          </div>
          <div class="card-body ">
          <center id="processing" style="display:none;">Please wait...!<br />
			<img src="<?=base_url()?>assets/img/processing.gif" alt="Processing...." width="50%"  /></center>
          </div>
        </div>
      </div>
    </div>
	
  <!-- =============== End Form Control =============================== -->
    </div>
</div>
<?php $this->load->view('admin_layout/footer');?>

<script>
function form_validation() 
{   var xcount =0;
    //if else validation here	
	if(xcount==0){
		var form = new FormData($('#myForm')[0]);
		//alert(form.get('title'));
	$("#processing").show();
    $.ajax({
      type: "POST",
      url: '<?=base_url()?>Home/ImportData',
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


function downloadStarted() {
    $("#processing").show();
   
    // Set a cookie indicating that the download process has started
    document.cookie = "downloadStarted=true";
}

// Polling function to check if the download has started
function checkDownloadStarted() {
    $.get("<?= base_url() ?>Home/ExportData", function(response) {
        if (response.downloadStarted) {
            $("#processing").hide(); // Hide the processing message
        } else {
            // If download hasn't started, continue polling
            setTimeout(checkDownloadStarted, 1000); // Check again after 1 second
        }
    });
}

// Start checking if the download has started after clicking the link
$('#ex').click(function() {
    $("#processing").show();
    setTimeout(checkDownloadStarted, 1000); // Start checking after 1 second
});


$("#cleanBtn").click(function(){
        if(confirm("Are you sure you want to clean the database?")) {
			if(confirm("This action is not reversible. Click Ok to clean the database?")) {
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>Home/cleanDB',
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    // Additional actions after successful truncation
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while truncating tables.');
                    console.error(xhr.responseText);
                }
            });
			}
        }
    });
</script>

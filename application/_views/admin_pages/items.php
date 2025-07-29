<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName = "items";
$calledName = "Items";
$heading = "Items";
$key = "id";
$tableName = "e_items";
$fields = array('Category Title');//,'Parent Category'
$indexes  = array('title');//,'parentid'

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
      <div class="col-md-8">
        <div class="card card-topline-aqua">
          <div class="card-head">
            <!--<header>< ?=$heading?> <small class="sub-heading">Management</small></header>-->
            <div style="padding:0px 20px">
              <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
              <!--<button type="button" class="btn btn-round btn-warning" onclick="add('add',0)" >Add New </button>-->
             
            </div>
            <div class="tools">
              <!--<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
              <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>-->
              <!--<a class="t-close btn-color fa fa-times" href="javascript:;"></a> -->
            </div>
          </div>
          <div class="card-body" id="myscrollx"  style="overflow-y: auto; min-height:420px; max-height:420px">
          <!--<span class="context-menu-one btn btn-neutral">right click me</span>-->
        	
				<?php foreach($treeCats as $tree) : extract($tree); ?>
                <span class="accordion-item class<?=$id?>" onclick="getChilds(<?=$id?>)" data-panel-id="nesting-panel<?=$id?>">
                <button class="btn btn-success btn-sm" onclick="add('add',<?=$id?>)" ><i class="fa fa-plus"></i></button>
              
				<?=str_pad($id,5,"0",STR_PAD_LEFT)?>----<i class="fa fa-plus-circle"></i> <?=$title?></span>
                <div class="accordion-panel" id="nesting-panel<?=$id?>"></div>
                <?php endforeach ?>
        </div>
      </div>
    </div>
    
      <div class="col-md-4">
        <div class="card card-topline-aqua">
          <div class="card-head">
            <!--<header>< ?=$heading?> <small class="sub-heading">Management</small></header>-->
            <form action="" method="post" onsubmit="">
            <label class="form-label">Search Category</label>
            <input type="text" class="form-control" name="searchcat" id="searchcat" placeholder="Search Category by Name / MIS#" onkeyup="getSearchedCats()" />
            </form>
            <div class="tools">
              <!--<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
              <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>-->
              <!--<a class="t-close btn-color fa fa-times" href="javascript:;"></a> -->
            </div>
          </div>
          <div class="card-body">
            <ul id="aik">
            </ul>          
        </div>
      </div>
    </div>
    <!-- =============== End Form Control =============================== -->
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

function getNewPath(catid){
	$.post("<?php echo base_url(); ?>Function_control/trackTempPath",{catid}, 
			function(data)
			{   //alert(data);
				$("#newpath").html(data);
				//$("#item_category").val(catid);
			});
	//$("#newpath").html(catid);
}

function getSearchedCats(){
	var tableName 	= '<?=$tableName?>';
	var searchString = $("#searchcat").val();
	if(searchString.length>0){
		//alert(searchString+searchString.length);
		$.post("<?php echo base_url(); ?>Function_control/searchCatString",{searchString,tableName}, 
			function(data)
			{   //alert(data);
				$("#aik").html(data);
			});
	}
}
function searchMagic(x){ 
	var tableName 	= '<?=$tableName?>';
	var catid=x;
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
						$(".class"+value).css('background-color','#cfcfcf');//item page find category
						$("#i"+value).css('background-color','#cfcfcf');

									
									var myElement = document.getElementById('i'+value);
									if(myElement==null)
										myElement = document.getElementsByClassName("class-x"+value)[0];
										
									var topPos = myElement.offsetTop;			
									document.getElementById('myscrollx').scrollTop = topPos;						
						}
						}, count * 550);
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
 
</script>

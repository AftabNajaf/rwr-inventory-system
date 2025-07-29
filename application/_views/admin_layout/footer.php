
</div>
<!-- start footer -->
		<div class="page-footer">
			<div class="page-footer-inner"> 2023 &copy; <?php echo $settings['siteName']; ?>  
<!--				<a href="mailto:#" target="_top" class="makerCss">#</a>
-->			</div>
			<div class="scroll-to-top">
				<i class="icon-arrow-up"></i>
			</div>
		</div>
		<!-- end footer -->
	</div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" integrity="sha512-nNlU0WK2QfKsuEmdcTwkeh+lhGs6uyOxuUs+n+0oXSYDok5qy0EI0lt01ZynHq6+p/tbgpZ7P+yUb+r71wqdXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<!-- start js include path asd
	<script src="<?=base_url()?>assets/plugins/jquery/jquery.min.js"></script>-->
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>

    <script src="<?=base_url()?>assets/js/kv-jsaccordions.src.js"></script>
    
    
    
	<script src="<?=base_url()?>assets/plugins/popper/popper.min.js"></script>
	<script src="<?=base_url()?>assets/plugins/jquery-blockui/jquery.blockui.min.js"></script>
	<script src="<?=base_url()?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
     <!-- ========= Validation form ----- -->
    <script src="<?=base_url()?>assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>
    <script src="<?=base_url()?>assets/plugins/jquery-validation/js/additional-methods.min.js"></script>
	<!-- bootstrap -->
	<script src="<?=base_url()?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
    <script src="<?=base_url()?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
    <script src="<?=base_url()?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker-init.js"></script>
    
     <!-- asd-->
    <script src="<?=base_url()?>assets/aik/datetimepicker/jquery.datetimepicker.min.js"></script>
    <script src="<?=base_url()?>assets/aik/datetimepicker/setting.js"></script>
    
   
    <script src="<?=base_url()?>assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script src="<?=base_url()?>assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js"></script>
	   
	<!-- Common js-->
	<script src="<?=base_url()?>assets/js/app.js"></script>
	<script src="<?=base_url()?>assets/js/layout.js"></script>
	<script src="<?=base_url()?>assets/js/theme-color.js"></script>
                    <!-- ========= Validation form ----- -->
    <script src="<?=base_url()?>assets/js/pages/validation/form-validation.js"></script>
	<!-- material -->
	<script src="<?=base_url()?>assets/plugins/material/material.min.js"></script>
	<!-- animation -->
	<script src="<?=base_url()?>assets/js/pages/ui/animations.js"></script>
	<script src="<?=base_url()?>assets/js/pages/material_select/getmdl-select.js"></script>
	<script src="<?=base_url()?>assets/plugins/flatpicker/flatpickr.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/datetime/datetime-data.js"></script>
    <!--  <script src="<?=base_url()?>assets/js/pages/steps/steps-data.js"></script>
         dropzone asd
      <script src="<?=base_url()?>assets/plugins/dropzone/dropzone.js"></script> -->
      <!--tags input-->
      <script src="<?=base_url()?>assets/plugins/jquery-tags-input/jquery-tags-input.js"></script>
      <script src="<?=base_url()?>assets/plugins/jquery-tags-input/jquery-tags-input-init.js"></script>
      <!--select2-->
      <script src="<?=base_url()?>assets/plugins/select2/js/select2.js"></script>
      <script src="<?=base_url()?>assets/js/pages/select2/select2-init.js"></script>
      
      <!-- Editable summernote -->
	<script src="<?=base_url()?>assets/plugins/summernote/summernote.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/summernote/summernote-data.js"></script>
    <!-- data tables -->
    
   
<!---->
	<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?=base_url()?>assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.js"></script>
    <script src="<?=base_url()?>assets/js/pages/table/table_data.js"></script>
    

	<!-- morris chart -->
	<!-- dashboar dcharts <script src="<?=base_url()?>assets/plugins/morris/morris.min.js"></script>
	<script src="<?=base_url()?>assets/plugins/morris/raphael-min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/chart/morris/morris_home_data.js"></script>
    <script src="<?=base_url()?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
	<script src="<?=base_url()?>assets/js/pages/sparkline/sparkline-data.js"></script>
    -->
    <script>
    function goCancel(){
		var popup = document.getElementById("popup");
    	popup.style.display = "none";
	}
	
	(function(){ 	 

  var SCROLL_WIDTH = 350;
  var btn_popup = document.getElementById("btn_popup");
  var popup = document.getElementById("popup");
  var popup_bar = document.getElementById("popup_bar");
  var btn_close = document.getElementById("btn_close");
  var btn_closed = document.getElementById("btn_closed");
  //-- let the popup make draggable & movable.
  var offset = { x: 0, y: 0 };

  popup_bar.addEventListener('mousedown', mouseDown, false);
  window.addEventListener('mouseup', mouseUp, false);

  function mouseUp()
  {
    window.removeEventListener('mousemove', popupMove, true);
  }

  function mouseDown(e){
    offset.x = e.clientX - popup.offsetLeft;
    offset.y = e.clientY - popup.offsetTop;
    window.addEventListener('mousemove', popupMove, true);
  }

  function popupMove(e){
    popup.style.position = 'absolute';
    var top = e.clientY - offset.y;
    var left = e.clientX - offset.x;
    popup.style.top = top + 'px';
    popup.style.left = left + 'px';
  }
  //-- / let the popup make draggable & movable.


  window.onkeydown = function(e){
    if(e.keyCode == 27){ // if ESC key pressed
      btn_close.click(e);
	  	$('#ixd').html("");//close item toottip
		$('#ixd').hide(); 
    }
  }

  btn_popup.onclick = function(e){
    // reset div position
    popup.style.top = "50px";
   // popup.style.left = "240px";
   // popup.style.width = window.innerWidth - SCROLL_WIDTH + "px";
   // popup.style.height = window.innerHeight - SCROLL_WIDTH + "px";
    popup.style.display = "block";
  }

  btn_close.onclick = function(e){
    popup.style.display = "none";
  }
  
  btn_closed.onclick = function(e){popup.style.display = "none";
  }
  
}());
	
    </script>
    <script>
function setSerail(x){
	var existingVal= $("#serial_no").val();

	if(!existingVal.includes(x)){
		if(existingVal=='')
		$("#serial_no").val(x+",");
		else
		$("#serial_no").val(existingVal+x+',');
	}
	else{
		;
		$("#serial_no").val(existingVal.replace(x,'').replace(',,',''));
	}

	
}
</script>

<script src="<?=base_url()?>assets/aik/highcharts.js"></script>
<script src="<?=base_url()?>assets/aik/exporting.js"></script>

<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
<script>
function setStoreRemarks(tid,tblName,tblxi,tblx,rowkey,tblxi_id){
	
	$.post("<?php echo base_url(); ?>Function_control/setStoreRemarks",{tid,tblName,tblxi,tblx,rowkey,tblxi_id,rowkey,tblxi_id}, 
		function(data)
		{  	//alert(data);
			location.reload();
		});
}

function setReceivings(tblName,gby,ky,vl)
{   var viewType 	= 'Receivings';
	var tableName 	= tblName;
	var genby		= gby;
	var key 		= ky; 
	var val			= vl;
	var action		= '';
	$("#exTitle").html('Receivings');
	$.post("<?php echo base_url(); ?>Function_control/getFormsView",{viewType,tableName,genby,key,val,action}, 
		function(data)
		{  	 
			$("#bar-parent2").html(data); 
			$("#btn_popup").trigger('click');
		});
}
</script>


	
</body>
</html>
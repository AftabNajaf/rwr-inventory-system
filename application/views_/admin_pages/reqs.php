<?php $this->load->view('admin_layout/header');?>
<?php 
$pageName = "itemqty";
$calledName = "ItemsStock";
$heading = "Manage Requests";
$key = "id";
$tableName = "e_items";
$fields = array('Ser #','Department','Section','Project','Generated On','Store Remarks');//,'Parent Category'
$indexes  = array('id','department_id','section_id','project_id','generated_on','store_remarks');//,'parentid'

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
     <div class="col-md-12" id="mat">
        <div class="card card-topline-aqua">
          <div class="card-head">
           <header>MAT<small class="sub-heading"> Request(s)</small></header>
            <div style="padding:0px 20px">
            <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
              <!--<button type="button" class="btn btn-round btn-warning" onclick="addbom('add',0)" >Add New</button>-->
            </div>
            <div class="tools">
            <!--<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>-->
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <!--<a class="t-close btn-color fa fa-times" href="javascript:;"></a> --></div>
          </div>
          <div class="card-body ">
          	<?php
		  	$fields 	= array('Ser #','Department','Section','Project','Generated On','Genrated By','Store Remarks');//,'Parent Category'
			$indexes  	= array('id','department_id','section_id','project_id','generated_on','generated_by','bom_remarks');//,'parentid'
			$tblx = 'e_mat'; $tblxi='e_matitem'; $tblxi_id='mat_id';
			?>
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
				<?php  foreach ($mats as $row):?>
                        <tr id="row<?=$row[$key]?>">
							<?php foreach ($indexes as $index): 
                            if(($index=='department_id' || $index=='section_id' )&& $row[$index]>0)
                                $row[$index]= getField('e_departments',array('id'=>$row[$index]),'title');
                            if($index=='project_id'&& $row[$index]>0) $row[$index]= getField('e_projects',array('id'=>$row[$index]),'title');
							/*if($index=='store_remarks'){
									$qtNeeded = $this->AdminModel->getSumRows($tblxi,'qty_needed',array($tblxi_id=>$row[$key]));
									$qtIssued = $this->AdminModel->getSumRows('e_items_alloc','item_quantity',array('instrument_no'=>$row[$key],'instrument'=>'mat'));
									if($qtNeeded==$qtIssued && $qtIssued>0)
										$this->AdminModel->updateRow($tblx,array('id'=>$row[$key]),array('store_remarks'=>'Completed'));
									if($qtNeeded>$qtIssued && $qtIssued>0)
										$this->AdminModel->updateRow($tblx,array('id'=>$row[$key]),array('store_remarks'=>'Partially Completed'));
									if($qtIssued==0){
										$this->AdminModel->updateRow($tblx,array('id'=>$row[$key]),array('store_remarks'=>''));
										
										$row[$index]= getField($tblx,array('id'=>$row[$key]),'store_remarks');
										if($row[$index]=='')
										$row[$index]='Pending';
										}
							}*/
							#if($index=='bom_remarks' && $row[$index]=='')
							#	$row[$index]= getField($tblx,array('id'=>$row[$key]),'store_remarks');
								
							if($index=='generated_by') $row[$index]= getField('e_admin',array("userid"=>$row['generated_by']),'name');
                            ?>
                            <td><?=$row[$index]?></td>
                        <?php endforeach ?>
                        <td style="padding: 2px;text-align: center;" width="15%"><br />
							<?php if($row['bom_remarks']=='Pending' || $row['bom_remarks']==''):?>
                            <button class="btn btn-success btn-xs" onclick="location.href = '<?=base_url()?>Home/matitems_allocation/<?=$row[$key]?>'" >
                            <i class="fa fa-plus"> Items</i></button>
                            <?php endif ?>
                            <button class="btn btn-warning btn-xs" onclick="window.open(
                            '<?=base_url()?>Pdfgenerator/downloadMat/<?=$row[$key]?>',
                            '_blank')" >
                            <i class="fa fa-eye"></i></button>  
                            
                            <!--<button class="btn btn-info btn-xs" onclick="setStoreRemarks(<?=$row[$key]?>,'mat','<?=$tblxi?>','<?=$tblx?>',<?=$row[$key]?>,'<?=$tblxi_id?>')" >Mark as Done</button>-->
                            
                        </td>
                        </tr>
                 <?php endforeach ?>
               </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <div class="col-md-12" id="grn">
        <div class="card card-topline-aqua">
          <div class="card-head">
           <header>GRN<small class="sub-heading"> Request(s)</small></header>
            <div style="padding:0px 20px">
            <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
              <!--<button type="button" class="btn btn-round btn-warning" onclick="addbom('add',0)" >Add New</button>-->
            </div>
            <div class="tools">
            <!--<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>-->
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <!--<a class="t-close btn-color fa fa-times" href="javascript:;"></a> --></div>
          </div>
          <div class="card-body ">
          	<?php
		  	$fields = array('PO #','Supplier','Generated On','Generated By','PO Status');//,'Parent Category'
			$indexes  = array('id','supplier_id','generated_on','generated_by','store_remarks');//,'parentid'
			?>
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
						<?php foreach ($grns as $row):?>
                        	<tr id="row<?=$row[$key]?>">
                        		<?php foreach ($indexes as $index): 
								if(($index=='supplier_id')&& $row[$index]>0)
									$row[$index]= getField('e_suppliers',array('id'=>$row[$index]),'title');
							
									if($index=='generated_by') $row[$index]= getField('e_admin',array("userid"=>$row['generated_by']),'name');
                            
									?>
                        			<td><?=$row[$index]?></td>
                        		<?php endforeach ?>
                        
                                <td style="padding: 2px;text-align: center;" width="15%">
                                <button class="btn btn-success btn-xs" onclick="location.href = '<?=base_url()?>Home/grnitems/<?=$row[$key]?>'" >
                                <i class="fa fa-plus"> GRN - Items</i></button>
                                
                                <button class="btn btn-warning btn-xs" onclick="location.href = '<?=base_url()?>Pdfgenerator/downloadGrn/<?=$row[$key]?>'" >
                                <i class="fa fa-eye"></i></button>
                              
                                
                                <!-- <button class="btn btn-primary btn-xs" onclick="addpo('edit',<?=$row[$key]?>)" >
                                <i class="fa fa-pencil"></i></button>
                                 
                                 <button class="btn btn-danger btn-xs" onclick="deleteRecordx('<?=$key?>',<?=$row[$key]?>,'<?=$tableName?>')">
								  <i class="fa fa-trash-o "></i></button>                   

                                <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>-->
                                 
                        </td>
                        </tr>
                        
                        <?php endforeach ?>
                        </tbody>
            </table>
          </div>
        </div>
      </div>
      
     <div class="col-md-12" id="adv">
        <div class="card card-topline-aqua">
          <div class="card-head">
           <header>Adv Booking<small class="sub-heading"> Request(s)</small></header>
            <div style="padding:0px 20px">
            <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
              <!--<button type="button" class="btn btn-round btn-warning" onclick="addbom('add',0)" >Add New</button>-->
            </div>
            <div class="tools">
            <!--<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>-->
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <!--<a class="t-close btn-color fa fa-times" href="javascript:;"></a> --></div>
          </div>
          <div class="card-body ">
          	<?php
		  	$fields = array('AB Request #','Department','Section','Project','Generated On','Generated By','Store Remarks');//,'Parent Category'
			$indexes  = array('id','department_id','section_id','project_id','generated_on','generated_by','store_remarks');//,'parentid'
			$tblx = 'e_adv'; $tblxi='e_advitems'; $tblxi_id='adv_id';
			?>
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
						<?php foreach ($advs as $row):?>
                        	<tr id="row<?=$row[$key]?>">
                        		<?php foreach ($indexes as $index): 
								if(($index=='department_id' || $index=='section_id' )&& $row[$index]>0)
									$row[$index]= getField('e_departments',array('id'=>$row[$index]),'title');
								if($index=='project_id'&& $row[$index]>0) $row[$index]= getField('e_projects',array('id'=>$row[$index]),'title');
								/*if($index=='store_remarks'){
									
									$qtNeeded = $this->AdminModel->getSumRows($tblxi,'qty_needed',array($tblxi_id=>$row[$key]));
									$qtIssued = $this->AdminModel->getSumRows('e_items_alloc','item_quantity',array('instrument_no'=>$row[$key],'instrument'=>'adv'));
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
								
								if($index=='generated_by') $row[$index]= getField('e_admin',array("userid"=>$row['generated_by']),'name');  
                               
								?>
                        			<td><?=$row[$index]?></td>
                        		<?php endforeach ?>
                        
                                <td style="padding: 2px;text-align: center;" width="15%">
                                <?php if($row['bom_remarks']=='Pending' || $row['bom_remarks']==''):?>
                                <button class="btn btn-success btn-xs" onclick="location.href = '<?=base_url()?>Home/advitems_allocation/<?=$row[$key]?>'" >
                                <i class="fa fa-plus"> Items</i></button>
                                <?php endif?>
                                <button class="btn btn-warning btn-xs" onclick="location.href = '<?=base_url()?>Pdfgenerator/downloadAdvBooking/<?=$row[$key]?>'" >
                                <i class="fa fa-eye"></i></button>
                                
                              <!--<button class="btn btn-info btn-xs" onclick="setStoreRemarks(<?=$row[$key]?>,'adv','<?=$tblxi?>','<?=$tblx?>',<?=$row[$key]?>,'<?=$tblxi_id?>')" >Mark as Done</button>-->
                              
                        </td>
                        </tr>
                        
                        <?php endforeach ?>
                        </tbody>
            </table>
          </div>
        </div>
      </div>
      
     <div class="col-md-12" id="tempissue">
        <div class="card card-topline-aqua">
          <div class="card-head">
           <header>Temp Issue<small class="sub-heading"> Request(s)</small></header>
            <div style="padding:0px 20px">
            <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
              <!--<button type="button" class="btn btn-round btn-warning" onclick="addbom('add',0)" >Add New</button>-->
            </div>
            <div class="tools">
            <!--<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>-->
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <!--<a class="t-close btn-color fa fa-times" href="javascript:;"></a> --></div>
          </div>
          <div class="card-body ">
          	<?php
		  	$fields = array('Req #','Department','Section','Project','Generated On','Generated By','Store Remarks');//,'Parent Category'
			$indexes  = array('id','department_id','section_id','project_id','generated_on','generated_by','store_remarks');//,'parentid'
			$tblx = 'e_tempissue'; $tblxi='e_tempissueitems'; $tblxi_id='adv_id';
			?>
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
						<?php foreach ($tmpissues as $row):?>
                        	<tr id="row<?=$row[$key]?>">
                        		<?php foreach ($indexes as $index): 
								if(($index=='department_id' || $index=='section_id' )&& $row[$index]>0)
									$row[$index]= getField('e_departments',array('id'=>$row[$index]),'title');
								if($index=='project_id'&& $row[$index]>0)
									$row[$index]= getField('e_projects',array('id'=>$row[$index]),'title');
								if($index=='generated_by') $row[$index]= getField('e_admin',array("userid"=>$row['generated_by']),'name'); 
								/*if($index=='store_remarks'){
									
									$qtNeeded = $this->AdminModel->getSumRows($tblxi,'qty_needed',array($tblxi_id=>$row[$key]));
									$qtIssued = $this->AdminModel->getSumRows('e_items_alloc','item_quantity',array('instrument_no'=>$row[$key],'instrument'=>'tmpissue'));
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
								} */
								?>
                        			<td><?=$row[$index]?></td>
                        		<?php endforeach ?>
                        
                                <td style="padding: 2px;text-align: center;" width="15%">
                                <?php if($row['bom_remarks']=='Pending' || $row['bom_remarks']==''):?>
                                <button class="btn btn-success btn-xs" onclick="location.href = '<?=base_url()?>Home/tmpissueitems_allocation/<?=$row[$key]?>'" >
                                <i class="fa fa-plus"> Items</i></button>
                                <?php endif ?>
                                <button class="btn btn-warning btn-xs" onclick="location.href = '<?=base_url()?>Pdfgenerator/downloadTempIssue/<?=$row[$key]?>'" >
                                <i class="fa fa-eye"></i></button>
                                
                                 
                              <!--<button class="btn btn-info btn-xs" onclick="setStoreRemarks(<?=$row[$key]?>,'tmpissue','<?=$tblxi?>','<?=$tblx?>',<?=$row[$key]?>,'<?=$tblxi_id?>')" >Mark as Done</button>-->
                              
                        </td>
                        </tr>
                        
                        <?php endforeach ?>
                        </tbody>
            </table>
          </div>
        </div>
      </div>
      
     <div class="col-md-12"=id="mmov">
        <div class="card card-topline-aqua">
          <div class="card-head">
           <header>Mat. Mov<small class="sub-heading"> Request(s)</small></header>
            <div style="padding:0px 20px">
            <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
              <!--<button type="button" class="btn btn-round btn-warning" onclick="addbom('add',0)" >Add New</button>-->
            </div>
            <div class="tools">
            <!--<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>-->
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <!--<a class="t-close btn-color fa fa-times" href="javascript:;"></a> --></div>
          </div>
          <div class="card-body ">
          	<?php
		  	$fields = array('Req #','Title/Subject','Generated On','Generated By','Store Remarks');//,'Parent Category'
			$indexes  = array('id','mm_title','generated_on','generated_by','store_remarks');//,'parentid'
			$tblx = 'e_mm'; $tblxi='e_mmitems'; $tblxi_id='adv_id';
			?>
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
						<?php foreach ($mmovs as $row):?>
                        	<tr id="row<?=$row[$key]?>">
                        		<?php foreach ($indexes as $index):
								if(($index=='mm_title' || $index=='section_id' )&& $row[$index]>0)
									$row[$index]= @getField('e_mm',array('id'=>$row['adv_id']),'mm_title');
								if($index=='generated_by') $row[$index]= getField('e_admin',array("userid"=>$row['generated_by']),'name'); 
								/*if($index=='store_remarks'){
									
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
                                <?php if($row['bom_remarks']=='Pending' || $row['bom_remarks']==''):?>
                                <button class="btn btn-success btn-xs" onclick="location.href = '<?=base_url()?>Home/matmoveitems_allocation/<?=$row[$key]?>'" >
                                <i class="fa fa-plus"> Items</i></button>
                                <?php endif?>
                                <button class="btn btn-warning btn-xs" onclick="location.href = '<?=base_url()?>Pdfgenerator/downloadMatmov/<?=$row[$key]?>'" >
                                <i class="fa fa-eye"></i></button>
                                
                                <!--<button class="btn btn-info btn-xs" onclick="setStoreRemarks(<?=$row[$key]?>,'mmov','<?=$tblxi?>','<?=$tblx?>',<?=$row[$key]?>,'<?=$tblxi_id?>')" >Mark as Done</button>-->
                              
                        </td>
                        </tr>
                        
                        <?php endforeach ?>
                        </tbody>
            </table>
          </div>
        </div>
      </div>
      
     <div class="col-md-12" id="restock">
        <div class="card card-topline-aqua">
          <div class="card-head">
           <header>Restock<small class="sub-heading"> Request(s)</small></header>
            <div style="padding:0px 20px">
            <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
              <!--<button type="button" class="btn btn-round btn-warning" onclick="addbom('add',0)" >Add New</button>-->
            </div>
            <div class="tools">
            <!--<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>-->
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <!--<a class="t-close btn-color fa fa-times" href="javascript:;"></a> --></div>
          </div>
          <div class="card-body ">
          	<?php
		  	$fields = array('Req #','Instrument','Instrument No','Generated On','Generated By','Store Remarks');//,'Parent Category'
			$indexes  = array('id','instrument','instrument_no','generated_on','generated_by','store_remarks');//,'parentid'
			$tblx = 'e_restock'; $tblxi='e_restockitems'; $tblxi_id='adv_id';
			?>
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
						<?php foreach ($restocks as $row):?>
                        	<tr id="row<?=$row[$key]?>">
                        		<?php foreach ($indexes as $index):
								if($index=='generated_by') $row[$index]= getField('e_admin',array("userid"=>$row['generated_by']),'name');
                               
								/*if($index=='store_remarks'){
									
									$qtNeeded = $this->AdminModel->getSumRows($tblxi,'qty_restocked',array($tblxi_id=>$row[$key]));
									//echo $this->db->last_query();
									$qtIssued = $this->AdminModel->getSumRows('e_items_alloc','item_quantity',array('instrument_no'=>$row[$key],'instrument'=>'restock')); 
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
                                <?php if($row['bom_remarks']=='Pending' || $row['bom_remarks']==''):?>
                                <button class="btn btn-success btn-xs" onclick="location.href = '<?=base_url()?>Home/restockitems_allocation/<?=$row[$key]?>'" >
                                <i class="fa fa-plus"> Items</i></button>
                                <?php endif ?>
                                <button class="btn btn-warning btn-xs" onclick="location.href = '<?=base_url()?>Pdfgenerator/downloadRestock/<?=$row[$key]?>'" >
                                <i class="fa fa-eye"></i></button>
                                
                              	 <!--<button class="btn btn-info btn-xs" onclick="setStoreRemarks(<?=$row[$key]?>,'restock','<?=$tblxi?>','<?=$tblx?>',<?=$row[$key]?>,'<?=$tblxi_id?>')" >Mark as Done</button>-->
                        </td>
                        </tr>
                        
                        <?php endforeach ?>
                        </tbody>
            </table>
          </div>
        </div>
      </div>
     <!-- 
      <div class="col-md-12">
        <div class="card card-topline-aqua">
          <div class="card-head">
           <header>Release<small class="sub-heading"> Request(s)</small></header>
            <div style="padding:0px 20px">
            <button  style="display:none" type="button" id="btn_popup" value="ppp"></button>
             <button  style="display:none" type="button" class="btn btn-round btn-warning" onclick="addbom('add',0)" >Add New</button>
            </div>
          </div>
          <div class="card-body ">
          	<?php
		  	$fields = array('Req #','Instrument','Instrument No','Generated On','Generated By','Store Remarks');//,'Parent Category'
			$indexes  = array('id','instrument','instrument_no','generated_on','generated_by','store_remarks');//,'parentid'
			$tblx = 'e_release'; $tblxi='e_releaseitems'; $tblxi_id='adv_id';
			?>
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
						<?php foreach ($relreqs as $row):?>
                        	<tr id="row<?=$row[$key]?>">
                        		<?php foreach ($indexes as $index):
								if($index=='generated_by') $row[$index]= getField('e_admin',array("userid"=>$row['generated_by']),'name');
								/*if($index=='store_remarks'){
									
									$qtNeeded = $this->AdminModel->getSumRows($tblxi,'qty_restocked',array($tblxi_id=>$row[$key]));
									//echo $this->db->last_query();
									$qtIssued = $this->AdminModel->getSumRows('e_items_alloc','item_quantity',array('instrument_no'=>$row[$key],'instrument'=>'release')); 
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
                                <button class="btn btn-success btn-xs" onclick="location.href = '<?=base_url()?>Home/releaseitems_allocation/<?=$row[$key]?>'" >
                                <i class="fa fa-plus"> Items</i></button>
                                
                                <button class="btn btn-warning btn-xs" onclick="location.href = '<?=base_url()?>Pdfgenerator/downloadReleases/<?=$row[$key]?>'" >
                                <i class="fa fa-eye"></i></button>
                                
                                <button class="btn btn-info btn-xs" onclick="setStoreRemarks(<?=$row[$key]?>,'release','<?=$tblxi?>','<?=$tblx?>',<?=$row[$key]?>,'<?=$tblxi_id?>')" >Mark as Done</button>
                              
                        </td>
                        </tr>
                        
                        <?php endforeach ?>
                        </tbody>
            </table>
          </div>
        </div>
      </div>
      -->
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
{   var viewType 	= 'Mat';
	var tableName 	= 'e_mat';
	var key 		= 'id'; 
	$("#exTitle").html(action+' MAT');
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

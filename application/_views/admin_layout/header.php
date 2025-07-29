<?php  
$CI =& get_instance();
$controler=$this->uri->segment(1);
$function=$this->uri->segment(2);
$nvlink=$this->uri->segment(3);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<meta name="description" content="<?php echo $settings['siteName']; ?>" />
<meta name="author" content="Asad Islam : asadislam.com" />
<title><?php echo $settings['siteName']; ?></title>
<!-- icons -->
<link href="<?=base_url()?>assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url()?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!--bootstrap -->
<link href="<?=base_url()?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!--<link href="<?=base_url()?>assets/css/pages/steps.css" rel="stylesheet" type="text/css" />

<link href="<?=base_url()?>assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
<link href="<?=base_url()?>assets/css/kv-jsaccordions.src.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url()?>assets/aik/datetimepicker/datetimepicker.css" rel="stylesheet" media="screen">
<link href="<?=base_url()?>assets/aik/datetimepicker/datepicker.css" rel="stylesheet" media="screen">
<link href="<?=base_url()?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen">
<link href="<?=base_url()?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url()?>assets/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url()?>assets/plugins/bootstrap-editable/inputs-ext/address/address.css" rel="stylesheet" type="text/css" />
<!-- Material Design Lite CSS -->
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/material/material.min.css">
<link rel="stylesheet" href="<?=base_url()?>assets/css/material_style.css">
<!-- animation -->
<link href="<?=base_url()?>assets/css/pages/animate_page.css" rel="stylesheet">
<link href="<?=base_url()?>assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
<!-- Template Styles -->
<link href="<?=base_url()?>assets/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url()?>assets/css/plugins.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url()?>assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url()?>assets/css/responsive.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url()?>assets/css/theme-color.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url()?>assets/plugins/summernote/summernote.css" rel="stylesheet">
<link href="<?=base_url()?>assets/plugins/dropzone/dropzone.css" rel="stylesheet" media="screen">
<link href="<?=base_url()?>assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">
<link href="<?=base_url()?>assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url()?>assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<!--POPUPS
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/flatpicker/flatpickr.min.css">-->
<!-- morris chart -->
<link href="<?=base_url()?>assets/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
<!-- favicon -->
<link rel="shortcut icon" href="<?=base_url().'assets/img/'.$settings['icon'];?>" />
<style>
.zoom {
	padding: 2px;
 transition: transform .2s; /* Animation */
	width: 120px;
	margin: 0 auto;
}
.zoom:hover {
	transform: scale(2.0); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}
</style>
</head>
<!-- END HEAD -->
<body class="page-header-fixed page-footer-fixed sidemenu-closed-hidelogo page-content-white page-md header-white dark-sidebar-color logo-dark" >
<div class="page-wrapper">
<!-- start header -->
<div class="page-header navbar navbar-fixed-top">
  <div class="page-header-inner ">
    <!-- logo start -->
    <div class="page-logo"> <span class="logo-default stitle"><a href="<?=base_url()?>"> <img alt="" style="width:150px" src="<?=base_url().'assets/img/'.$settings['icon'];?>"> </a></span> </div>
    <!-- logo end -->
    <ul class="nav navbar-nav navbar-left in">
      <li><a href="#" class="menu-toggler sidebar-toggler"><i class="icon-menu"></i></a></li>
    </ul>
    <!--<form class="search-form-opened" action="#" method="GET">
					
                    <div class="input-group">
						
                        <input type="text" class="form-control" placeholder="Search..." name="query">
						<span class="input-group-btn search-btn">
							<a href="javascript:;" class="btn submit">
								<i class="icon-magnifier"></i>
							</a>
						</span>
					</div>
				</form>-->
    <!--	 start mobile menu -->
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
					data-target=".navbar-collapse"><span></span> </a>
    <!-- end mobile menu -->
    <!-- start header menu 
    <span class="stitle pull-left" style="font-size:25%; float:left; color:#006; padding:10px 0px 0px 10px"><?php echo $settings['siteName']; ?></span>-->
    <div class="top-menu">
      <ul class="nav navbar-nav pull-right">
        <!-- start notification dropdown -->
        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar"> <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> <i class="fa fa-envelope-o"></i> <span class="badge headerBadgeColor1"> ! </span> </a>
          <ul class="dropdown-menu animated swing">
            <li class="external">
              <h3><span class="bold">Notifications</span></h3>
              <span class="notification-label purple-bgcolor">
              <!--New 3-->
              </span> </li>
            <li>
              <ul class="dropdown-menu-list small-slimscroll-style" data-handle-color="#637283">
                <?php
				$newUsersNotifications=$submittedMM=$submittedTempis=$submittedPos=$submittedDc=0;
				if($this->session->role=='admin'){
					$newUsersNotifications=$this->AdminModel->countRows("e_admin",array('status'=>'Inactive','emailverified'=>'No'));
					$submittedBoms = $this->AdminModel->countRows("e_bom",array('approved_status'=>'Submitted'));
					$submittedMats = $this->AdminModel->countRows("e_mat",array('approved_status'=>'Submitted'));
					$submittedAdvs = $this->AdminModel->countRows("e_adv",array('approved_status'=>'Submitted'));
					$submittedTempis = $this->AdminModel->countRows("e_tempissue",array('approved_status'=>'Submitted'));
					$submittedPos = $this->AdminModel->countRows("e_po",array('approved_status'=>'Submitted'));
					$submittedDc = $this->AdminModel->countRows("e_dc",array('approved_status'=>'Submitted'));
					$submittedMM = $this->AdminModel->countRows("e_mm",array('approved_status'=>'Submitted'));
					$submittedRSTK = $this->AdminModel->countRows("e_restock",array('approved_status'=>'Submitted'));
					$submittedREL = $this->AdminModel->countRows("e_release",array('approved_status'=>'Submitted'));
				}
				elseif(($this->session->role=='level2') && $this->session->departmentid!=234 ){
					$newUsersNotifications=$this->AdminModel->countRows("e_admin",array('status'=>'Inactive','emailverified'=>'No','departmentid'=>$this->session->departmentid));
					$submittedBoms = $this->AdminModel->countRows("e_bom",array('approved_status'=>'Submitted','department_id'=>$this->session->departmentid));
					$submittedMats = $this->AdminModel->countRows("e_mat",array('approved_status'=>'Submitted','department_id'=>$this->session->departmentid));
					$submittedAdvs = $this->AdminModel->countRows("e_adv",array('approved_status'=>'Submitted','department_id'=>$this->session->departmentid));
					$submittedTempis = $this->AdminModel->countRows("e_tempissue",array('approved_status'=>'Submitted','department_id'=>$this->session->departmentid));
					$submittedPos = $this->AdminModel->countRows("e_po",array('approved_status'=>'Submitted'));
					$submittedMM = $this->AdminModel->countRows("e_mm",array('approved_status'=>'Submitted'));//,'department_id'=>$this->session->departmentid
					$submittedRSTK = $this->AdminModel->countRows("e_restock",array('approved_status'=>'Submitted','department_id'=>$this->session->departmentid));
					$submittedREL = $this->AdminModel->countRows("e_release",array('approved_status'=>'Submitted','department_id'=>$this->session->departmentid));
				}
				elseif(($this->session->role=='level1') && $this->session->departmentid!=234 ){
					$submittedBoms = $this->AdminModel->countRows("e_bom",array('approved_status'=>'Draft','department_id'=>$this->session->departmentid));
					$submittedMats = $this->AdminModel->countRows("e_mat",array('approved_status'=>'Draft','department_id'=>$this->session->departmentid));
					$submittedAdvs = $this->AdminModel->countRows("e_adv",array('approved_status'=>'Draft','department_id'=>$this->session->departmentid));
					$submittedTempis = $this->AdminModel->countRows("e_tempissue",array('approved_status'=>'Draft','department_id'=>$this->session->departmentid));
					$submittedMM = $this->AdminModel->countRows("e_mm",array('approved_status'=>'Draft'));
					$submittedPos = $this->AdminModel->countRows("e_po",array('approved_status'=>'Draft'));
					$submittedRSTK = $this->AdminModel->countRows("e_restock",array('approved_status'=>'Draft','generated_by'=>$this->session->userid));
					$submittedREL = $this->AdminModel->countRows("e_release",array('approved_status'=>'Draft','generated_by'=>$this->session->userid));
				}
				elseif(($this->session->role=='level2') && $this->session->departmentid==234 ){//store
					$newUsersNotifications=$this->AdminModel->countRows("e_admin",array('status'=>'Inactive','emailverified'=>'No','departmentid'=>$this->session->departmentid));
					#$submittedBoms = $this->AdminModel->countRows("e_bom",array('approved_status'=>'Approved'));
					$submittedMats = $this->AdminModel->countRows("e_mat",array('approved_status'=>'Approved'));
					$submittedAdvs = $this->AdminModel->countRows("e_adv",array('approved_status'=>'Approved'));
					$submittedTempis = $this->AdminModel->countRows("e_tempissue",array('approved_status'=>'Approved'));
					$submittedPos = $this->AdminModel->countRows("e_po",array('approved_status'=>'Submitted'));
					$submittedDc = $this->AdminModel->countRows("e_dc",array('approved_status'=>'Submitted'));
					$submittedMM = $this->AdminModel->countRows("e_mm",array('approved_status'=>'Submitted'));
					$submittedRSTK = $this->AdminModel->countRows("e_restock",array('approved_status'=>'Approved'));
					$submittedREL = $this->AdminModel->countRows("e_release",array('approved_status'=>'Approved'));
				}
				elseif(($this->session->role=='level1') && $this->session->departmentid==234 ){//stores
					#$submittedBoms = $this->AdminModel->countRows("e_bom",array('approved_status'=>'Approved'));
					$submittedMats = $this->AdminModel->countRows("e_mat",array('approved_status'=>'Approved'));
					$submittedAdvs = $this->AdminModel->countRows("e_adv",array('approved_status'=>'Approved'));
					$submittedTempis = $this->AdminModel->countRows("e_tempissue",array('approved_status'=>'Approved'));
					$submittedPos = $this->AdminModel->countRows("e_po",array('approved_status'=>'Draft'));
					$submittedDc = $this->AdminModel->countRows("e_dc",array('approved_status'=>'Draft'));
					$submittedMM = $this->AdminModel->countRows("e_mm",array('approved_status'=>'Draft'));
					$submittedRSTK = $this->AdminModel->countRows("e_restock",array('approved_status'=>'Approved'));
					$submittedREL = $this->AdminModel->countRows("e_release",array('approved_status'=>'Approved'));
				}
			 	?>
                
                <?php 	if($newUsersNotifications>0):?>
                <li> <a href="<?=base_url()?>Home/users"> <span class="time">
                  <?=$newUsersNotifications?>
                  </span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-check"></i></span> New User(s) Registered </span> </a> </li>
                <?php endif?>
                
                <?php 	if(@$submittedBoms>0):
						if($this->session->departmentid==234)
						$urlx= base_url().'Home/reqs';
						else
						$urlx = base_url().'Home/bom';
						?>
                <li> <a href="<?=$urlx?>"> <span class="time">
                  <?=$submittedBoms?>
                  </span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-check"></i></span> Pending BOM(s) </span> </a> </li>
                <?php endif?>
                
                <?php if(@$submittedMats>0):
                		if($this->session->departmentid==234)
						$urlx= base_url().'Home/reqs#mat';
						else
						$urlx = base_url().'Home/mat';
						?>
                <li> <a href="<?=$urlx?>"> <span class="time">
                  <?=$submittedMats?>
                  </span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-check"></i></span> Pending Mat(s) </span> </a> </li>
                <?php endif?>
                
                <?php if(@$submittedAdvs>0):
                		if($this->session->departmentid==234)
						$urlx= base_url().'Home/reqs#adv';
						else
						$urlx = base_url().'Home/adv';
						?>
                <li> <a href="<?=$urlx?>"> <span class="time">
                  <?=$submittedAdvs?>
                  </span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-check"></i></span> Pending Adv Bookings(s) </span> </a> </li>
                <?php endif?>
                
                <?php if(@$submittedTempis>0):
                		if($this->session->departmentid==234)
						$urlx= base_url().'Home/reqs#tempissue';
						else
						$urlx = base_url().'Home/tempissue';
						?>
                <li> <a href="<?=$urlx?>"> <span class="time">
                  <?=$submittedTempis?>
                  </span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-check"></i></span> Pending Temp Issue(s) </span> </a> </li>
                <?php endif?>
                
                <?php if(@$submittedPos>0):
                		if($this->session->departmentid==234)
						$urlx= base_url().'Home/reqs#grn';
						else
						$urlx = base_url().'Home/po';
						?>
                <li> <a href="<?=$urlx?>"> <span class="time">
                  <?=$submittedPos?>
                  </span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-check"></i></span> Pending PO(s) </span> </a> </li>
                <?php endif?>
                
                <?php if(@$submittedDc>0):
                if($this->session->departmentid==234)
						$urlx= base_url().'Home/reqs';
						else
						$urlx = base_url().'Home/dc';
						?>
                <li> <a href="<?=$urlx?>"> <span class="time">
                  <?=$submittedDc?>
                  </span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-check"></i></span> Pending Del Challan(s) </span> </a> </li>
                <?php endif?>
                
                <?php if(@$submittedMM>0):
                	if($this->session->departmentid==234)
						$urlx= base_url().'Home/reqs#mmov';
						else
						$urlx = base_url().'Home/mmov';
						?>
                <li> <a href="<?=$urlx?>"> <span class="time">
                  <?=$submittedMM?>
                  </span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-check"></i></span> Pending Mat.Mov(s) </span> </a> </li>
                <?php endif?>
                
                
                <?php if(@$submittedRSTK>0):
                		if($this->session->departmentid==234)
						$urlx= base_url().'Home/reqs#restock';
						else
						$urlx = base_url().'Home/restock';
						?>
                <li> <a href="<?=$urlx?>"> <span class="time">
                  <?=$submittedRSTK?>
                  </span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-check"></i></span> Pending Restcok(s) </span> </a> </li>
                <?php endif?>
                
                
                <?php if(@$submittedREL>0):
                	if($this->session->departmentid==234)
						$urlx= base_url().'Home/reqs';
						else
						$urlx = base_url().'Home/release';
						?>
                <li> <a href="<?=$urlx?>"> <span class="time">
                  <?=$submittedREL?>
                  </span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-check"></i></span> Pending Release(s) </span> </a> </li>
                <?php endif?>
                
                <!--<li> <a href="<?=base_url()?>formats/daily-vaccination.xlsx"> <span class="time">1</span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-check"></i></span> Daily Vaccinations Input Format </span> </a> </li>
                <li> <a href="<?=base_url()?>formats/health-sessions.xlsx"> <span class="time">1</span> <span class="details"> <span class="notification-icon circle purple-bgcolor"><i class="fa fa-user o"></i></span> Health Session Input Format</span> </a> </li>
                <li> <a href="<?=base_url()?>formats/feedback.xlsx"> <span class="time">1</span> <span class="details"> <span class="notification-icon circle blue-bgcolor"><i class="fa fa-comments-o"></i></span> Complaints/Feedback Format</span> </a> </li>
                <li> <a href="<?=base_url()?>formats/vaccines-vials.xlsx"> <span class="time">1</span> <span class="details"> <span class="notification-icon circle deepPink-bgcolor"><i class="fa fa-cubes"></i></span> Vaccine Vials Record Format</span> </a> </li>-->
              </ul>
              <!--<div class="dropdown-menu-footer"> <a href="javascript:void(0)"> All notifications </a> </div>-->
            </li>
          </ul>
        </li>
        <!-- end notification dropdown -->
        <!-- start message dropdown 
						<li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
								data-close-others="true">
								<i class="fa fa-cloud-download"></i>
								<span class="badge headerBadgeColor2"> 2 </span>
							</a>
							<ul class="dropdown-menu animated slideInDown">
								<li class="external">
									<h3><span class="bold">Messages</span></h3>
									<span class="notification-label cyan-bgcolor">New 2</span>
								</li>
								<li>
									<ul class="dropdown-menu-list small-slimscroll-style" data-handle-color="#637283">
										<li>
											<a href="#">
												<span class="photo">
													<img src="assets/img/user/user2.jpg" class="img-circle" alt="">
												</span>
												<span class="subject">
													<span class="from"> Sarah Smith </span>
													<span class="time">Just Now </span>
												</span>
												<span class="message"> Jatin I found you on LinkedIn... </span>
											</a>
										</li>
										<li>
											<a href="#">
												<span class="photo">
													<img src="assets/img/user/user3.jpg" class="img-circle" alt="">
												</span>
												<span class="subject">
													<span class="from"> John Deo </span>
													<span class="time">16 mins </span>
												</span>
												<span class="message"> Fwd: Important Notice Regarding Your Domain
													Name... </span>
											</a>
										</li>
										<li>
											<a href="#">
												<span class="photo">
													<img src="assets/img/user/user1.jpg" class="img-circle" alt="">
												</span>
												<span class="subject">
													<span class="from"> Rajesh </span>
													<span class="time">2 hrs </span>
												</span>
												<span class="message"> pls take a print of attachments. </span>
											</a>
										</li>
										<li>
											<a href="#">
												<span class="photo">
													<img src="assets/img/user/user8.jpg" class="img-circle" alt="">
												</span>
												<span class="subject">
													<span class="from"> Lina Smith </span>
													<span class="time">40 mins </span>
												</span>
												<span class="message"> Apply for Ortho Surgeon </span>
											</a>
										</li>
										<li>
											<a href="#">
												<span class="photo">
													<img src="assets/img/user/user5.jpg" class="img-circle" alt="">
												</span>
												<span class="subject">
													<span class="from"> Jacob Ryan </span>
													<span class="time">46 mins </span>
												</span>
												<span class="message"> Request for leave application. </span>
											</a>
										</li>
									</ul>
									<div class="dropdown-menu-footer">
										<a href="#"> All Messages </a>
									</div>
								</li>
							</ul>
						</li>-->
        <!-- end message dropdown -->
        <!-- start manage user dropdown -->
        <li class="dropdown dropdown-user"> <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
								data-close-others="true"> <img src="<?=base_url()?>assets/img/<?=$userData['image']?>" class="img-circle" alt="<?=$userData['image']?>"> <span class="username username-hide-on-mobile">
          <?=$userData['name']?>
          </span> <i class="fa fa-angle-down"></i> </a>
          <ul class="dropdown-menu dropdown-menu-default animated jello">
            <li> <a href="<?=base_url()?>Home/user_profile"> <i class="icon-user"></i> Change Password </a> </li>
            <li> <a href="<?=base_url()?>Login/logout"> <i class="icon-logout"></i> Log Out </a> </li>
          </ul>
        </li>
        <!-- end manage user dropdown
						<li class="dropdown dropdown-quick-sidebar-toggler">
							<a id="headerSettingButton" class="mdl-button mdl-js-button mdl-button--icon pull-right"
								data-upgraded=",MaterialButton">
								<i class="material-icons">settings</i>
							</a>
						</li> -->
      </ul>
    </div>
  </div>
</div>
<!-- end header -->
<!-- start page container -->
<div class="page-container">
<!-- start sidebar menu -->
<div class="sidebar-container">
  <!-- style=" overflow-y: scroll; height:86vh"-->
  <div class="sidemenu-container navbar-collapse collapse fixed-menu">
    <div id="remove-scroll">
      <ul class="sidemenu page-header-fixed p-t-20" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <li class="sidebar-toggler-wrapper hide">
          <div class="sidebar-toggler"> <span></span> </div>
        </li>
        <!--<li class="sidebar-user-panel">&nbsp;</li>-->
        <?php if($this->session->role=='admin') :?>
        
            <li class="nav-item <?php if(@strpos("Dashboard",$controler )!== false){echo 'active';}?>"> 
                <a href="<?=base_url()?>Dashboard/index" class="nav-link "> 
                    <i class="material-icons">dashboard</i> <span class="title">Dashboard</span> 
                </a>
            </li>
        	<li class="nav-item  <?php if(@strpos("items,itemqty,itemcustody",$function )!== false){echo 'active';}?> "> 
            	<a href="#" class="nav-link nav-toggle"> 
                	<i class="material-icons">assistant</i> <span class="title">Items /Inventory</span> <span class="arrow"></span> 
                </a>
          		<ul class="sub-menu">
            		<li class="nav-item <?php if(@strpos("items",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/items" class="nav-link "> <span class="title">Manage Items</span></a> 
                    </li>
            		<!--<li class="nav-item <?php if(@strpos("itemqty",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/itemqty" class="nav-link "> <span class="title">Manage Item Stock</span></a> 
                    </li>
            		<li class="nav-item <?php if(@strpos("itemcustody",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/itemcustody" class="nav-link "> <span class="title">Items Custodianship</span></a> 
                    </li>-->
          		</ul>
        	</li>
        	<li class="nav-item <?php if(@strpos("bom",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/bom" class="nav-link "><i class="material-icons">payment</i><span class="title">Manage BOM</span></a> 			
            </li>
            <li class="nav-item <?php if(@strpos("bmlib",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/bmlib" class="nav-link "><i class="material-icons">payment</i><span class="title">BOM Lib</span></a> 			
            </li>
        	<li class="nav-item <?php if(@strpos("mat",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/mat" class="nav-link "><i class="material-icons">assignment</i> <span class="title">Manage MAT</span></a>
            </li>
        	<li class="nav-item  <?php if(@strpos("po,pol",$function )!== false){echo 'active';}?> ">
            	<a href="#" class="nav-link nav-toggle"> 
                	<i class="material-icons">store</i> <span class="title">Manage POL</span><span class="arrow"></span> </a>
          		<ul class="sub-menu">
            		<li class="nav-item <?php if(@strpos("po",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/po" class="nav-link "> <span class="title">Manage PO</span></a> </li>
            		<li class="nav-item <?php if(@strpos("pol",$function )!== false){echo 'active';}?>"> 
                		<a href="<?=base_url()?>Home/pol" class="nav-link "> <span class="title">POL</span></a> </li>
          		</ul>
        	</li>
        	<li class="nav-item <?php if(@strpos("grn",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/grn" class="nav-link ">
                	<i class="material-icons">assignment_late</i><span class="title">Manage GRN</span></a>
            </li>
            
            
            
            <li class="nav-item <?php if(@strpos("advbooking",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/adv" class="nav-link "><i class="material-icons">book</i><span class="title">Adv Booking</span></a>
            </li>
            <li class="nav-item <?php if(@strpos("release",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/release" class="nav-link ">
                	<i class="material-icons">content_paste</i><span class="title">Rel.Request</span></a>
            </li>
            <li class="nav-item <?php if(@strpos("tempissue",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/tempissue" class="nav-link ">
                	<i class="material-icons">bookmark</i><span class="title">Temp Issue</span></a>
            </li>
            <li class="nav-item <?php if(@strpos("restock",$function )!== false){echo 'active';}?>"> 
               <a href="<?=base_url()?>Home/restock" class="nav-link "><i class="material-icons">business</i><span class="title">Restock</span></a>
            </li>
            <li class="nav-item <?php if(@strpos("dc",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/dc" class="nav-link ">
                	<i class="material-icons">settings</i><span class="title">Del. Challan</span></a>
            </li>
            <li class="nav-item <?php if(@strpos("mmov",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/mmov" class="nav-link ">
                	<i class="material-icons">compare</i><span class="title">Mat. Movement</span></a>
            </li>
            <li class="nav-item <?php if(@strpos("fixed",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/fixed" class="nav-link ">
                	<i class="material-icons">details</i><span class="title">Fixed Assets</span></a>
            </li>
            
    
            
        <li class="nav-item <?php if(@strpos("settings,users,suppliers,customers,departments,projects,categories,units,rwrdata,elogs",$function )!== false){			echo 'active';}?> "> 
            	<a href="#" class="nav-link nav-toggle"> 
                <i class="material-icons">ac_unit</i> <span class="title">Configurations</span> <span class="arrow"></span> </a>
          		<ul class="sub-menu">
            		<li class="nav-item <?php if(@strpos("settings",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/settings" class="nav-link "> <span class="title">General Settings</span></a> </li>
            		<li class="nav-item <?php if(@strpos("units",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/units" class="nav-link "> <span class="title">Manage Units</span></a> </li>
            		<li class="nav-item <?php if(@strpos("categories",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/categories" class="nav-link "> <span class="title">Manage Categories</span></a> </li>
            		<li class="nav-item <?php if(@strpos("departments",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/departments" class="nav-link "> <span class="title">Manage Departments</span></a> </li>
            		<li class="nav-item <?php if(@strpos("projects",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/projects" class="nav-link "> <span class="title">Manage Projects</span></a> </li>
            		<li class="nav-item <?php if(@strpos("suppliers",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/suppliers" class="nav-link "> <span class="title">Manage Suppliers</span></a> </li>
            		<li class="nav-item <?php if(@strpos("customers",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/customers" class="nav-link "> <span class="title">Manage Customers</span></a> </li>
            		<li class="nav-item <?php if(@strpos("users",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/users" class="nav-link "> <span class="title">MIS Users</span></a> </li>
                    <li class="nav-item <?php if(@strpos("rwrdata",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/rwrdata" class="nav-link "> <span class="title">RWR Data</span></a> </li>
                    <li class="nav-item <?php if(@strpos("elogs",$function )!== false){echo 'active';}?>"> 
                    	<a href="<?=base_url()?>Home/elogs" class="nav-link "> <span class="title">User Logs</span></a> </li>
          		</ul>
        </li>
        
        
        <li class="nav-item <?php if(@strpos("feedbackform",$function )!== false){echo 'active';}?>">
        	<a href="<?=base_url()?>Home/feedbackform" class="nav-link "> 
            	<i class="material-icons">language</i> <span class="title">Complaints/Feedback</span> </a> </li>
        <li class="nav-item  <?php if(@strpos("dailyvt,dailygov,vialsrpt,healthsessionsrpt,feedbackrpt",$function )!== false){echo 'active';}?> ">
         	<a href="#" class="nav-link nav-toggle"> 
         	<i class="material-icons">account_balance</i> <span class="title">Reports</span> <span class="arrow"></span> </a>
          	<ul class="sub-menu">
            	<li class="nav-item <?php if(@strpos("feedbackrpt",$function )!== false){echo 'active';}?>"> 
                	<a href="<?=base_url()?>Home/feedbackrpt" class="nav-link "> <span class="title">Feedback/Issues</span></a> </li>
          	</ul>
        </li>
         <?php elseif(($this->session->role=='level1' || $this->session->role=='level2') && $this->session->departmentid==234 ) ://store
		 $privileges=$this->session->privileges; 
		 ?>
        
        <li class="nav-item <?php if(@strpos("Dashboard",$controler )!== false){echo 'active';}?>"> 
        	<a href="<?=base_url()?>Dashboard/index" class="nav-link "> 
            	<i class="material-icons">dashboard</i> <span class="title">Dashboard</span> </a> </li>
                
        <li class="nav-item <?php if(@strpos("reqs",$function )!== false){echo 'active';}?>"> 
        	<a href="<?=base_url()?>Home/reqs" class="nav-link "> 
            	<i class="material-icons">payment</i> <span class="title">Manage Requests</span> </a> </li>
         <li class="nav-item  <?php if(@strpos("po,pol",$function )!== false){echo 'active';}?> "> 
        	<a href="#" class="nav-link nav-toggle"> 
            	<i class="material-icons">store</i> <span class="title">Manage POL</span> <span class="arrow"></span> </a>
          	<ul class="sub-menu">
            <?php if(strpos($privileges,'po')!==false):?>
            	<li class="nav-item <?php if(@strpos("po",$function )!== false){echo 'active';}?>"> 
                	<a href="<?=base_url()?>Home/po" class="nav-link "> <span class="title">Manage PO</span></a> </li>
            <?php endif?>
            	<li class="nav-item <?php if(@strpos("pol",$function )!== false){echo 'active';}?>"> 
                	<a href="<?=base_url()?>Home/pol" class="nav-link "> <span class="title">POL</span></a> </li>
          	</ul>
        </li>
        
        <li class="nav-item <?php if(@strpos("grn",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/grn" class="nav-link ">
                	<i class="material-icons">bookmark</i><span class="title">GRN</span></a>
            </li>
        
        <?php if(strpos($privileges,'mat')!==false):?>
        <li class="nav-item <?php if(@strpos("mat",$function )!== false && strpos($privileges,'mat')!==false){echo 'active';}?>"> 
        	<a href="<?=base_url()?>Home/mat" class="nav-link ">
            	<i class="material-icons">payment</i> <span class="title">Manage MAT</span> </a> </li>
        <?php endif?>
            
        <?php if(strpos($privileges,'tempissue')!==false):?>
            <li class="nav-item <?php if(@strpos("tempissue",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/tempissue" class="nav-link ">
                	<i class="material-icons">bookmark</i><span class="title">Temp Issue</span></a>
            </li>
        <?php endif?>
        
       <?php if(strpos($privileges,'dc')!==false):?>     
      	<li class="nav-item <?php if(@strpos("dc",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/dc" class="nav-link ">
                	<i class="material-icons">settings</i><span class="title">Del. Challan</span></a>
            </li>
       <?php endif  ?>
       <?php if(strpos($privileges,'mmov')!==false):?>
            <li class="nav-item <?php if(@strpos("mmov",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/mmov" class="nav-link ">
                	<i class="material-icons">compare</i><span class="title">Mat. Movement</span></a>
            </li>
            <li class="nav-item <?php if(@strpos("mmbyuser",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/mmbyuser" class="nav-link ">
                	<i class="material-icons">compare</i><span class="title">MM By User(s)</span></a>
            </li>
       <?php endif?>
        
      <!--  <li class="nav-item  <?php if(@strpos("items,categories",$function )!== false){echo 'active';}?> "> 
        	<a href="#" class="nav-link nav-toggle"> 
            	<i class="material-icons">store</i> <span class="title">Items /Inventory</span> <span class="arrow"></span> </a>
          	<ul class="sub-menu">
            	<li class="nav-item <?php if(@strpos("items",$function )!== false){echo 'active';}?>"> 
                	<a href="<?=base_url()?>Home/items" class="nav-link "> <span class="title">Manage Items</span></a> </li>
            	<li class="nav-item <?php if(@strpos("categories",$function )!== false){echo 'active';}?>"> 
                	<a href="<?=base_url()?>Home/categories" class="nav-link "> <span class="title">Manage Categories</span></a> </li>
          	</ul>
        </li>
        
        -->
        
        <li class="nav-item <?php if(@strpos("feedbackform",$function )!== false){echo 'active';}?>"> 
        	<a href="<?=base_url()?>Home/feedbackform" class="nav-link "> 
            	<i class="material-icons">language</i> <span class="title">Complaints/Feedback</span> </a> </li>

        <?php elseif($this->session->role=='level1' || $this->session->role=='level2') :
		 $privileges=$this->session->privileges; 
		?>
        
        <li class="nav-item <?php if(@strpos("Dashboard",$controler )!== false){echo 'active';}?>"> 
        	<a href="<?=base_url()?>Dashboard/index" class="nav-link "> 
            	<i class="material-icons">dashboard</i> <span class="title">Dashboard</span> </a> </li>
        <?php if(strpos($privileges,'bom')!==false):?>
        <li class="nav-item <?php if(@strpos("bom",$function )!== false ){echo 'active';}?>"> 
        	<a href="<?=base_url()?>Home/bom" class="nav-link "> 
            	<i class="material-icons">payment</i> <span class="title">Manage BOM</span> </a> </li>
        <?php endif ?>
        <li class="nav-item <?php if(@strpos("bmlib",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/bmlib" class="nav-link "><i class="material-icons">payment</i><span class="title">BOM Library</span></a> 			
            </li>
        <?php if(strpos($privileges,'mat')!==false):?>
        <li class="nav-item <?php if(@strpos("mat",$function )!== false && strpos($privileges,'mat')!==false){echo 'active';}?>"> 
        	<a href="<?=base_url()?>Home/mat" class="nav-link ">
            	<i class="material-icons">payment</i> <span class="title">Manage MAT</span> </a> </li>
        <?php endif?>
        
        <li class="nav-item  <?php if(@strpos("po,pol",$function )!== false){echo 'active';}?> "> 
        	<a href="#" class="nav-link nav-toggle"> 
            	<i class="material-icons">store</i> <span class="title">Manage POL</span> <span class="arrow"></span> </a>
          	<ul class="sub-menu">
            <?php if(strpos($privileges,'po')!==false):?>
            <li class="nav-item <?php if(@strpos("po",$function )!== false){echo 'active';}?>"> 
                	<a href="<?=base_url()?>Home/po" class="nav-link "> <span class="title">Manage PO</span></a> </li>
            <?php endif ?>
            	<li class="nav-item <?php if(@strpos("pol",$function )!== false){echo 'active';}?>"> 
                	<a href="<?=base_url()?>Home/pol" class="nav-link "> <span class="title">POL</span></a> </li>
          	</ul>
        </li>
        
        <?php if(strpos($privileges,'mmov')!==false):?>
        <li class="nav-item <?php if(@strpos("mmov",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/mmov" class="nav-link ">
                	<i class="material-icons">compare</i><span class="title">Mat. Movement</span></a>
            </li>
            <li class="nav-item <?php if(@strpos("mmbyuser",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/mmbyuser" class="nav-link ">
                	<i class="material-icons">compare</i><span class="title">MM By User(s)</span></a>
            </li>
        <?php endif?>
        <?php if(strpos($privileges,'adv')!==false):?>
         <li class="nav-item <?php if(@strpos("advbooking",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/adv" class="nav-link "><i class="material-icons">book</i><span class="title">Adv Booking</span></a>
            </li>
        <?php endif?>
        <?php if(strpos($privileges,'release')!==false):?>
            <li class="nav-item <?php if(@strpos("release",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/release" class="nav-link ">
                	<i class="material-icons">content_paste</i><span class="title">Rel.Request</span></a>
            </li>
        <?php endif ?>
        <?php if(strpos($privileges,'tempissue')!==false):?>
            <li class="nav-item <?php if(@strpos("tempissue",$function )!== false){echo 'active';}?>"> 
            	<a href="<?=base_url()?>Home/tempissue" class="nav-link ">
                	<i class="material-icons">bookmark</i><span class="title">Temp Issue</span></a>
            </li>
        <?php endif?>
        <?php if(strpos($privileges,'restock')!==false):?>
            <li class="nav-item <?php if(@strpos("restock",$function )!== false){echo 'active';}?>"> 
               <a href="<?=base_url()?>Home/restock" class="nav-link "><i class="material-icons">business</i><span class="title">Restock</span></a>
            </li>
        <?php endif?>
        
        <!--<li class="nav-item  <?php if(@strpos("items,categories",$function )!== false){echo 'active';}?> "> 
        	<a href="#" class="nav-link nav-toggle"> 
            	<i class="material-icons">store</i> <span class="title">Items /Inventory</span> <span class="arrow"></span> </a>
          	<ul class="sub-menu">
            	<li class="nav-item <?php if(@strpos("items",$function )!== false){echo 'active';}?>"> 
                	<a href="<?=base_url()?>Home/items" class="nav-link "> <span class="title">Manage Items</span></a> </li>
            	<li class="nav-item <?php if(@strpos("categories",$function )!== false){echo 'active';}?>"> 
                	<a href="<?=base_url()?>Home/categories" class="nav-link "> <span class="title">Manage Categories</span></a> </li>
          	</ul>
        </li>-->
        
        <li class="nav-item <?php if(@strpos("feedbackform",$function )!== false){echo 'active';}?>"> 
        	<a href="<?=base_url()?>Home/feedbackform" class="nav-link "> 
            	<i class="material-icons">language</i> <span class="title">Complaints/Feedback</span> </a> </li>

        <?php endif ?>
      </ul>
    </div>
  </div>
</div>
<!-- end sidebar menu -->

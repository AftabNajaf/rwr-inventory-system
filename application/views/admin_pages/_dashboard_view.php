<?php $this->load->view('admin_layout/header'); ?>
<style>
.highcharts-credits{
	visibility:hidden
}
</style>

<div class="page-content-wrapper">
	<div class="page-content">
		<div class="page-bar">
						<div class="page-title-breadcrumb">
							<div class=" pull-left">
								<div class="page-title">Dashboard</div>
							</div>
							<ol class="breadcrumb page-breadcrumb pull-right">
								<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
										href="/">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
								</li>
							</ol>
						</div>
					</div>
                    
        <div class="row">
						<div class="col-lg-3 col-md-6 col-sm-12 col-12">
							<div class="card bg-success">
								<div class="panel-body">
                                <span class="margin-top-0 full-width">Vaccine Doses Administered</span>
									<h3><?=$data['totalVaccinations']?></h3>
                  				</div>
							</div>
						</div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-12 col-12">
							<div class="card bg-blue">
								<div class="panel-body">
									<span class="text-small margin-top-10 full-width">Administerd Males : <?=$data['totalMales']?></span>
                                    <span class="text-small margin-top-10 full-width">Administerd Females : <?=$data['totalFemales']?></span>
                                    <span class="text-small margin-top-10 full-width">Administerd Transgenders : <?=$data['totalTransgenders']?></span>
								</div>
							</div>
						</div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-12 col-12">
							<div class="card bg-info">
								<div class="panel-body">
									<span class="text-small margin-top-10 full-width">Vaccinations by Team - A : <?=$data['totalTeama']?></span>
                                    <span class="text-small margin-top-10 full-width">Vaccinations by Team - B : <?=$data['totalTeamb']?></span>
                                    <span class="text-small margin-top-10 full-width">Vaccinations by Team - C : <?=$data['totalTeamc']?></span>
								</div>
							</div>
						</div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-12 col-12">
							<div class="card  bg-primary">
								<div class="panel-body">
                                	<span class="margin-top-10 pull-left">Total 1st Doses : </span>
                                    <span class="text-small margin-top-10 pull-right"><?=$data['totalFirstDoses']?></span>
									<span class="margin-top-10 pull-left">Total 2nd Doses : </span>
                                    <span class="text-small margin-top-10 pull-right"><?=$data['totalSecondDoses']?></span>
                                    <span class="margin-top-10 pull-left">Total Booster Doses : </span>
                                    <span class="text-small margin-top-10 pull-right"><?=$data['totalBossters']?></span>
								</div>
							</div>
						</div>
                        
                        
					</div>    
	</div>	        			
</div>

<style>
.chart-outer {
  display: flex;
}

.highcharts-data-table {
  display: flex;
  flex-direction: column;
  justify-content: space-around;
}

#highcharts-data-table-0,
#highcharts-data-table-1 {
  margin: 0;
}

#container1,
#container2 {
  height: 300px;
  padding-right: 2em;
  width: 50%;
}

.highcharts-data-table table {
  border-collapse: collapse;
  border-spacing: 0;
  background: white;
  min-width: 100%;
  margin-top: 10px;
  font-family: sans-serif;
  font-size: 0.9em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
  border: 1px solid silver;
  padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
  background: #f8f8f8;
}

.highcharts-data-table tr:hover {
  background: #eff;
}

.highcharts-data-table caption {
  border-bottom: none;
  font-size: 1.1em;
  font-weight: bold;
}
    
</style>

<?php $this->load->view('admin_layout/footer');?>
<script>
$(document).ready(function () {
	$('#exptable').DataTable({
		"aaSorting": [],
				"paging": false,
				dom: 'Bfrtip',
				buttons: ['copy', 'csv', 'excel', 'print', 'pdf']
    });	
	$('#revtable').DataTable({
		"aaSorting": [],
				"scrollY":        "180px",
        "scrollCollapse": true,
		"paging":         false,
				dom: 'Bfrtip',
				buttons: ['copy', 'csv', 'excel', 'print', 'pdf']
    });					

	 	 
});

</script>


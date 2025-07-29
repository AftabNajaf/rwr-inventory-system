<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site_function extends CI_Controller {

	public function __construct() {

        parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('function_helper');
		//$this->load->helper('simplexlsx_class_helper');
		$this->load->Model('AdminModel');
		$this->load->Model('SiteModel');
		$this->load->library('image_lib');
		$this->load->library('upload');
		if (!isset($_SESSION))
		session_start();
    }

	public function index()
	{
	  echo "Nothing to show. Site_function";
	}
	
	
	#################gilgit bazar#########
	public function getIndicatorsByGoalArr4Data()
	{
		$goalArr = $this->input->post('district');
		$obj='';
		if($goalArr == "") {
			echo "";
			return false;
		}

		$ind = $this->AdminModel->getWhereIn('e_locations',array('district',$goalArr));
		//$obj.= '<select  class="form-control select2-multiple col-12" multiple  id="lokation" name="lokation" placeholder="Select Location"  onchange="loadmaps(0)">';
              foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[healthunitid]'>".$value['title']."</option>";
                  }    
        echo $obj;//.='</select>';
       //echo "<script>$('#district').selectize({plugins: ['remove_button'],maxItems : [4] });</ script>";
	}
	
	public function projectionDistricts()
	{
		$goalArr = $this->input->post('province');
		if($goalArr == "") {
			echo "";
			return false;
		}

		$ind = $this->AdminModel->getWhereIn('e_cities',array('province_id',$goalArr));
		echo '<select  multiple onchange="projections(0)" id="pdistrict"   placeholder="Select Districs">';
              foreach ($ind as $key => $value) {
                  	echo "<option value='$value[city_id]'>".$value['city_name']."</option>";
                  }    
        echo '</select>';
       echo "<script>$('#pdistrict').selectize({plugins: ['remove_button'],maxItems : [1] });</script>";
	}
	
	
	public function getIndicator()
	{
		$goal_no = $this->input->post('goal_no');
		getOption('e_indicator','indicator_no','indicator_no',array('goal_no'=>$goal_no));
		
	}
	public function getIndicatorsByGoalArr()
	{
		$goalArr = $this->input->post('sGoals');
		if($goalArr == "") $goalArr = array('1');
		$ind = $this->AdminModel->getWhereIn('e_indicator',array('goal_no',$goalArr));
		echo '<select  onchange="renderSummary()"  multiple id="indicatorIn"   placeholder="Indicator Selected All...">';
              foreach ($ind as $key => $value) {
                  	echo "<option value='$value[indicator_no]'>".$value['indicator_no']."</option>";
                  }    
        echo '</select>';
       echo "<script>$('#indicatorIn').selectize({plugins: ['remove_button'] });</script>";
	}
	
	public function getNormalIndicator()
	{
		$goal_no = $this->input->post('goal_no');
		getOption('e_indicator','indicator_no','indicator_no',"",array('goal_no'=>$goal_no));
	}
	//by comparson
	public function getDataYears()
	{
		$goal_no = $this->input->post('goal_no');
		$indicators = $this->input->post('indicators');
		$level = strtolower($this->input->post('level'));
		$sub_level = $this->input->post('sub_level');
		$cond = array(
			'goal_no' => $goal_no,
			'level'	=> $level,
			'status' => 'active'
		);
		if($indicators != "")
			$cond['indicator_no'] = $indicators;
		if($level != 'national' && $sub_level != "")
			$cond['sub_level'] = $sub_level;
		$res = $this->SiteModel->getDistinct('data','year',$cond,TRUE);
		echo "<select onchange='yearsIn1Change()' multiple placeholder='Select Years' id='yearsIn1'>";
		foreach ($res as $key => $value) {
			echo "<option value='$value[year]'>$value[year]</option>";
		}
		echo "</select>";

		echo "<script>  yearsIn1 = $('#yearsIn1'); var temp = yearsIn1.selectize({plugins: ['remove_button'] }); var yearsIn1SelectizeC = temp[0].selectize;</script>";
	}
	public function getDataYears2()
	{
		$goal_no = $this->input->post('goal_no');
		 $indicators = $this->input->post('indicators');
		$level = strtolower($this->input->post('level'));
		$sub_level = $this->input->post('sub_level');
		$cond = array(
			'goal_no' => $goal_no,
			'level'	=> $level,
			'status'=> 'active'
		);
		
		if($level != 'national' && $sub_level != "")
			$cond['sub_level'] = $sub_level;
		if($indicators != ""){
			$whereIn = array('indicator_no',$indicators);

			$res = $this->SiteModel->getDistinctWhereIn('data','year',$whereIn,$cond,TRUE);
		}
		else
			$res = $this->SiteModel->getDistinct('data','year',$cond,TRUE);

		echo "<select onchange='yearsIn2Change()' multiple placeholder='Select Years' id='yearsIn2'>";
		foreach ($res as $key => $value) {
			echo "<option value='$value[year]'>$value[year]</option>";
		}
		echo "</select>";

		echo "<script>  yearsIn2 = $('#yearsIn2'); var temp = yearsIn2.selectize({plugins: ['remove_button'] }); var yearsIn2SelectizeC = temp[0].selectize;</script>";
	}
	//by data
	public function getFiltersByGsIsLs()
	{
		$sGoals = $this->input->post('sGoals');
		$sIndicators = $this->input->post('sIndicators');
		$sLevel = $this->input->post('sLevel');
		if($sGoals == ""){
			echo "";
			return false;
		}
		if ($sIndicators != "")
			$whereIn['indicator_no'] =$sIndicators;
		else 
			$whereIn['goal_no'] = $sGoals;
		$ind = $this->SiteModel->getWhereInMultiple('e_indicator',$whereIn,array(),array('indicator_no','asc'));
		
		$rawFilters = array();
		if($sLevel == "")
		foreach ($ind as $key => $value) {
			$rawFilters[] = $value['filter_national'];
			$rawFilters[] = $value['filter_region'];
			$rawFilters[] = $value['filter_district'];
		}
		else
			foreach ($ind as $key => $value) {
			foreach ($sLevel as $level) {
				$rawFilters[] = $value['filter_'.$level];
			}
		}
		$filters_cat_ids =array_unique(array_values(explode(',',implode(',', $rawFilters))));
		echo '<select  onchange="renderData()" name="filters" multiple id="filterIn"   placeholder="Selected All">';
			foreach ($filters_cat_ids as $key => $id) {
					$filtRow = $this->SiteModel->getRow('e_filters_cat',array('filter_cat_id'=>$id));
					if(!$filtRow) continue;

				echo "<option value='$id'>".$filtRow['filter_cat_title']."</option>";
			}
		echo "</select>";
		echo "<script>$('#filterIn').selectize({plugins: ['remove_button'] });
			filters_cat_ids = '".implode(',', $filters_cat_ids)."';
			
		</script>";
	}
	public function getYears4dataByGs()
	{
		$sGoals = $this->input->post('sGoals');
		if($sGoals == "")
			return false;
		$goalCond = $this->prepareOr($sGoals,'goal_no');
		 $q = "
			SELECT DISTINCT year FROM data
			where $goalCond
		";
		$res = $this->SiteModel->myQuery($q);
        echo '<select  multiple id="yearsIn" onchange="renderData()" placeholder="All Years">';
        	foreach ($res as $key => $value) {
        		echo "<option value='$value[year]'>$value[year]</option>";
        	}
        echo "</select>";
		echo "<script>$('#yearsIn').selectize({plugins: ['remove_button'] })</script>";
	}

	public function getChart()
	{
		$data = array();
		$data['area']		= $this->input->post('area');
		$data['chartType']  = $this->input->post('chart_type');
		$data['indicator'] 	= $this->SiteModel->getRow('e_indicator',array('indicator_no'=>$this->input->post('indicator_no')));
		$data['viewType'] 	= "showChart";
		$this->load->view('site_pages/called_view',$data);
	}

	public function getTarget()
	{
		 $goal_no = $this->input->post('goal_no');
		 $data = array();

		 $data['viewType'] = 'showGoal';
		 $data['goal'] = $this->SiteModel->getRow('e_goals',array('goal_no'=>$goal_no));
		 $data['target'] = getTargets($goal_no);
		$this->load->view('site_pages/called_view', $data);
	}
	public function getProvince()
	{
		$area = $this->input->post('area_name');
		$province = $this->SiteModel->getRow('e_province',array('name'=>$area));
		if ($province) {
			$districts = $this->SiteModel->getRows('e_cities',array('province_id'=>$province['province_id']));
			if ($districts) {?>

<label class="col-sm-2">District </label>
<div class="col-sm-10">
  <select name="district" class="form-control" data-jcf='{"wrapNative": false, "wrapNativeOnMobile": false}'>
    <option value="" disabled selected>Select your District</option>
    <?php foreach ($districts as $dist): ?>
    <option value="<?=$dist['city_name']?>">
    <?=$dist['city_name']?>
    </option>
    <?php endforeach ?>
  </select>
</div>
<?php
			}
		
		}
	}
	public function chart_search()
	{
		$indicator_no = $this->input->post('indicator_no');
		$to = $this->input->post('to');
		$from = $this->input->post('from');
		$area = $this->input->post('area');
		$district = $this->input->post('district');
		if ($district) {
			$area = $district;
		}
		$this->renderChart($indicator_no,'line',$area,$from,$to);
	}
	public function prepareRendering()
	{
		$indicator_no 	= $this->input->post('indicator_no');
		$chartType		= $this->input->post('chartType');
		$area			= $this->input->post('area');
		$from			= $this->input->post('from');
		$to				= $this->input->post('to');
		
		$this->renderChart($indicator_no,$chartType,$area,$from,$to);
	}
	public function renderChart($indicator_no = "1.1.1",$chartType = 'line',$area = "Pakistan",$from = 0,$to = 0)
	{
		$indicator = $this->SiteModel->getRow('e_indicator',array('indicator_no'=>$indicator_no));
		if (!$indicator) {
			echo "No indicator found for ".$indicator_no;
			exit();
		}
		$chart_config = array();
		$chart_config['indicator_no'] 	= $indicator_no;
		$chart_config['indicator']		= $indicator;
		$chart_config['chartType']		= $chartType;
		$chart_config['area']			= $area;
		$chart_config['from']			= $from;
		$chart_config['to']				= $to;
		$chart_config['viewType']		= "renderChart";
		// print_r($chart_config);
		$this->load->view('site_pages/called_view', $chart_config);
	}


	public function prepareRenderingFilter()
	{
		$indicator_no 	= $this->input->post('indicator_no');
		$chartType		= $this->input->post('chartType');
		$area			= $this->input->post('area');
		$level			= $this->input->post('level');
		$filterId 		= $this->input->post('filterId');
		// echo "<pre>";
		// print_r($_POST);
		$this->renderChartFilter($indicator_no,$chartType,$filterId,$level,$area);
	}

	
	public function renderChartFilter($indicator_no = "1.1.1",$chartType = 'line',$filterId,$level = "National",$area = "")
	{
		$indicator = $this->SiteModel->getRow('e_indicator',array('indicator_no'=>$indicator_no));
		if (!$indicator) {
			echo "No indicator found for ".$indicator_no;
			return false;
		}
		$subFilters = $this->SiteModel->getRows('e_filters',array('filter_cat_id'=>$filterId));
		if(!$subFilters){
			echo "No Filters found";
			return false;
		}
		$chart_config = array();
		$chart_config['indicator_no'] 	= $indicator_no;
		$chart_config['indicator']		= $indicator;
		$chart_config['chartType']		= $chartType;
		$chart_config['area']			= $area;
		$chart_config['level']			= $level;
		$chart_config['filterId']		= $filterId;
		$chart_config['subFilters']		= $subFilters;
		$chart_config['viewType']		= "renderFilterChart";
		// echo "<pre>";
		// print_r($chart_config);
		$this->load->view('site_pages/chart_view', $chart_config);
	}

	//chart rendering EXPLORE AREA
	public function prepareRenderingExplore()
	{
		$indicator_no 	= $this->input->post('indicator_no');
		$chartType		= $this->input->post('chartType');
		$subLevel		= $this->input->post('subLevelVal');
		$level			= $this->input->post('level');
		if($level == "National"){
			$this->renderNationalChart($indicator_no,$chartType);
		}
		elseif($level == "Region")
		{
			$this->renderRegionChart($indicator_no,$chartType,$subLevel);
		}
		
		// $this->renderChartFilter($indicator_no,$chartType,$filterId,$level,$area);
	}
	public function renderNationalChart($indicator_no,$chartType = 'line')
	{
		$indicator = $this->SiteModel->getRow('e_indicator',array('indicator_no'=>$indicator_no));
		if (!$indicator) {
			echo "No indicator found for ".$indicator_no;
			return false;
		}
		
		$chart_config = array();
		$chart_config['indicator_no'] 	= $indicator_no;
		$chart_config['indicator']		= $indicator;
		$chart_config['chartType']		= $chartType;
		$chart_config['viewType']		= "renderNationalChart";
		// echo "<pre>";
		// print_r($chart_config);
		$this->load->view('site_pages/chart_view_explore', $chart_config);

	}
	public function renderRegionChart($indicator_no,$chartType = 'line',$subLevel)
	{
		$indicator = $this->SiteModel->getRow('e_indicator',array('indicator_no'=>$indicator_no));
		if (!$indicator) {
			echo "No indicator found for ".$indicator_no;
			return false;
		}
		if($subLevel == ""){
			echo "Please select any province..";
			return false;
		}
		$chart_config = array();
		$chart_config['indicator_no'] 	= $indicator_no;
		$chart_config['indicator']		= $indicator;
		$chart_config['chartType']		= $chartType;
		$chart_config['subLevel']		= $subLevel;
		$chart_config['viewType']		= "renderRegionChart";
		// echo "<pre>";
		// print_r($chart_config);
		$this->load->view('site_pages/chart_view_explore', $chart_config);

	}
	###########################compariosn new version
	public function renderChart1()
	{

		// echo "<pre>";
		// print_r($_POST);
		$sLevel1 = $this->input->post('sLevel1');
		$sSub_level1 = $this->input->post('sSub_level1');
		$sYears1 = $this->input->post('sYears1');
		$sFilters1 = $this->input->post('sFilters1');
		$sIndicator1 = $this->input->post('sIndicator1');
		$chartType = $this->input->post('chartType');

		$chart_config = array();
		$chart_config['indicator_no'] 	= $sIndicator1;
		$chart_config['indicator']		= $this->SiteModel->getRow('e_indicator',array('indicator_no'=>$sIndicator1));
		$chart_config['chartType']		= $chartType;
		$chart_config['years']		= '';
		$chart_config['values']		= '';
		
		$chartDataConfig = [
			'indicator_no' => $sIndicator1,
			'level' => $sLevel1,
			'sub_level' => $sSub_level1,
			'years'	=> $sYears1,
			'filters' => $sFilters1,
		];
		

		if($sFilters1 == "") 
		{
			
			$chartDataConfig['dataType']   = "headline";
			$chart_config['settings']['on'] = 'true';
		}
		else
		{
			
			$chartDataConfig['dataType']   = "filters";
		}
		$data = $this->getChartData($chartDataConfig,$chartType);
		
		$chart_config['years'] = $data['years'];
		$chart_config['values'] = $data['values'];
		$chart_config['viewType']		= "renderChart1";
		if($data['years'] == ""){
			echo "<br><br><h6>No data available</h6>";
			return false;
		}
		$this->load->view('site_pages/chart_view_explore', $chart_config);
 		
	}

	protected function getChartData($config,$chartType = 'normal')
	{
		if($config['dataType'] == 'headline'){
			$indicator_no = $config['indicator_no'];
			$level = $config['level'];
			$sub_level = $config['sub_level'];
			$years = $config['years'];
			if($years == "")
			{
				$cond = [
					'indicator_no' => $indicator_no,
					'level' 		=> $level,
					'status' 		=> 'active'
				];
				if($level != "national")
					$cond['sub_level'] = $sub_level;
				$dataArr = $this->SiteModel->getOrderby('data','year','asc',$cond);
				// echo "<pre>";
				// print_r($dataArr);
				$yearsArr = $valuesArr = array();
				foreach ($dataArr as $key => $value) {
					$yearsArr[] = $value['year'];
					$valuesArr[] = $value['headline_value'];
				
				}
				$resArr['years'] = implode(',', $yearsArr);
				if($chartType == 'pie' || $chartType == 'pyramid')
					$resArr['values'] = $valuesArr;
				else
				$resArr['values'] = "{
                        name: 'Total value',
                        data: [".implode(',', $valuesArr)."]
                    }";
				return $resArr;

			}
			else
			{
				$cond1 = " ( 
					indicator_no = '$indicator_no' and 
					level = '$level' and status = 'active' ";
				if($level != "national")
					$cond1 = $cond1." and  sub_level = '$sub_level' ";
				$cond1 = $cond1." ) ";

				$cond2 = " AND (".$this->prepareOr($years,'year').")";
				$cond = $cond1.$cond2;
				 $q = "
					SELECT * FROM data
					WHERE $cond
					ORDER BY year asc
				";
				$cond = [
					'indicator_no' => $indicator_no,
					'level' 		=> $level
				];
				if($level != "national")
					$cond['sub_level'] = $sub_level;

				$dataArr = $this->SiteModel->myQuery($q);
				
				$yearsArr = $valuesArr = array();
				foreach ($dataArr as $key => $value) {
					$yearsArr[] = $value['year'];
					$valuesArr[] = $value['headline_value'];
				
				}
				$resArr['years'] = implode(',', $yearsArr);
				if($chartType == 'pie' || $chartType == 'pyramid')
					$resArr['values'] = $valuesArr;
				else
				$resArr['values'] = "{
                        name: 'Total value',
                        data: [".implode(',', $valuesArr)."]
                    }";
				return $resArr;
			}
		}
		else
		{
			$indicator_no = $config['indicator_no'];
			$level = $config['level'];
			$sub_level = $config['sub_level'];
			$years = $config['years'];
			$filters = $config['filters'];
			$subFiltersIds = getSubFilters($filters);
			
			if($years == "")
			{
				$cond = [
					'indicator_no' => $indicator_no,
					'level' 		=> $level,
					'status'		=> 'active'
				];
				 $conditionArr['data.indicator_no'] = $indicator_no;
      			 $conditionArr['data.level'] = $level;
      			 $conditionArr['data.status'] = 'active';

				if($level != "national"){
					$cond['sub_level'] = $sub_level;
					$conditionArr['data.sub_level'] =$sub_level;
				}
				$dataArr = $this->SiteModel->getOrderby('data','year','asc',$cond);
				
				$yearsArr = $valuesArr = array();
				foreach ($dataArr as $key => $value) {
					$yearsArr[] = $value['year'];	
				}
			
				$resArr['years'] = implode(',', $yearsArr);
				$resArr['values'] = retFilterValues($subFiltersIds,$conditionArr);
				return $resArr;

			}
			else
			{
				$cond1 = " data.indicator_no = '$indicator_no' and  data.level = '$level' and data.status = 'active'";
				
				$cond2 = $this->prepareOr($years,'year');
				
				$cond = [
					'indicator_no' => $indicator_no,
					'level' 		=> $level
				];
				

				if($level != "national"){
					$cond['sub_level'] = $sub_level;
					$cond1 = $cond1." and sub_level = '$sub_level' ";
					
				}

				$resArr['years'] = implode(',', $years);
				$resArr['values'] = retFilterValuesYearBased($subFiltersIds,$cond1,$cond2);
				return $resArr;
			}
		}

	}
	public function getIndFilters()
	{
		 $level = $this->input->post('sLevel1');
		 $indicator_no = $this->input->post('sIndicator1');
		 $indicator = $this->AdminModel->getRow('e_indicator',array('indicator_no'=>$indicator_no));
		 $fIds = $indicator['filter_'.$level];
		 echo ' <select  multiple id="filterIn1" onchange="filterChanged()"  placeholder="Select">';
		 foreach (explode(',', $fIds) as $id) {
		 	$filter = $this->AdminModel->getRow('e_filters_cat',array('filter_cat_id'=>$id));
		 	// if($filter)
		 	echo "<option value='$id'>".$filter['filter_cat_title']."</option>";
		 }
		 echo "</select>";
		 echo "<script> var filterIn1Selectize = $('#filterIn1').selectize({plugins: ['remove_button'] }); var filterIn1SelectizeC = filterIn1Selectize[0].selectize;</script>";
	}
	public function getIndFilters2()
	{
		 $level = $this->input->post('sLevel2');
		 $indicator_noS = $this->input->post('sIndicator2');
		 $goal_no = $this->input->post('sGoal2');

		 $fIdsArr = array();

		 if($indicator_noS == ""){
			$indicator_noS = $this->AdminModel->getRows('e_indicator',array('goal_no'=>$goal_no));
		 	foreach ($indicator_noS as $key => $ind) {
		 		$fIdsArr[] = $ind['filter_'.$level];
		 	}
		 }
		else
		{
			foreach ($indicator_noS as $indicatorNo) {
				$ind = $this->SiteModel->getRow('e_indicator',array('indicator_no'=>$indicatorNo));
		 		$fIdsArr[] = $ind['filter_'.$level];
			}
		}

		$fIdsArr = array_values($fIdsArr);
		$fIds = implode(',',array_unique(array_values(explode(',',implode(',', $fIdsArr)))));
		 echo ' <select  multiple id="filterIn2" onchange="filterChanged2()"  placeholder="Select">';
		 foreach (explode(',', $fIds) as $id) {
		 	if($id == "") continue;
		 	$filter = $this->AdminModel->getRow('e_filters_cat',array('filter_cat_id'=>$id));
		 	if($filter)
		 	echo "<option value='$id'>".$filter['filter_cat_title']."</option>";
		 }
		 echo "</select>";
		 echo "<script>var temp = $('#filterIn2').selectize({plugins: ['remove_button'] });filterIn2SelectizeC = temp[0].selectize;</script>";
	}
	public function getComparisonInd()
	{
		$goal_no = $this->input->post('sGoal2');
		echo "<select multiple id='indicatorIn2' onchange='indicatorIn2Change()' placeholder='Selected All'>";

			getOption('e_indicator','indicator_no','indicator_no','',array('goal_no'=>$goal_no));
		echo "</select>";
		echo "<script>var temp = $('#indicatorIn2').selectize({plugins: ['remove_button'] });indicatorIn2SelectizeC = temp[0].selectize;</script>";
	}

	public function renderComparisonChart()
	{
		
		$level = $this->input->post('sLevel1');
		$level2 = $this->input->post('sLevel2');
		$sub_level = $this->input->post('sSub_level1');
		$sub_level2 = $this->input->post('sSub_level2');
		$years = $this->input->post('sYears1');
		$years2 = $this->input->post('sYears2');

		$indicator_no = $this->input->post('sIndicator1');
		$chartType = $this->input->post('chartType');
		$sGoal2 = $this->input->post('sGoal2');
		$sIndicator2 = $this->input->post('sIndicator2');
		$indicatorRow = $this->SiteModel->getRow('e_indicator',['indicator_no'=>$indicator_no]);
		
		// Chart 1 Envirnmnt
			
			$sIndicator1[] = $indicator_no;

				$cond0 = "(".$this->prepareOr($sIndicator1,'indicator_no').")";

				$cond1 = " (
					level = '$level'";
				if($level != "national")
					$cond1 = $cond1." and  sub_level = '$sub_level' ";
				$cond1 = $cond1." ) ";
				
				$cond = $cond0." AND ".$cond1;
				if($years != "") 
					$cond = $cond." AND (".$this->prepareOr($years,'year').")";

				  $q = "
					SELECT * FROM data
					WHERE $cond and (data.status = 'active')
					ORDER BY year asc
				";

				
				$dataArr = $this->SiteModel->myQuery($q);
				// echo "<pre>";
				// print_r($dataArr);

				$yearsArr = $valuesArr = $indNo = $arrangedData = array();

				foreach ($dataArr as $key => $value) {
					$yearsArr[] = $value['year'];
					$indNo[] = $value['indicator_no'];
					$arrangedData[$value['indicator_no']][$value['year']] = $value['headline_value'];

				}

				$indNo = array_unique($indNo);
				

				foreach ($yearsArr as $y) {
						foreach ($arrangedData as $key => $value) {
							array_key_exists($y,$arrangedData[$key]) OR $arrangedData[$key][$y] = "0";
						}
				}
				$resArr['values'] = "";
				foreach ($arrangedData as $key => $value) {
					ksort($arrangedData[$key]);
					
					$resArr['values'] = $resArr['values']."{
											name:'$key %',
											data:[".implode(',', $arrangedData[$key])."]
										},";
				}

				$resArr['years'] = implode(',', array_unique($yearsArr));
				$chart_config['years'] = $resArr['years'];
				$chart_config['values'] = $resArr['values'];
				$chart_config['text'] = "Total values for ".ucwords($level)." Level ".$sub_level;
				$chart_config['chartType']		= $chartType;
				$chart_config['viewType']		= "dynamicChart";
				$chart_config['unit']		= $indicatorRow['unit'];

		// Chart 2 Envirnmnt

		if($sIndicator2 == "")
		{
			$sIndicator2 = array();
			$inds = $this->SiteModel->getRows('e_indicator',array('goal_no'=>$sGoal2));
			foreach ($inds as $key => $value) {
				$sIndicator2[] = $value['indicator_no'];
			}
		}

				$cond00 = "(".$this->prepareOr($sIndicator2,'indicator_no').")";

				$cond11 = " (
					level = '$level2'";
				if($level2 != "national")
					$cond11 = $cond11." and  sub_level = '$sub_level2' ";
				$cond11 = $cond11." ) ";
				
				$cond2 = $cond00." AND ".$cond11;
				if($years2 != "") 
					$cond2 = $cond2." AND (".$this->prepareOr($years2,'year').")";

				  $q2 = "
					SELECT * FROM data
					WHERE $cond2 and (data.status = 'active')
					ORDER BY year asc
				";

				
				$dataArr = $this->SiteModel->myQuery($q2);
				// echo "<pre>";
				// print_r($dataArr);

				$yearsArr = $valuesArr = $indNo = $arrangedData = array();

				foreach ($dataArr as $key => $value) {
					$yearsArr[] = $value['year'];
					$indNo[] = $value['indicator_no'];
					$arrangedData[$value['indicator_no']][$value['year']] = $value['headline_value'];

				}

				$indNo = array_unique($indNo);
				

				foreach ($yearsArr as $y) {
						foreach ($arrangedData as $key => $value) {
							array_key_exists($y,$arrangedData[$key]) OR $arrangedData[$key][$y] = "0";
						}
				}
				$resArr['values'] = "";
				$resArr['years'] = "";
				foreach ($arrangedData as $key => $value) {
					ksort($arrangedData[$key]);
					
					$resArr['values'] = $resArr['values']."{
											name:'$key %',
											data:[".implode(',', $arrangedData[$key])."]
										},";
				}

				$resArr['years'] = implode(',', array_unique($yearsArr));
				$chart_config2 = array();
				$chart_config2['years'] = $resArr['years'];
				$chart_config2['values'] = $resArr['values'];
				$chart_config2['text'] = "Total values for ".ucwords($level2)." Level ".$sub_level2;
				$chart_config2['chartType']		= $chartType;
				$chart_config2['viewType']		= "dynamicChart";
				$chart_config2['unit']		= "Values";
			

				?>
<div class="bg-success" style="min-height: 40px;margin: auto ">
  <div class="pull-left" style="padding: 6px;">
    <div class="row">
      <div class="col-md-2" style="padding-top: 4px"> <i title="download as pdf" class="fa fa-file-pdf-o fa-2x" onclick="callExport()"></i> </div>
      <div class="col-md-2" style="padding-top: 4px"> <i title="download as Image/PNG" class="fa fa-picture-o fa-2x" onclick="callExportAsImage()"></i> </div>
    </div>
  </div>
  <div class="pull-right chart-icon"> <img onclick="renderChart1('stacked')" src="<?=base_url()?>assets/site_assets/img/sc.png"> <img onclick="renderChart1('area')" src="<?=base_url()?>assets/site_assets/img/area2.png"> <i onclick="renderChart1('area2')"  class="fa fa-area-chart fa-2x"></i> <img  onclick="renderChart1('bar')"  src="<?=base_url()?>assets/site_assets/img/bar.png"> <i onclick="renderChart1('column')" class="fa fa-bar-chart fa-2x"></i> <i  onclick="renderChart1('line')" class="fa fa-line-chart fa-2x" ></i> </div>
</div>
<script type="text/javascript">

        	var chartsArr = [];
        	Highcharts.exportCharts = function ( options) {

			    // Merge the options
			    options = Highcharts.merge(Highcharts.getOptions().exporting, options);

			    // Post to export server
			    Highcharts.post(options.url, {
			        filename: options.filename || 'chart',
			        type: options.type,
			        width: options.width,
			        svg: Highcharts.getSVG(chartsArr)
			    });
			};
			Highcharts.getSVG = function (charts) {
		    var svgArr = [],
		        top = 0,
		        width = 0;

		    Highcharts.each(charts, function (chart) {
		        var svg = chart.getSVG(),
		            // Get width/height of SVG for export
		            svgWidth = +svg.match(
		                /^<svg[^>]*width\s*=\s*\"?(\d+)\"?[^>]*>/
		            )[1],
		            svgHeight = +svg.match(
		                /^<svg[^>]*height\s*=\s*\"?(\d+)\"?[^>]*>/
		            )[1];

		        svg = svg.replace(
		            '<svg',
		            '<g transform="translate(0,' + top + ')" '
		        );
		        svg = svg.replace('</svg>', '</g>');

		        top += svgHeight;
		        width = Math.max(width, svgWidth);

		        svgArr.push(svg);
		    });

		    return '<svg height="' + top + '" width="' + width +
		        '" version="1.1" xmlns="http://www.w3.org/2000/svg">' +
		        svgArr.join('') + '</svg>';
		};

			function callExport() {
				Highcharts.exportCharts({type: 'application/pdf' });
			}
			function callExportAsImage() {
				Highcharts.exportCharts();
			}
        </script>
<?php

				// echo $resArr['values'];
				echo "<div class='row'>";
					echo "<div class='col-md-6' id='chart1'>";
						$chart_config['settings']['chart_id'] = 'chart1';
						if ($chart_config['years'] == "") 
							echo "<br><br><h6>No data available</6>";
						else
							$this->load->view('site_pages/chart_view_explore', $chart_config);


					echo "</div>";
					echo "<div class='col-md-6' id='chart2'>";
						$chart_config2['settings']['chart_id'] = 'chart2';
						if ($chart_config2['years'] == "") 
							echo "<br><br><h6>No data available</6>";
						else
						$this->load->view('site_pages/chart_view_explore', $chart_config2);

					echo "</div>";
				echo "</div>";

				
	}
	public function renderMultipleGraph()
	{
		// echo "<pre>";
		// print_r($_POST);
		 $rowCol = $this->input->post('rowCol');

		$level = $this->input->post('sLevel1');
		$level2 = $this->input->post('sLevel2');
		$sub_level = $this->input->post('sSub_level1');
		$sub_level2 = $this->input->post('sSub_level2');
		$sGoal2 = $this->input->post('sGoal2');
		$sIndicator1 = $this->input->post('sIndicator1');
		$sIndicator2 = $this->input->post('sIndicator2');
		$sFilters1 = $this->input->post('sFilters1');
		$sFilters2 = $this->input->post('sFilters2');

		$chartType = $this->input->post('chartType');
		$years = $this->input->post('sYears1');
		$years2 = $this->input->post('sYears2');

		if ($sIndicator2 == ""){
			$sIndicator2 = array();
			foreach ($this->SiteModel->getRows('e_indicator',array('goal_no'=>$sGoal2)) as $key => $value) {
				$sIndicator2[] = $value['indicator_no'];
			}
		}

		$graphArr = array();
		$config = [
					'level' 		=> $level,
					'sub_level' 	=> $sub_level,
					'indicator_no' 	=> $sIndicator1,
					'years'			=> $years,
					'filters'		=> $sFilters1
		];
		$graphArr[] = $this->multipleGraphHelperFilterized($config);

		$config['filters'] = $sFilters2;
		$config['years'] = $years2;
		$config['level'] = $level2;
		$config['sub_level'] = $sub_level2;
		foreach ($sIndicator2 as $key => $value) {
			// if($value == "" || $value == $sIndicator1) continue;
			if($value == "") continue;
			$config['indicator_no'] =  $value;
			$graphArr[] = $this->multipleGraphHelperFilterized($config);
		}

		// echo "<pre>";
		// print_r($graphArr);
		echo "<div class='row'>";
		?>
<div class="bg-success" style="min-height: 40px;margin: auto 19px">
<div  >
  <div class="row pull-left" style="width: 500px">
    <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 4px"><small><strong>Graph in One Row</strong> </small></div>
    <div class="col-md-1 col-sm-1 col-xs-1" style="padding: 1px;margin: 1px">
      <select style="margin: 1px;padding: 1px" class="form-control input-sm" id="rowColIn" onchange="updateRowCol(this)">
        <option <?php if($rowCol == '12') echo 'selected' ?> value="12">1</option>
        <option <?php if($rowCol == '6') echo 'selected' ?> value="6">2</option>
        <option <?php if($rowCol == '4') echo 'selected' ?> value="4">3</option>
        <option <?php if($rowCol == '3') echo 'selected' ?> value="3">4</option>
        <option <?php if($rowCol == '2') echo 'selected' ?> value="2">6</option>
      </select>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-3" style="padding-top: 8px"> <i title="download as pdf" class="fa fa-file-pdf-o fa-2x" onclick="callExport()"></i> &nbsp;&nbsp;<i title="download as Image/PNG" class="fa fa-picture-o fa-2x" onclick="callExportAsImage()"></i></div>
  </div>
  <div class="pull-right chart-icon"> <img onclick="renderChart1('stacked')" title="Not ready Scattered Chart" src="<?=base_url()?>assets/site_assets/img/sc.png">
    <!--   <img  onclick="renderChart1('area')"  src="<?=base_url()?>assets/site_assets/img/area2.png">
            <i onclick="renderChart1('area2')"  class="fa fa-area-chart fa-2x"></i> -->
    <img  onclick="renderChart1('bar')"  src="<?=base_url()?>assets/site_assets/img/bar.png"> <i <?php if($chartType == 'column') echo 'style="color:blue"' ?>  onclick="renderChart1('column')" class="fa fa-bar-chart fa-2x"></i> <i <?php if($chartType == 'line') echo 'style="color:blue"' ?> onclick="renderChart1('line')" class="fa fa-line-chart fa-2x" ></i> </div>
</div>
<script type="text/javascript">

        	var chartsArr = [];
        	Highcharts.exportCharts = function ( options) {

			    // Merge the options
			    options = Highcharts.merge(Highcharts.getOptions().exporting, options);

			    // Post to export server
			    Highcharts.post(options.url, {
			        filename: options.filename || 'chart',
			        type: options.type,
			        width: options.width,
			        svg: Highcharts.getSVG(chartsArr)
			    });
			};
			Highcharts.getSVG = function (charts) {
		    var svgArr = [],
		        top = 0,
		        width = 0;

		    Highcharts.each(charts, function (chart) {
		        var svg = chart.getSVG(),
		            // Get width/height of SVG for export
		            svgWidth = +svg.match(
		                /^<svg[^>]*width\s*=\s*\"?(\d+)\"?[^>]*>/
		            )[1],
		            svgHeight = +svg.match(
		                /^<svg[^>]*height\s*=\s*\"?(\d+)\"?[^>]*>/
		            )[1];

		        svg = svg.replace(
		            '<svg',
		            '<g transform="translate(0,' + top + ')" '
		        );
		        svg = svg.replace('</svg>', '</g>');

		        top += svgHeight;
		        width = Math.max(width, svgWidth);

		        svgArr.push(svg);
		    });

		    return '<svg height="' + top + '" width="' + width +
		        '" version="1.1" xmlns="http://www.w3.org/2000/svg">' +
		        svgArr.join('') + '</svg>';
		};

			function callExport() {
				Highcharts.exportCharts({type: 'application/pdf' });
			}
			function callExportAsImage() {
				Highcharts.exportCharts();
			}
        </script>

<?php

		foreach ($graphArr as $key => $value) {

			if(!$value) continue;
			 $chart_config['years'] = $value['years'];
			$chart_config['text'] = $value['text'];
			 $chart_config['values'] = $value['values'];
			$chart_config['chartType']		= $chartType;
			$chart_config['viewType']		= "dynamicChartV2";
			$chart_config['chartId'] = 'chart'.$key;
			$chart_config['rowCol'] = $rowCol;
			$chart_config['unit'] = $value['unit'];
			
			$this->load->view('site_pages/chart_view_explore', $chart_config);
			
		}
		echo "<div style='clear:both'></div>";
		
	}
	protected function multipleGraphHelperFilterized($config)
	{
		$indicator_no = $config['indicator_no'];
		$level = $config['level'];
		$sub_level = $config['sub_level'];

		$indicator = $this->SiteModel->getRow('e_indicator',array('indicator_no'=>$indicator_no));
		$org_filters =  $indicator['filter_'.strtolower($level)];
		$org_filters =  explode(',', $org_filters);
		
		$years = $config['years'];
		$filters = $config['filters'];
		if($filters == "")
		{
			$filters =  $org_filters;
		}
		else
		{
			foreach ($filters as $key => $value)
			{
				if(!in_array($value, $org_filters))
					unset($filters[$value]);
			}
		}

		 $subFiltersIds = getSubFilters($filters);
			
		if($years == "")
		{
				$cond = [
					'indicator_no' => $indicator_no,
					'level' 		=> $level,
					'status'		=> 'active'
				];
				 $conditionArr['data.indicator_no'] = $indicator_no;
      			 $conditionArr['data.level'] = $level;
      			 $conditionArr['data.status'] ='active';

				if($level != "national"){
					$cond['sub_level'] = $sub_level;
					$conditionArr['data.sub_level'] =$sub_level;
				}
				$dataArr = $this->SiteModel->getOrderby('data','year','asc',$cond);
				
				$yearsArr = $valuesArr = array();
				
				foreach ($dataArr as $key => $value) {
					$yearsArr[] = $value['year'];	
				}

				if(!$yearsArr) return array();
				$resArr['unit'] = $indicator['unit'];
				$resArr['text']	= "<b>Indicator: ".$indicator_no.'</b>:'.getField('e_indicator',array('indicator_no'=>$indicator_no),'indicator');
				$resArr['years'] = implode(',', $yearsArr);
				$resArr['values'] = retFilterValuesV2($subFiltersIds,$conditionArr,$yearsArr);
				if($resArr['values'])
				return $resArr;
				else
				return array();
				

		}
		else
		{
			$cond1 = " data.indicator_no = '$indicator_no' and  data.level = '$level' and data.status = 'active'";
				
				$cond2 = $this->prepareOr($years,'year');
				
				$cond = [
					'indicator_no' => $indicator_no,
					'level' 		=> $level
				];
				

				if($level != "national"){
					$cond['sub_level'] = $sub_level;
					$cond1 = $cond1." and sub_level = '$sub_level' ";
				}
				sort($years);
				$resArr['unit'] = $indicator['unit'];
				$resArr['text'] = "indicator No.".$indicator_no;
				$resArr['years'] = implode(',', $years);
				$resArr['values'] = retFilterValuesYearBasedV2($subFiltersIds,$cond1,$cond2,$years);
				if($resArr['values'])
				return $resArr;
				else
				return array();
		}
		
	}
	############################renderSummary 
	public function renderSummary()
	{
		$sGoals = $this->input->post('sGoals');
		// $sFilters = $this->input->post('sFilters');
		 $sIndicators = $this->input->post('sIndicators');
		 
		 $cond = array('level'=>'national','status'=>'active');
		 $orderArr = array('year','asc');

		 $whereIn = array();
		 if($sIndicators != ""){
		 	$whereIn['indicator_no'] = $sIndicators;
		 	$filters_cat_ids = get_filter_cat_ids($sIndicators);
		 	$indicatorData = $this->SiteModel->getWhereIn('e_indicator',array('indicator_no',$sIndicators));
		 }
		 elseif ($sGoals != ''){
			 $whereIn['goal_no'] =  $sGoals;
			 $indicatorData = $allInd = $this->SiteModel->getWhereIn('e_indicator',array('goal_no',$whereIn['goal_no']));
			 foreach ($allInd as $key => $value) {
			 	$allInd2[] = $value['indicator_no'];
			 }
		 	 $filters_cat_ids = get_filter_cat_ids($allInd2);
		 }
		 else{
		 	$whereIn['goal_no'] = '1';
			 $indicatorData = $allInd = $this->SiteModel->getWhereIn('e_indicator',array('goal_no',$whereIn['goal_no']));
			 foreach ($allInd as $key => $value) {
			 	$allInd2[] = $value['indicator_no'];
			 }
		 	$filters_cat_ids = get_filter_cat_ids($allInd2);
		 }
		 	



	 	$filters = array();

	 		foreach ($filters_cat_ids as $f_cat_id)
	 		{
	 			$filtersArr = $this->SiteModel->getRows('e_filters',array('filter_cat_id'=>$f_cat_id));
	 			foreach ($filtersArr as $filterRow) {
	 				$filters[] = $filterRow;
	 			}
	 		}
	 
	 	$targetData = $this->SiteModel->getWhereIn('e_targets',array('goal_no',$sGoals));
	 	foreach ($targetData as $key => $value) {
	 		$targetData2[$value['target_no']]= $value;
	 	}
	 	$goalsData = $this->SiteModel->getWhereIn('e_goals',array('goal_no',$sGoals));
	 	foreach ($goalsData as $key => $value) {
	 		$goalsData2[$value['goal_no']] = $value;
	 	}
	 	foreach ($indicatorData as $key => $value) {
	 		$indicatorData2[$value['indicator_no']] = $value;
	 	}
		$mainData = $this->SiteModel->getWhereInMultiple('data',$whereIn,$cond,$orderArr,TRUE);
		$data = array(
			'viewType' => 'renderSummary',
			'mainData' => $mainData,
			'filters' =>$filters,
			'indicators' => $indicatorData2,
			'goals' 	=> $goalsData2,
			'targets'  => $targetData2

		);
		// echo "<pre>";
		// print_r($data);

		$this->load->view('site_pages/other_views', $data);
	}
	############################end renderSummary

	############################renderDATA 
	public function renderData()
	{
		$goal_no = $this->input->post('goal_no');
		 $sGoals = $this->input->post('sGoals');
		$sFilters = $this->input->post('sFilters');
		$sIndicators = $this->input->post('sIndicators');
		$sLevel = $this->input->post('sLevel');
		$sDistricts = $this->input->post('sDistricts');
		$sRegions = $this->input->post('sRegions');
		$sYears = $this->input->post('sYears');

		$filters_cat_ids = $this->input->post('filters_cat_ids');
		$filters_cat_ids = array_values(array_unique(explode(',', $filters_cat_ids)));
		if($sIndicators == ""){
			$cond = " (".$this->prepareOr($sGoals,'goal_no').") ";
			$indicatorData = $this->SiteModel->getWhereIn('e_indicator',array('goal_no',$sGoals));
		}
		else{
			$cond = " data_id != '' ";
			$sIndArr = explode(',', $sIndicators);
			$indicatorData = $this->SiteModel->getWhereIn('e_indicator',array('indicator_no',$sIndArr));
		}
		// echo "<pre>";
		// var_dump($sYears);
		// echo "</pre>";
		//handling level
		if($sLevel != "")
		{
			foreach ($sLevel as $level)
			{
				$levelArr[] = " level = '$level' ";
			}
			$cond = $cond.' AND ( '.implode(' || ', $levelArr).' ) ';

			$regionCond = $districtCond = "";
			if(in_array('region', $sLevel)){
				//then handle the sRegions
				if($sRegions != ""){
					$sLevel2 = $sLevel;
					unset($sLevel2[array_search('region', $sLevel2)]);
					if($sLevel2)
					$regionCond = $this->prepareOr($sRegions,'sub_level').' || '.$this->prepareOr($sLevel2,'level');
					else
					$regionCond  = $this->prepareOr($sRegions,'sub_level');
				}
			}
			if (in_array('district', $sLevel)) {
				if($sDistricts != ""){
					unset($sLevel[array_search('district', $sLevel)]);
					if($sLevel)
						$districtCond = $this->prepareOr($sDistricts,'sub_level').' || '.$this->prepareOr($sLevel,'level');
					else
						$districtCond = $this->prepareOr($sDistricts,'sub_level');
				}
			}
			if($regionCond != "" && $districtCond != "")
				$cond = $cond." AND ( $regionCond  ) AND ( $districtCond )";
			elseif ($regionCond != "" && $districtCond == "")
				$cond = $cond." AND ( $regionCond  )";
			elseif($regionCond == "" && $districtCond != "")
				$cond = $cond." AND ( $districtCond  )";	
		}
		//end of sLevel

		if($sIndicators != "")
		{
		
			foreach (explode(',', $sIndicators) as $ind)
			{
				$indi[] = " indicator_no = '$ind' ";
			}
			$cond = $cond." AND ( ".implode('||', $indi)." ) ";
			$filters_cat_ids = get_filter_cat_ids(explode(',', $sIndicators));
		}

		if($sYears != ""){
			$cond = $cond." AND ( ".$this->prepareOr($sYears,'year')." ) ";
		}
			    $query = "
					SELECT * FROM data
					WHERE
					 $cond and (data.status = 'active')
					ORDER BY year asc
	 		";

	 	$filters = array();
	 	if ($sFilters == "")
	 	{
	 		foreach ($filters_cat_ids as $f_cat_id)
	 		{
	 			$filtersArr = $this->SiteModel->getRows('e_filters',array('filter_cat_id'=>$f_cat_id));
	 			foreach ($filtersArr as $filterRow) {
	 				$filters[] = $filterRow;
	 			}
	 		}
	 	}
	 	else
	 	{
	 		foreach (explode(',', $sFilters) as $f_cat_id)
	 		{
	 			$filtersArr = $this->SiteModel->getRows('e_filters',array('filter_cat_id'=>$f_cat_id));
	 			foreach ($filtersArr as $filterRow) {
	 			
	 				$filters[] = $filterRow;
	 			}
	 		}

	 	}
	 	
	 	$targetData = $this->SiteModel->getWhereIn('e_targets',array('goal_no',$sGoals));
	 	foreach ($targetData as $key => $value) {
	 		$targetData2[$value['target_no']]= $value;
	 	}
	 	$goalsData = $this->SiteModel->getWhereIn('e_goals',array('goal_no',$sGoals));
	 	foreach ($goalsData as $key => $value) {
	 		$goalsData2[$value['goal_no']] = $value;
	 	}
	 	
	 	foreach ($indicatorData as $key => $value) {
	 		$indicatorData2[$value['indicator_no']] = $value;
	 	}


		$mainData = $this->SiteModel->myQuery($query,TRUE);
		$data = array(
			'viewType' => 'rednerData',
			'goal_no'=>$goal_no,
			'mainData' => $mainData,
			'filters' =>$filters,
			'goals'	=> $goalsData2,
			'targets' => $targetData2,
			'indicators' => $indicatorData2
		);
		// echo "<pre>";
		// print_r($data);

		$this->load->view('site_pages/other_views', $data);
	}
	########################### end RenderData
	protected function prepareOr($arr,$index)
	{
		$newArr = array();
		foreach ($arr as $item) {
			$newArr[] = " $index = '$item' ";
		}
		return implode(' || ', $newArr);
	}
	######################analysis search function
	public function getLevel()
	{
		$indicator_no = $this->input->post('indicator_no');
		$indicator = $this->SiteModel->getRow('e_indicator',array('indicator_no'=>$indicator_no));
		if($indicator['level'] != ""){
			$levels = explode(',', $indicator['level']);
			echo "
<option value='' disabled selected>Select Level</option>
";
			foreach ($levels as $lvl) {
				echo "
<option value='$lvl'>$lvl</option>
";
			}
		}else{
			echo "
<option disabled >No Level Assigned</option>
";
		}

	}
	public function getSubLevel()
	{
		$level = $this->input->post('level');
		echo '<select onchange="subLevelSelection(this)" id="subLevelOption" multiple  placeholder="Select Sub Level...">';
		
		if($level == "Region")
			getOption('e_province','name','name');
		elseif($level == "District")
			getOption('e_cities','city_name','city_name');
			
        echo '</select>';  
        echo "<script>$('#subLevelOption').selectize({plugins: ['remove_button'] });</script>";
	}
	
	

	################generic save and update
	public function saveSimple()
	{
		if (isset($_FILES) && !empty($_FILES['files']['name']))
		{
			$photo =  $this->processImg($_FILES);
			$this->create_thumbnail($photo);
		}
		else
			$photo = "";
		$data = $_POST;
		$tableName = $data['tableName'];
		unset($data['tableName']);
		$data = $data + array('image' => $photo);
		//   echo "<pre>";
		//  print_r($data);
		// echo $tableName;

		if ($this->AdminModel->insertData($tableName,$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed"
				));
	}
	public function updateSimple()
	{
		$index 		= $_POST['keyIndex'];
		$tableName 	= $_POST['tableName'];
		$id = $this->input->post($index);
		$data = $_POST;
		unset($data['keyIndex']);
		unset($data['tableName']);

		if (isset($_FILES) && !empty($_FILES['files']['name']))
		{
			$this->deleteImage($tableName,$index,$id,'image','assets/cms_images/');
			$photo =  $this->processImg($_FILES);
			$this->create_thumbnail($photo);
			$data = $data + array('image'=>$photo);
		}

		// echo "<pre>";
		// print_r($data);
		
		if ($this->AdminModel->updateRow($tableName,$index,$id,$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed(@query updateSimple)"
				));

	}

	////////////////////saving career application
	public function saveApplication()
	{
		$data = $_POST;
		if (strcasecmp($_SESSION['captchaWord'], $data['captcha']) == 0) 
		{
			unset($_SESSION['captchaWord']);
			unset($data['captcha']);

			if (isset($_FILES) && !empty($_FILES['files']['name']))
			{
				 $file =  $this->processFile($_FILES);
			}
			else
				$file = "";
			

			$data = $data + array('cv' => $file);
			  //  echo "<pre>";
			  // print_r($data);

			if ($this->AdminModel->insertData('e_applications',$data))
			{

				echo "
<div>Application Saved successfully<br>
  Redircting...</div>
";
			}
			else
				
			echo "
<div>Your Application Not sent <br>
  Redircting...</div>
";
		}
		else
			echo "
<div> Invalid Captcha Value<br>
  Redircting...</div>
";
	}

	//////////////////////saving contact us site page
	public function saveContactUs()
	{

		$data = array();
		$data['name'] = $this->input->post('name');
		$data['email'] = $this->input->post('email');
		$data['ministry_id'] = $this->input->post('ministry_id');
		$data['message'] = $this->input->post('message');
		
			//   echo "<pre>";
			//  print_r($data);
			// echo $tableName;

			if ($this->AdminModel->insertData('e_contact_us',$data))
			{
				$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Your Message sent successfully. Thankyou for feedback"
				));
			}
			else
				$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Your Message was not sent."
				));

	}
	//////////////// GENERIC Function for delete row//////////////
	public function deleteImage($tableName,$key,$value,$fieldName,$filePath)
	{
		 $filename = $this->AdminModel->getField($tableName,$key,$value,$fieldName);
		if (!empty($filename) && $filename != '0') 
		{
			$image = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.$filePath.$filename;
			$image_thumbnail = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.$filePath.'thumbnail/'.$filename;
			if (file_exists($image)) unlink($image);
			if (file_exists($image_thumbnail)) unlink($image_thumbnail);
		}
	}
	public function deleteMultipleImage($tableName,$key,$value,$fieldName,$filePath)
	{
		 $filename = $this->AdminModel->getField($tableName,$key,$value,$fieldName);
		if (!empty($filename) && $filename != '0') 
		{
			$images = explode(';', $filename);
			foreach ($images as $file)
			{
				$image = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.$filePath.$file;
				//$image_thumbnail = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.$filePath.'thumbnail/'.$file;
				if (file_exists($image)) unlink($image);
				//if (file_exists($image_thumbnail)) unlink($image_thumbnail);
			}
			
		}
	}
	 public function processImg($files,$filename = 'files')
	{
		$config['upload_path']          = "assets/cms_images/";
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 3000;
       // $config['max_width']            = 3000 ;
        //$config['max_height']           = 2000;
        $new_name = time().mt_rand(1,999).trim($_FILES[$filename]['name']);
		$config['file_name'] = $new_name;
        $photos = "";
        $dataInfo = array();
      	
      	$this->load->library('upload', $config);


        if ( ! $this->upload->do_upload($filename))
        {
            $error = array('error' => $this->upload->display_errors());
            //print_r($error);
            //exit();
            //return false;
        }
        else
        {
             $data = array('upload_data' => $this->upload->data());
             return $data['upload_data']['file_name'];
        }
	}

	public function processMultipleIamges($files,$filename = 'files',$thumbnail = FALSE)
	{
		$config['upload_path']          = "assets/cms_images/";
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 3000;
       // $config['max_width']            = 3000 ;
        //$config['max_height']           = 2000;
        
        $photos = array();
        $dataInfo = array();
        // echo "<pre>";
        // print_r($_FILES);
       
        
        $cpt = count($_FILES[$filename]['name']);
        for($i=0; $i<$cpt; $i++)
        {   
   			$new_name = time().rand(9,99999);
			$config['file_name'] = $new_name;       
            $_FILES[$filename]['name']= $files[$filename]['name'][$i];
            $_FILES[$filename]['type']= $files[$filename]['type'][$i];
            $_FILES[$filename]['tmp_name']= $files[$filename]['tmp_name'][$i];
            $_FILES[$filename]['error']= $files[$filename]['error'][$i];
            $_FILES[$filename]['size']= $files[$filename]['size'][$i];  

            $this->upload->initialize($config);
            // $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload($filename))
            {
                $error = array('error' => $this->upload->display_errors());
                // print_r($error);
            }
            else
            {  
                $dataInfo = array('upload_data' => $this->upload->data());
                $photos[] = $dataInfo['upload_data']['file_name'];
               
            }

            
        }
        if($thumbnail) $this->create_thumbnail($photos,TRUE);
        return implode(';',$photos );
	}
	
	 public function create_thumbnail($filename,$multi_images = FALSE)
   	{
   		
   		$target_path = $_SERVER['DOCUMENT_ROOT'] . '/'.CMS_PATH.'assets/cms_images/thumbnail';
      	$config = array(
          'image_library' => 'gd2',
          'source_image'  => '',
          'new_image' => $target_path,
          'maintain_ratio' => TRUE,
          'create_thumb' => TRUE,
          'thumb_marker' => '',
          'width' => 150,
          'height' => 150
      );
      if ($multi_images)
      {
      	foreach ($filename as $file)
      	{
      		 $source_path = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.'assets/cms_images/'.$file;
      		$config['source_image'] = $source_path; 
      		 $this->image_lib->initialize($config);
		      if (!$this->image_lib->resize())
		          echo $this->image_lib->display_errors();
      	}
      }
      else
      {
 		$source_path = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.'assets/cms_images/'.$filename;
      	$config['source_image'] = $source_path;
      	$this->image_lib->initialize($config);
		if (!$this->image_lib->resize())
		     echo $this->image_lib->display_errors();
		      
      }
      $this->image_lib->clear();
   	}

   	 public function processFile($files,$filename = 'files')
	{
		$config['upload_path']          = "assets/lib/uploads";
        $config['allowed_types']        = 'docx|doc|pdf';
        $config['max_size']             = 4000;
       // $config['max_width']            = 3000 ;
        //$config['max_height']           = 2000;
        $new_name = time().mt_rand(1,999000); 
		$config['file_name'] = $new_name;
        $photos = "";
        $dataInfo = array();
      	
      	$this->load->library('upload', $config);
        if ( ! $this->upload->do_upload($filename))
        {
            $error = array('error' => $this->upload->display_errors());
             //print_r($error);
             //exit();
            //return false;
        }
        else
        {
             $data = array('upload_data' => $this->upload->data());
             return $data['upload_data']['file_name'];
        }
	}

	#___________________________________________ Ajax
	public function mapAjax()
	{ 
	  $qString = explode('##',$this->input->post('filterz'));
	  $province_id  = $qString[0];
	  $city_id  	= $qString[1];
	  $cropp_id  	= $qString[2];
	  $datec  	='';
	  if($qString[3]!='')
	  $datec  	= $qString[3];//date("Y-m-d", DateTime::createFromFormat('d/m/Y', $qString[3])->getTimestamp() );
	  
	  if($cropp_id==0) $cropp_id=4; // default flour
	  $qry = ' select * from e_foodinput where crop_id = '.$cropp_id;
	  if($province_id>0) 
	  		$qry .= ' and province_id = '.$province_id;
	  if($city_id>0) 
	  		$qry .= ' and city_id = '.$city_id;
	  if($datec!='') 
					$qry .= ' and date_added like "'.$datec.'%"';
					
$res = $this->AdminModel->myQuery($qry)->result_array();
	  $i=0; $cnt=0; $dt= ''; $resultArray = array();
		if($res){
			foreach ($res as $row) {
					$range_start = $row['stockneeded'] + ceil(0.05*$row['stockneeded']); # 5% +
					$range_end   = $row['stockneeded'] - ceil(0.05*$row['stockneeded']); # 5% -
					
					if(($range_end <= $row['mktstock']) && ($row['mktstock'] <= $range_start)){
						//$cls = 'style="padding:8px; width:100%" class="blink_yellow" ';
						//$dls = 'class="blink_yellow1" ';
						$cnt=400;
					}
					elseif($row['mktstock']<$range_end){
						//$cls = 'style="padding:8px; width:100%" class="blink_red" '; 
						//$dls = 'class="blink_red1" '; 
						$cnt=200;
					}
					else { //elseif($row['mktstock']>$range_start)
						//$cls = 'style="padding:8px; width:100%" class="blink_green" ';
						//$dls = 'class="blink_green1" ';
						$cnt=600;
					}
				
				
				
				
				/*
					if($row['mktstock'] < $row['stockneeded'])$cnt=200; //red
									else if($row['mktstock']==$row['stockneeded'])$cnt=400; //rellow
									else $cnt=600; //green*/
					$ky = getField('e_cities',$cond = array('city_id'=>$row['city_id']),'citykey');
					$resultArray[$i]=array($ky,($cnt+$row['city_id']));
					//$dt .= "['".$ky."',".($cnt+$row['city_id'])."],";
					$i++;
			}		
		}	
		echo json_encode($resultArray);
	}
	
	public function getDataFile()
	{	
		$province_id =$this->input->post('province');
		$city_id =$this->input->post('district');
		$crop_id =$this->input->post('cropp');
		$dt = $this->input->post('datec');
		$datec  	='';
		  if($dt!='')
		  $datec  	= date("Y-m-d", DateTime::createFromFormat('d/m/Y', $dt)->getTimestamp() );
		  
		$qry = "SELECT * FROM e_dataupload WHERE crop_id = ".$crop_id.' and province_id = '.$province_id.' and city_id = '.$city_id.' and date_added = "'.$datec.'%"';
		$x='';
		$row = $this->AdminModel->myQuery($qry)->result_array();
		if($row){
		foreach($row as $r)
			$x=$r['filename'];
		}
		echo json_encode($x);
		}
		
	#___________________________________________ Main Tootip
	public function getToolTip()
	{
		$dname =$this->input->post('dname');
		$crop_id =$this->input->post('crop_id');
		$dt = $this->input->post('datec');
		$datec  	='';
		  if($dt!='')
		  $datec  	= date("Y-m-d", DateTime::createFromFormat('d/m/Y', $dt)->getTimestamp() );
		$qry = "SELECT * FROM e_foodinput WHERE city_id in (select city_id from e_cities where city_name like '".$dname."' ) and crop_id = ".$crop_id;
		
		if($datec!='')
			$qry .=' and date_added like "'.$datec.'%"';
		else if($datec=='')
			$qry .=' and fid IN ( SELECT MAX(fid) FROM e_foodinput GROUP BY crop_id,city_id) ';
	  
		
		$row = $this->AdminModel->myQuery($qry)->result_array();
		foreach ($row as $r){
			$row['city'] = getField('e_cities',$cond = array('city_id'=>$r['city_id']),'city_name');
			$row['crop'] = getField('e_crops',$cond = array('crop_id'=>$r['crop_id']),'crop_name');
			$row['unit'] = getField('e_units',$cond = array('unit_id'=>$r['unit_id']),'unit');
			$row['mktstock'] = $r['mktstock'];
			$row['stockneeded'] = $r['stockneeded'];
			$row['price'] = $r['price'];
			$row['pricevariation'] = $r['pricevariation'];
			$row['date_added'] = date("d-M-Y",strtotime($r['date_added']));
		}
		$v ='<tr>
			  <th colspan="2">'.$row['crop'].' | '.$row['city'].' | '.$row['date_added'].'</th>
			</tr>
			<tr>
			  <td>Stock Poistion: </td>
			  <td>'.$row['mktstock'].' '.$row['unit'].'</td>
			</tr>
			<tr>
			  <td>Demand Estimate:</td>
			  <td>'.$row['stockneeded'].' '.$row['unit'].'</td>
			</tr>
			<tr>
			  <td>Price:</td>
			  <td>Rs. '.$row['price'].'</td>
			</tr>
			<tr>
			  <td>Price Hike: </td>
			  <td>'.$row['pricevariation'].'%</td>
			</tr>';
			
		echo json_encode($v);
		}
		
		public function getDistrictTblData()
	{	$tbldata=''; $data[]='';
		$data['lprice']=''; $data['bardemand']=0; $data['barstock']=0;
		$provinceid =$this->input->post('province');
		
		$dname =$this->input->post('dname');
		
		if (is_numeric($dname) && $dname>0)
		$dname = getField('e_cities',$cond = array('city_id'=>$dname),'city_name');
		else if (is_numeric($dname) && $dname==0)
		$dname='';
		
		$crop_id=$this->input->post('crop_id');
		$datec  	='';
	  	if($this->input->post('datec')!='')
	  	$datec  	= date("Y-m-d", DateTime::createFromFormat('d/m/Y', $this->input->post('datec'))->getTimestamp() );
	  
		$qry = "SELECT * FROM e_foodinput WHERE 1 ";
		if($dname!='')
			$qry .= ' and city_id in (select city_id from e_cities where city_name like "'.$dname.'" ) ';
		if($crop_id<=3)
			$qry .=' and crop_id = 4 ';
		else
			$qry .=' and crop_id = '.$crop_id;
		if($provinceid>0)
			$qry .=' and province_id = '.$provinceid;			
		if($datec!='')
			$qry .=' and date_added like "'.$datec.'%"';
		else if($datec=='')
			$qry .=' and fid IN ( SELECT MAX(fid) FROM e_foodinput GROUP BY crop_id,city_id) ';


	$resd = $this->SiteModel->myQueryz($qry);
	//bydefault charts title
	$data['bartitle']="<span id='bchead' style='font-size:12; font-weight:bold'>Country wide Stock/Demand Position of Flour</span>";
	$data['linetitle']="<span id='lchead' style='font-size:12; font-weight:bold'>Country wide Fornightly Price Trend of Flour</span>";			
			
	foreach ($resd as $row) {
		/*if($row['mktstock']< $row['stockneeded']){ $cls = 'style="padding:8px; width:100%" class="blink_red" '; $dls = 'class="blink_red1" '; }
		else if($row['mktstock']==$row['stockneeded']){
			$cls = 'style="padding:8px; width:100%" class="blink_yellow" ';
			$dls = 'class="blink_yellow1" ';
		}
		else {
			$cls = 'style="padding:8px; width:100%" class="blink_green" ';
			$dls = 'class="blink_green1" ';
		}*/
		$range_start = $row['stockneeded'] + ceil(0.05*$row['stockneeded']); # 5% +
	    $range_end   = $row['stockneeded'] - ceil(0.05*$row['stockneeded']); # 5% -
	    
	    if(($range_end <= $row['mktstock']) && ($row['mktstock'] <= $range_start)){
	        $cls = 'style="padding:8px; width:100%" class="blink_yellow" ';
			$dls = 'class="blink_yellow1" ';
	    }
	    elseif($row['mktstock']<$range_end){
	        $cls = 'style="padding:8px; width:100%" class="blink_red" '; 
	        $dls = 'class="blink_red1" '; 
	    }
		else { //elseif($row['mktstock']>$range_start)
			$cls = 'style="padding:8px; width:100%" class="blink_green" ';
			$dls = 'class="blink_green1" ';
		}
			
$tbldata.= getField('e_province',$cond = array('province_id'=>$row['province_id']),'name').'#'.getField('e_cities',$cond = array('city_id'=>$row['city_id']),'city_name').'#'.date("d-M-Y",strtotime($row['date_added'])).'#'.getField('e_crops',$cond = array('crop_id'=>$row['crop_id']),'crop_name').'#'.getField('e_units',$cond = array('unit_id'=>$row['unit_id']),'unit').'#<div '.$cls.' >'.$row['mktstock'].'</div>#'.$row['stockneeded'].'#'.$row['price'].'#'.$row['pricevariation'].'#';

			//$data['bardemand']=$row['stockneeded'];//rand(10,100);
			//$data['barstock']=$row['mktstock'];//rand(10,100);
			
			$data['tbdata']=rtrim($tbldata,"#");

			$data['bardemand']+=$row['stockneeded'];
			$data['barstock']+=$row['mktstock'];
			
			$data['lseries']=getField('e_crops',$cond = array('crop_id'=>$row['crop_id']),'crop_name');
			$data['barvalues']=getField('e_units',$cond = array('unit_id'=>$row['unit_id']),'unit');
			
			if($dname!=''){			
			$data['bartitle']='<span id="bchead" style="font-size:12; font-weight:bold">Stock/Demand Position of '.getField('e_crops',$cond = array('crop_id'=>$row['crop_id']),'crop_name').' in '.getField('e_cities',$cond = array('city_id'=>$row['city_id']),'city_name').'</span>';
			$data['linetitle']='<span id="bchead" style="font-size:12; font-weight:bold">Fornightly Price Trend of  '.getField('e_crops',$cond = array('crop_id'=>$row['crop_id']),'crop_name').' in '.getField('e_cities',$cond = array('city_id'=>$row['city_id']),'city_name').'</span>';
			}
			else if($dname==''){			
			$data['bartitle']='<span id="bchead" style="font-size:12; font-weight:bold">Stock/Demand Position of '.getField('e_crops',$cond = array('crop_id'=>$row['crop_id']),'crop_name').'</span>';
			//' in '.getField('e_cities',$cond = array('city_id'=>$row['city_id']),'city_name').'</span>';
			$data['linetitle']='<span id="bchead" style="font-size:12; font-weight:bold">Fornightly Price Trend of  '.getField('e_crops',$cond = array('crop_id'=>$row['crop_id']),'crop_name').'</span>';
			//' in '.getField('e_cities',$cond = array('city_id'=>$row['city_id']),'city_name').'</span>';
			}
			
			
		}
		#________________________prices______________________________________________
		//$pqry = " SELECT DISTINCT(price) FROM e_foodinput WHERE 1 ";
		$pqry = " SELECT * FROM e_foodinput WHERE 1 ";
		if($dname!='') 
		$pqry .= ' and city_id in (select city_id from e_cities where city_name like "'.$dname.'" ) ';
				
		if($crop_id<=3 || $crop_id =="")
		$pqry .=' and crop_id = 4 '; 
		else 
		$pqry .=' and crop_id = '.$crop_id;
		if($provinceid>0) 
		$pqry .=' and province_id = '.$provinceid;
		$pqry .=' and date_added > now() - INTERVAL 15 day ';
		$pqry .=' order by date_added ASC limit 0,5 ';
		$data['pqry']	=	$pqry;
		$reslt = $this->SiteModel->myQueryz($pqry);
		foreach ($reslt as $ro){
			$data['lprice']	.=	$ro['price'].',';
		}
		$data['lprice']	=	 rtrim($data['lprice'],",");
		#________________________prices______________________________________________
		echo json_encode($data);
		}
		
	public function getTblData()
	{	$tbldata=''; $data[]=''; $cummulatives=true;
		$dname = $this->input->post('dname');
		$crop_id = $this->input->post('crop_id');
		$qry="SELECT * FROM e_foodinput WHERE fid IN ( SELECT MAX(fid) FROM e_foodinput GROUP BY crop_id,city_id) and crop_id = ".$crop_id;
		/*$dqry="SELECT * FROM e_foodinput WHERE fid IN ( SELECT MAX(fid) FROM e_foodinput GROUP BY crop_id,city_id) and crop_id = ".$crop_id." and city_id in (select city_id from e_cities where city_name like '".$dname."')";*/
		$zqry="SELECT * FROM e_foodinput WHERE crop_id = ".$crop_id." and city_id in (select city_id from e_cities where city_name like '".$dname."')";
		
		if($dname==""){
		$resd = $this->SiteModel->myQueryz($qry);
		}else{
		$resd = $this->SiteModel->myQueryz($zqry);
		$cummulatives=false;
		}
		
		
		foreach ($resd as $row) {

		/*if($row['mktstock']< $row['stockneeded']){ $cls = 'style="padding:8px; width:100%" class="blink_red" '; $dls = 'class="blink_red1" '; }
		else if($row['mktstock']==$row['stockneeded']){
			$cls = 'style="padding:8px; width:100%" class="blink_yellow" ';
			$dls = 'class="blink_yellow1" ';
		}
		else {
			$cls = 'style="padding:8px; width:100%" class="blink_green" ';
			$dls = 'class="blink_green1" ';
		}*/
		$range_start = $row['stockneeded'] + ceil(0.05*$row['stockneeded']); # 5% +
	    $range_end   = $row['stockneeded'] - ceil(0.05*$row['stockneeded']); # 5% -
	    
	    if(($range_end <= $row['mktstock']) && ($row['mktstock'] <= $range_start)){
	        $cls = 'style="padding:8px; width:100%" class="blink_yellow" ';
			$dls = 'class="blink_yellow1" ';
	    }
	    elseif($row['mktstock']<$range_end){
	        $cls = 'style="padding:8px; width:100%" class="blink_red" '; 
	        $dls = 'class="blink_red1" '; 
	    }
		else { //elseif($row['mktstock']>$range_start)
			$cls = 'style="padding:8px; width:100%" class="blink_green" ';
			$dls = 'class="blink_green1" ';
		}
		
		if($cummulatives){
		    $row['tstock'] = $this->AdminModel->getSumRows('e_foodinput','tstock',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of stock with millsinput
			$row['rpstock'] = $this->AdminModel->getSumRows('e_foodinput','rpstock',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of stock with mills at output
			$row['ropstock']= $this->AdminModel->getSumRows('e_foodinput','ropstock',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of production
			$row['production']= $this->AdminModel->getSumRows('e_foodinput','production',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of imports
			$row['imports']= $this->AdminModel->getSumRows('e_foodinput','imports',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of imports
			$row['retention']= $this->AdminModel->getSumRows('e_foodinput','retention',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of millsinput
			$row['millsinput']= $this->AdminModel->getSumRows('e_foodinput','millsinput',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
		}
		
if($row['crop_id']==2){		
$tbldata.= getField('e_cities',$cond = array('city_id'=>$row['city_id']),'city_name').'#'.getField('e_crops',$cond = array('crop_id'=>$row['crop_id']),'crop_name').' '.getField('e_units',$cond = array('unit_id'=>$row['unit_id']),'unit').'#'.date("d-m-Y",strtotime($row['date_added'])).'#'.$row['production'].'#'.$row['imports'].'#'.$row['retention'].'#'.$row['tstock'].'#'.$row['millsinput'].'#<div '.$cls.' >'.$row['rstock'].'</div>#'.$row['rpstock'].'#'.'-#'.'-#'.'-#'.$row['ropstock'].'#'.$row['mktstock'].'#'.$row['stockneeded'].'#';
#
$data['tblname']='sugar';
} 
else{
$tbldata.= getField('e_cities',$cond = array('city_id'=>$row['city_id']),'city_name').'#'.getField('e_crops',$cond = array('crop_id'=>$row['crop_id']),'crop_name').' '.getField('e_units',$cond = array('unit_id'=>$row['unit_id']),'unit').'#'.date("d-m-Y",strtotime($row['date_added'])).'#'.$row['production'].'#'.$row['imports'].'#'.$row['retention'].'#'.$row['tstock'].'#'.$row['millsinput'].'#<div '.$cls.' >'.$row['rstock'].'</div>#'.$row['rpstock'].'#'.$row['ropstock'].'#'.$row['mktstock'].'#'.$row['stockneeded'].'#';
#
$data['tblname']='wheat';
}
		}
		
		$data['tbdata']=rtrim($tbldata,"#");
		
		echo json_encode($data);
		}
		
	public function getPieCharts()
	{	$data[]='';
		$crop_id = $this->input->post('crop');
		if($crop_id!=null){
			foreach ($crop_id as $key => $value) {
					$crop_id=$value;
               }
		}
		else $crop_id=1;
		
		$data['punjab']		= $this->getprovicialStocks($crop_id,1);
		$data['sindh']		= $this->getprovicialStocks($crop_id,2);
		$data['kp']			= $this->getprovicialStocks($crop_id,3);
		$data['baloch']		= $this->getprovicialStocks($crop_id,4);
		
		echo json_encode($data);
	}
		
	public function getprovicialStocks($crop_id,$provid){
		$data=''; $rstock=$rpstock=$ropstock=$mktstock=$demand=0;
		$resd = $this->SiteModel->myQueryz("SELECT * FROM e_foodinput WHERE fid IN ( SELECT MAX(fid) FROM e_foodinput GROUP BY
				crop_id,city_id ) and crop_id = ".$crop_id." and province_id = ".$provid);	
		if($resd){
			foreach ($resd as $row) {
				$rstock 	+= $row['rstock'];
				$mktstock 	+= $row['mktstock'];
				$demand 	+= $row['stockneeded'];
				$rpstock	+= $this->AdminModel->getSumRows('e_foodinput','rpstock',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
				$ropstock	+= $this->AdminModel->getSumRows('e_foodinput','ropstock',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			}
			/*$total = $rstock+$rpstock+$ropstock+$mktstock;//.'#'.$demand;
			$rstock = ($rstock*100)/$total;
			$rpstock = ($rpstock*100)/$total;
			$ropstock = ($ropstock*100)/$total;
			$mktstock = ($mktstock*100)/$total;*/
			$title=getField('e_crops',$cond = array('crop_id'=>$crop_id),'crop_name').' Stock : '.getField('e_province',$cond = array('province_id'=>$provid),'name');
			$data = $rstock.'#'.$rpstock.'#'.$ropstock.'#'.$mktstock.'#'.$demand.'#'.$title;
		}
		return $data;
	}
	
	public function getMultiCharts()
	{	$data[]=''; $xdata=''; $indicatorz='total#'; $lokations=$districts=array();
		$indicator	= $this->input->post('indicator');
		$lokation	= $this->input->post('lokation');
		$district	= $this->input->post('district');
		
		#location
		if($lokation !=null){ $counter=0;
			$lokations=array();
			foreach ($lokation as $key => $value) {
					$lokations[$counter] =$value;
					$counter++;
               }
		}
		#district
		if($district !=null){ $counter=0;
			$districts=array();
			foreach ($district as $key => $value) {
					$districts[$counter]=$value;
					$counter++;
               }
		}
		#indictor
		if($indicator!= null && sizeOf($indicator)>0){
			foreach ($indicator as $key => $value) {
						$indicatorz .=$value.'#';        }  
			$indicatorz =rtrim($indicatorz,"#");
		}
		
		if(sizeOf($districts)>=1 && empty($lokations)){
			for($i=0; $i<sizeOf($districts); $i++){
				$xdata .= $this->getmultiStocks(0,$districts[$i],$indicatorz);
				$xdata .='@@@';	}
		}
		else{//if(sizeOf($lokations)>=1)
			$xdata ='';
			for($i=0; $i<sizeOf($lokations); $i++){
				$xdata .= $this->getmultiStocks($lokations[$i],0,$indicatorz);
				$xdata .='@@@';	}
		}
		$data['roundz'] 	=	rtrim($xdata,"@@@");
		$data['indicators'] =	$indicatorz;
		echo json_encode($data);
	}
	
	
	public function getProjectionz()
	{	$data[]=''; $xdata=''; $indicatorz=''; $crop=1; $provinces=array(1); $districts=array(0);
		$indicator 	= $this->input->post('indicator');
		$crop_id 	= $this->input->post('crop');
		$province = $this->input->post('province');
		$district = $this->input->post('district');
		
		#province
		if(isset($province) && $province !=null){ $counter=0;
			$provinces=array();
			foreach ($province as $key => $value) {
					$provinces[$counter]=$value;
					$counter++;
               }
		}
		
		#district
		if(isset($district) && $district !=null){ $counter=0;
			$districts=array();
			foreach ($district as $key => $value) {
					$districts[$counter] =$value;
					$counter++;
               }
		}
		

		#crop_id
		if(isset($crop_id) && $crop_id !=null){
			foreach ($crop_id as $key => $value) {
					$crop=$value;
               }
		}
		#indictor
		if($indicator!= null && sizeOf($indicator)>0){
			foreach ($indicator as $key => $value) {
					$indicatorz .=$value.'#';
                  }  
			$indicatorz =rtrim($indicatorz,"#");
		}
		
		for($i=0; $i<sizeOf($provinces); $i++){
			$xdata .= $this->getPojectedData($crop,0,$provinces[$i],$indicatorz);
			$xdata .='@@';
		}
		
		if(sizeOf($districts)>1){
			$xdata ='';
			for($i=0; $i<sizeOf($districts); $i++){
				$xdata .= $this->getPojectedData($crop,$districts[$i],0,$indicatorz);
				$xdata .='@@';
			}
		}	
		
		$data['roundz'] 	=	rtrim($xdata,"@@");
		$data['indicators'] =$indicatorz;
		
		echo json_encode($data);
	}
	
	public function getPojectedData($crop_id,$city_id,$province_id,$indicatorz){
		$data=''; $mkArry=''; $res =''; $iloop=0;$x=''; $d=""; $tot=0; $cntr=0;
		#indictor
		$indd = explode("#",$indicatorz); 
		foreach($indd as $vx){
			$mkArry=''; $res ='';
			$qry = "SELECT ".$vx." FROM e_foodinput WHERE crop_id = ".$crop_id;
			
			if($city_id!=0)
				$qry .= ' and city_id = '.$city_id;
			/*else
				$qry .= ' and fid IN ( SELECT MAX(fid) FROM e_foodinput GROUP BY crop_id,city_id ) ';*/
				
			if($province_id!=0)
				$qry .= ' and  province_id = '.$province_id;				
			$qry .= ' order by date_added DESC  limit 0,5 ';	
				
			$resd = $this->SiteModel->myQueryz($qry);	
			if($resd){
				foreach ($resd as $row) {
					$mkArry .= $row[$vx].'#';
				}
			}
				$tmpArry = explode('#',rtrim($mkArry ,"#"));
				for($loop =1; $loop<=5; $loop++){
					$asad=array_slice($tmpArry, $loop);
					$tot =$average = array_sum($asad)/count($asad);
							array_push($tmpArry,$tot);
						$res .= $tot.'#';
					}
				$data .= $mkArry.$res.'**'.$mkArry.'|'; //break;
		}
		if($city_id!=0)
		$title =getField('e_cities',$cond = array('city_id'=>$city_id),'city_name');
		if($city_id==0 && $province_id!=0)
		$title =getField('e_province',$cond = array('province_id'=>$province_id),'name');
		
		return rtrim($data ,"|").$title;
	}
	
	public function getmultiStocks($city_id,$province_id,$indicatorz){
		$data=''; $total=$transgenders=$males=$females=$cnic=$others=$dosage_one=$dosage_two=$booster=0; $seriesTitle=$title='';
		
		   if($city_id==0 && $province_id!=""){
				$total 		= $this->AdminModel->countRows('e_vaccinations',$cond= array('district'=>$province_id));
				$males		= $this->AdminModel->countRows('e_vaccinations',$cond= array('district'=>$province_id,"gender"=>"Male"));
				$females	= $this->AdminModel->countRows('e_vaccinations',$cond= array('district'=>$province_id,"gender"=>"Female"));
				$transgenders= $this->AdminModel->countRows('e_vaccinations',$cond= array('district'=>$province_id,"gender"=>"Transgender"));
				$cnic = $this->AdminModel->countRows('e_vaccinations',$cond= array('district'=>$province_id,"idtype<>"=>"Others"));
				$others = $this->AdminModel->countRows('e_vaccinations',$cond= array('district'=>$province_id,"idtype"=>"Others"));
				$dosage_one = $this->AdminModel->countRows('e_vaccinations',$cond= array('district'=>$province_id,"dosage"=>"1"));
				$dosage_two = $this->AdminModel->countRows('e_vaccinations',$cond= array('district'=>$province_id,"dosage"=>"2"));
				$booster = $this->AdminModel->countRows('e_vaccinations',$cond= array('district'=>$province_id,"dosage"=>"Booster"));
			$seriesTitle='#'.$province_id;
			$title=' Total Vaccinations ';
		   }
		   if($city_id>0 && $province_id==0){
				$total 		= $this->AdminModel->countRows('e_vaccinations',$cond= array('healthunitid'=>$city_id));
				$males		= $this->AdminModel->countRows('e_vaccinations',$cond= array('healthunitid'=>$city_id,"gender"=>"Male"));
				$females	= $this->AdminModel->countRows('e_vaccinations',$cond= array('healthunitid'=>$city_id,"gender"=>"Female"));
				$transgenders= $this->AdminModel->countRows('e_vaccinations',$cond= array('healthunitid'=>$city_id,"gender"=>"Transgender"));
				$cnic = $this->AdminModel->countRows('e_vaccinations',$cond= array('healthunitid'=>$city_id,"idtype<>"=>"Others"));
				$others = $this->AdminModel->countRows('e_vaccinations',$cond= array('healthunitid'=>$city_id,"idtype"=>"Others"));
				$dosage_one = $this->AdminModel->countRows('e_vaccinations',$cond= array('healthunitid'=>$city_id,"dosage"=>"1"));
				$dosage_two = $this->AdminModel->countRows('e_vaccinations',$cond= array('healthunitid'=>$city_id,"dosage"=>"2"));
				$booster = $this->AdminModel->countRows('e_vaccinations',$cond= array('healthunitid'=>$city_id,"dosage"=>"Booster"));
			$seriesTitle='#'.getField('e_locations',$cond = array('healthunitid'=>$city_id),'title');
			$title=' Total Vaccinations ';
		   }
		   
			/*if($city_id!=0){
			$title.=' : '.getField('e_cities',$cond = array('city_id'=>$city_id),'city_name');
			$seriesTitle='#'.getField('e_cities',$cond = array('city_id'=>$city_id),'city_name');
			$price 		= ceil($this->AdminModel->getAvgRows('e_foodinput','price',$cond= array('city_id'=>$city_id,'crop_id'=>$crop_id)));
			}
			if($city_id==0 && $province_id!=0){
			$title.=' : '.getField('e_province',$cond = array('province_id'=>$province_id),'name');
			$seriesTitle='#'.getField('e_province',$cond = array('province_id'=>$province_id),'name');
			$price = $this->AdminModel->getAvgRows('e_foodinput','price',$cond= array('province_id'=>$province_id,'crop_id'=>$crop_id));
			}*/
			
			#indictor
			$indd = explode("#",$indicatorz); $x=''; $d="";
			foreach($indd as $vx){
				$x =& $$vx; 
				$d .=$x."#";
			}
			//$data = $total.'#'.$males.'#'.$females.'#'.$transgenders.'#'.$title.$seriesTitle;
			$data = $d.$title.$seriesTitle;
		
		return $data;
	}
	
	public function getSummaryData()
	{	$tbldata=''; $data[]=''; $cummulatives=true;
		$indicatorz=''; $default_crop='>0'; $provinces[]=0; $districts[]=0;
		$hdata='';$xdata=''; $rstock=$rpstock=$ropstock=$mktstock=$stockneeded=$price=0;
		
		$indicator 	= $this->input->post('indicator');
		$crop_id 	= $this->input->post('crop');
		$province = $this->input->post('province');
		$district = $this->input->post('district');
		$qry='SELECT * FROM e_foodinput WHERE fid IN ( SELECT MAX(fid) FROM e_foodinput GROUP BY crop_id,city_id ) ';
		
		if($indicator==null && $crop_id==null && $province==null && $district==null){
			$qry = "SELECT * FROM e_foodinput WHERE fid IN ( SELECT MAX(fid) FROM e_foodinput GROUP BY crop_id,city_id ) and crop_id $default_crop";
		}
		if($crop_id != null){
			$cropid_imploded = implode(",",$crop_id);
			$qry .= ' and crop_id  in  ('.$cropid_imploded.') ';
			#$cummulatives=false;
		}
		
		if($district != null){
			$district_imploded = implode(",",$district);
			$qry .= ' and city_id  in  ('.$district_imploded.') ';
		}
		if($province != null){
			$province_imploded = implode(",",$province);
			$qry .= ' and province_id  in  ('.$province_imploded.') ';
		}
		$resd = $this->SiteModel->myQueryz($qry);
		
		
		foreach ($resd as $row) {
			$dls=$cls='';
		if($row['crop_id']>3){
			/*if($row['mktstock']< $row['stockneeded']){ $dls = 'style="padding:8px; width:100%" class="blink_red" ';}
			else if($row['mktstock']==$row['stockneeded']){
				$dls = 'style="padding:8px; width:100%" class="blink_yellow" ';
			}
			else {
				$dls = 'style="padding:8px; width:100%" class="blink_green" ';
			}*/
			$range_start = $row['stockneeded'] + ceil(0.05*$row['stockneeded']); # 5% +
			$range_end   = $row['stockneeded'] - ceil(0.05*$row['stockneeded']); # 5% -
			
			if(($range_end <= $row['mktstock']) && ($row['mktstock'] <= $range_start)){
				$dls = 'style="padding:8px; width:100%" class="blink_yellow" ';
			}
			elseif($row['mktstock']<$range_end){
				$dls = 'style="padding:8px; width:100%" class="blink_red" '; 
			}
			else { //elseif($row['mktstock']>$range_start)
				$dls = 'style="padding:8px; width:100%" class="blink_green" ';
			}
		}
		else{
			/*if($row['mktstock']< $row['stockneeded']){ $cls = 'style="padding:8px; width:100%" class="blink_red" ';}
			else if($row['mktstock']==$row['stockneeded']){
				$cls = 'style="padding:8px; width:100%" class="blink_yellow" ';
			}
			else {
				$cls = 'style="padding:8px; width:100%" class="blink_green" ';
			}*/
			$range_start = $row['stockneeded'] + ceil(0.05*$row['stockneeded']); # 5% +
			$range_end   = $row['stockneeded'] - ceil(0.05*$row['stockneeded']); # 5% -
			
			if(($range_end <= $row['mktstock']) && ($row['mktstock'] <= $range_start)){
				$cls = 'style="padding:8px; width:100%" class="blink_yellow" ';
			}
			elseif($row['mktstock']<$range_end){
				$cls = 'style="padding:8px; width:100%" class="blink_red" '; 
			}
			else { //elseif($row['mktstock']>$range_start)
				$cls = 'style="padding:8px; width:100%" class="blink_green" ';
			}
		}
		if($cummulatives){
		    $row['tstock'] = $this->AdminModel->getSumRows('e_foodinput','tstock',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of stock with millsinput
			$row['rpstock'] = $this->AdminModel->getSumRows('e_foodinput','rpstock',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of stock with mills at output
			$row['ropstock']= $this->AdminModel->getSumRows('e_foodinput','ropstock',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of production
			$row['production']= $this->AdminModel->getSumRows('e_foodinput','production',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of imports
			$row['imports']= $this->AdminModel->getSumRows('e_foodinput','imports',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of imports
			$row['retention']= $this->AdminModel->getSumRows('e_foodinput','retention',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
			#sum of millsinput
			$row['millsinput']= $this->AdminModel->getSumRows('e_foodinput','millsinput',$cond= array('city_id'=>$row['city_id'],'crop_id'=>$row['crop_id']));
		}
		
$tbldata.= getField('e_province',$cond = array('province_id'=>$row['province_id']),'name').'#'.getField('e_cities',$cond = array('city_id'=>$row['city_id']),'city_name').'#'.getField('e_crops',$cond = array('crop_id'=>$row['crop_id']),'crop_name').' '.getField('e_units',$cond = array('unit_id'=>$row['unit_id']),'unit').'#'.date("d-m-Y",strtotime($row['date_added'])).'#'.$row['production'].'#'.$row['imports'].'#'.$row['retention'].'#'.$row['tstock'].'#'.$row['millsinput'].'#<div '.$cls.' >'.$row['rstock'].'</div>#'.$row['rpstock'].'#'.$row['ropstock'].'#<div '.$dls.' >'.$row['mktstock'].'</div>#'.$row['stockneeded'].'#';
		}
		
		$data['tbdata']=rtrim($tbldata,"#");
		
		echo json_encode($data);
	}
	
	
	#___________________________________________
	public function groupSubCrops()
	{
		$resultArray = array(); $dt='';
		$val = $this->input->post('value');
		if($val!='')
			$qry = ' select * from e_crops where crop_group ="'.$val.'"';
		else
			$qry = ' select * from e_crops ';
		$res = $this->AdminModel->myQuery($qry)->result_array();
		foreach ($res as $row) {
					if($row['crop_id']<=3) continue;
					$dt .= '
<option value="'.$row['crop_id'].'">'.$row['crop_name'].'</option>
';
			}
		$resultArray['group']=$dt;
		echo json_encode($resultArray);
	}
	#___________________________________________
	public function groupSubDistricts()
	{
		$resultArray = array(); $dt='';
		$val = $this->input->post('province');
		if($val>=1)
			$qry = ' select * from e_cities where province_id ="'.$val.'"';
		else
			$qry = ' select * from e_cities ';
		$res = $this->AdminModel->myQuery($qry)->result_array();
		foreach ($res as $row) {
					if($row['city_id']==$val)
						$dt .= '<option value="'.$row['city_id'].'" selected >'.$row['city_name'].'</option>';
					else
						$dt .= '<option value="'.$row['city_id'].'">'.$row['city_name'].'</option>';
			}
		$resultArray['subdistricts']=$dt;
		echo json_encode($resultArray);
	}
	
	public function readDataFile()
	{
		 $filename = $this->input->post('fname');
		 $crop_title = $this->input->post('crop');
		 $pos = strpos($filename, 'xlsx');
		 if ($pos === false) {
				echo 'File is not in Excel (.xlsx) Format';
		 }else {
		$xlsx = new SimpleXLSX('assets/lib/uploads/'.$filename);
		
		$sheets=$xlsx->sheetNames(); $cnt=1;
		foreach($sheets as $index => $name){
		echo '<table border="1" cellpadding="3" class="table table-striped table-bordered saik ">';
		echo '<caption><h1>'.$crop_title.' :'.$name.'</h1></caption>';
			list($cols) = $xlsx->dimension($cnt);
			foreach( $xlsx->rows($cnt) as $k => $r) {
				//if ($k == 0) continue; // skip first row
				echo '<tr>';
				for( $i = 0; $i < $cols; $i++)
				{
			
					echo '<td>'.( (isset($r[$i])) ? $r[$i] : '&nbsp;' ).'</td>';
			
				}
				echo '</tr>';
			}
			$cnt++;
		echo '</table>';
		}
		}	
	}
} 
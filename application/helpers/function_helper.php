<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('__send_curl_request'))
{
	
	if(!function_exists('_get_SESSION_VAL'))
	{
	  function _get_SESSION_VAL($KEY)
	  {
	    if(isset($_SESSION[$KEY]))
	      return $_SESSION[$KEY];
	    else
	      return false;
	  }
	}

	function get_user_credentials()
	{
		$CI = & get_instance();
		$apiuser = $CI->session->username;
		$apipass = $CI->session->password;
		$credentials_arr = array();
		$credentials_arr['username'] = $apiuser;
		$credentials_arr['password'] = $apipass;
		return $credentials_arr;
	}
	
	
	function login_check()
	{
		$CI = & get_instance();
		if (!isset($_SESSION))
		session_start();
		
		if (  $CI->session->logged_in == 'yes' && $CI->session->username != '' && $CI->session->password != '')
		{
			// logged in...
			return true;
		}
		else
		{
			// unauthorized...
			if($CI->router->class != 'Login')
			{
				$_SESSION = array();
				@session_destroy();
				redirect('Login');
				exit;
			}
		}
	}
	
	

	
	function show_flash_data()
	{
		$CI = & get_instance();
		$resp='';
		 if($CI->session->flashdata('alert_data'))
		 {
				$alert = $CI->session->flashdata('alert_data');
				$resp .= '<div class="alert alert-'.$alert['type'].' alert-dismissible" role="alert">';
				$resp .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
				$resp .= $alert['details'];
				$resp .= '</div>';				
         }  
		 
		 return $resp;
	}
	function in_hours($minutes) {
	    //floor($minutes / 60).':'.($minutes -   floor($minutes / 60) * 60);
	    if(!isset($minutes) || empty($minutes) || $minutes == null)
	    {
	    	return '00:00';
	    }
	    $h = ($minutes / 60);
	    $h = floor($h);
	    $m = $minutes -   floor($minutes / 60) *60;
	    if($h<10) {
	        $h = '0'.$h;
	    }
	    $m = floor($m);
	    if($m < 10) {
	        $m = '0'.$m;
	    }
	    $time = $h.':'.$m;
	    return $time;
	}

	function send_curl_by_post($req_url, $post_data)
	{
		$ch = curl_init($req_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

		$returnedData = curl_exec($ch);

		if ($returnedData === false)
		{
			$CI->errors['curl'] = 'curl error #' . curl_errno($ch) . ': ' . curl_error($ch);
			$log_msg = $CI->errors['curl'];
			//$CI->_defined_issues($log_msg, $file . LINE, 2);
			return FALSE;
			die();
		}

		curl_close($ch);
		return $returnedData;

	}
	if (! function_exists('currency')) {
		    function currency($input,$numberFormat = TRUE)
		    {
		        $ci = & get_instance();
		        $ci->load->database();
		        $var = $ci->session->userdata('set_currency');
		        // asign session data to var
		        if (isset($var)) {
		            // check to see thath we have somethink in session
		            $ci->db->select('*');
		            $ci->db->from('currencies');
		            // you need valid database and table
		            $ci->db->where('name', $var);
		            // column with name of the currency from database
		            $query 	= $ci->db->get();
		            $row 	= $query->row();
		            $rate 	= $row->rate;
		            $symbol = $row->symbol;
		            // column with rates from database
		        } else {
		            $rate = 1;
		            // default value for default currency
		        }
		        $total = (double) $input * (double) $rate;
		        if($numberFormat)
		         return $symbol.' '.number_format((double) $total, 2);
		     	else 
		         return $total;
		        // enough preccision for now, change if you want
		    }
		}
	function set_default_city()
	{
		$CI = & get_instance();
		if (!isset($_SESSION))
		session_start();
		
		if (  $CI->session->set_area= 'yes')
		{
			return true;
		}
		else
		{
			$data = array(
				'set_area'	=> 'yes',
				'area'		=>	'Pakistan',
				'area_type' => 'National'
			);
			$CI->session->set_userdata($data);
		}

	}

		if (! function_exists('label')) {

		    function label()
		    {
		        $ci = & get_instance();
		        $var = $ci->session->userdata('set_currency');
		        if (isset($var)) {
		            $result = $ci->session->userdata('set_currency');
		            //set name of the currency from session
		        } else {
		            $result = "pkr";
		            // set yout current currency
		        }
		        return $result;
		    }
		}
		
	
	//cms helper function
	function getParent($level,$parent_id)
	{
		if ($level == 0) {
			return "No Parent";
		}
		else {
			getField('e_menu',array('menuid'=>$parent_id),'name');
			return $record;
		}
	}
	function getParentCat($level,$parent_id)
	{
		if ($level == 0) {
			return "No Parent";
		}
		else {
			$CI =& get_instance();
			$CI->load->model('AdminModel');
			$record = $CI->AdminModel->getField('e_category',array('cat_id'=>$parent_id),'name');
			return $record;
		}
	}
	function getParentOption($select_id)
	{
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		$records = $CI->AdminModel->getRows('e_menu',array('level'=>'0'));
		$selected = "";
		foreach ($records as $row) 
		{
			if($row['menuid'] == $select_id) $selected= "selected";
			else $selected ="";
			echo "<option value=".$row['menuid']." ".$selected." >".$row['name']."</option>";
			
				$records2 = $CI->AdminModel->getRows('e_menu',array('parent_id'=>$row['menuid']));
				if ($records2)
				{
					foreach ($records2 as $row2)
					{
						if($row2['menuid'] == $select_id) $selected= "selected";
						else $selected ="";
						echo "<option value='sub-".$row2['menuid']."' ".$selected.">-- &nbsp;".$row2['name']."</option>";
					}
				}
				
			
		}
	}
	function getParentOptionCat($select_id)
	{
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		$records = $CI->AdminModel->getRows('e_category',array('level'=>'0'));
		$selected = "";
		foreach ($records as $row) 
		{
			if($row['cat_id'] == $select_id) $selected= "selected";
			else $selected ="";
			echo "<option value=".$row['cat_id']." ".$selected." >".$row['name']."</option>";
	
		}
	}
	
	function getAllCategories($level=0)
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
        return   $CI->SiteModel->getAllCategories($level);
	}

	//////////////////generic function for get option
	function getOption($tableName,$value='id',$showValue='name',$select="",$cond=array(),$selectOption=FALSE)
	{
		$CI = & get_instance();
		$CI->load->model('AdminModel');
		$records2 = $CI->AdminModel->multiCondition($tableName,$cond);
		if ($records2)
		{
			if($selectOption) echo "<option value='' disabled selected>Select option</option>";
			foreach ($records2 as $row2)
			{
				if($row2[$value] == $select) $selected= "selected";
				else $selected ="";
				echo "<option value='".$row2[$value]."' ".$selected.">".$row2[$showValue]."</option>";
			}
		}
		/*else
			echo "<option value=''>No Options</option>";*/
	}
	
	function getOptionAnokhi($tableName,$value='id',$showValue='name',$select="",$projid,$lot_no)
	{
		$CI = & get_instance();
		$CI->load->model('AdminModel');
		$records2 = $CI->AdminModel->multiCondition($tableName,$cond);
		if ($records2)
		{
			//echo "<option value='' disabled selected>Select option</option>";
			foreach ($records2 as $row2)
			{
				if($CI->AdminModel->checkExistedRecord("e_wsdaily"," project_id=".$projid ." and lot_no = '".$lot_no."' and contract_item_id = ".$row2[$value])) continue;
				
				if($row2[$value] == $select) $selected= "selected";
				else $selected ="";
				echo "<option value='".$row2[$value]."' ".$selected.">".$row2[$showValue]."</option>";
			}
		}
		else
			echo "<option value=''>No Options</option>";
	}
	
	///////////////generic function for get field
	function separator($arr,$field,$delimtr = ',')
	{
		if (!$arr) return "";
		$preparedArr = [];
		foreach ($arr as $row) {
			$preparedArr[] = $row[$field];
		}
		if(!$preparedArr) return "";
		else
		return implode($delimtr, $preparedArr);
	}
	function is_value_exist($tableName,$field_name,$field_value)
	{
		if($field_value == "") return false;
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		return  $CI->AdminModel->checkExistFieldValue($tableName,array($field_name=>$field_value));

	}
	//category-Path
	function getCatPath($id){
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		return $records2 = $CI->AdminModel->getCatPath($id);
	
	}
	//item-Path
	function getItemPath($id){
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		return $records2 = $CI->AdminModel->getItemPath($id);
	
	}
	function getField($tableName,$cond = array(),$field)
	{
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		return $records2 = $CI->AdminModel->getField($tableName,$cond,$field);
	}
	function getItemAvailQty($itemID)
	{
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		return $records2 = $CI->AdminModel->getItemAvailQty($itemID);
	}
	function getRow($tableName,$cond = array())
	{
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		return $records2 = $CI->AdminModel->getRow($tableName,$cond);
	}
	function getData($table,$conditions = array(),$result = 'all')
	{
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		return $records2 = $CI->AdminModel->multiCondition($table,$conditions,$result);
	}
	function multiCondition($table,$conditions = array(),$result = 'all')
	{
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		return $records2 = $CI->AdminModel->multiCondition($table,$conditions,$result);
	}
	function countRows($tableName,$cond = array())
	{
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		return $records2 = $CI->AdminModel->countRows($tableName,$cond);
	}
	 function getSumRows($tableName,$sumCol,$cond = array())
	{
		$CI =& get_instance();
		$CI->load->model('AdminModel');
		return $records2 = $CI->AdminModel->getSumRows($tableName,$sumCol,$cond);
	}
	###################site helper##################################
	function getNavMenu($controler,$function,$pageSlug = '')
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		$records = $CI->SiteModel->getMenuRows('e_menu',array('level'=>'0'));
		foreach ($records as $row)
		{
			extract($row);
			if (in_array('navigation',explode(',', $position)))
			{   
				if($row['slug'] == $pageSlug && $row['slug'] != "") $selected ='active';
				else $selected = "";

				$records2 = $CI->SiteModel->getMenuRows('e_menu',array('parent_id'=>$menuid));
				$add = "";
				if(!empty($records2)) 
				{
					$add = '&nbsp;<span class="caret"></span>';
					$class = "dropdown";
					$aAdd = ' class="dropdown-toggle" ';

				}
				else $add = $class = $aAdd= ""; 

				echo '<li  class=" '.$selected.' '.$class.' " '.$aAdd.'>
						<a   href="'.base_url().$slug.'"><span>'.$name.$add.'</span>  </a>';
              
				
				if ($records2)
				{
					echo "<ul class='dropdown-menu'>";
					foreach ($records2 as $row2)
					{
						$records3 = $CI->SiteModel->getMenuRows('e_menu',array('parent_id'=>$row2['menuid']));
						if ($records3) {
							$class3 = "dropdown";
							$aAdd3 = ' class="dropdown-toggle" data-toggle="dropdown"';;
						}
						else $class3 = $aAdd3 = "";
						echo '<li class="aa '.$class3.'" >
								<a style="z-index:999999" '.$aAdd3.' href="'.base_url().$row2['slug'].'"  >'.$row2['name'].'</a>';
						
						if ($records3)
						{
							echo "<ul class='dropdown-menu'>";
							foreach ($records3 as $row3)
							{
								echo '<li >
									<a href="'.base_url().$row3['slug'].'"><span>'.$row3['name'].'</span></a>
	                     		</li>';					
							}
							echo "</ul>";
						}
						 echo   '</li>';					
					}
				  echo "</ul>";
				}
				 echo   '</li>';
			}
		}
	}
	 function getNavMenu2($controler,$function,$pageSlug = '')
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		$records = $CI->SiteModel->getMenuRows('e_menu',array('level'=>'0'));
		foreach ($records as $row)
		{
			extract($row);
			if (in_array('navigation',explode(',', $position)))
			{   
				$mainSlug = 'pages';
				if($row['slug'] == 'shop' || $row['slug'] == 'Shop') $mainSlug = 'shop';
				$records2 = $CI->SiteModel->getMenuRows('e_category',array('menu'=>$menuid));
				$add = "";
				if(!empty($records2)){$add = '&nbsp;<span class="caret"></span>';$childClass ='menu-item-has-children';}
				else $add = $childClass = ""; 

				echo '<li class="'.$childClass.'">
						<a href="'.base_url().$slug.'">'.$name.$add.'</a>';

				if ($records2)
				{
					echo "<ul class='sub-menu'>";
					foreach ($records2 as $row2)
					{
						$records3 = $CI->SiteModel->getMenuRows('e_category',array('parent_id'=>$row2['cat_id']));
						if(!empty($records3)) $childClass2 ='menu-item-has-children';
							else $childClass2 = ""; 

							echo '<li class="'.$childClass2.'">
								<a href="'.base_url().$mainSlug.'/'.$row2['slug'].'">'.$row2['name'].'</a>';
						
						if ($records3)
						{
							echo "<ul class='sub-menu' style='min-height:252px'>";
							foreach ($records3 as $row3)
							{
								echo '<li >
									<a href="'.base_url().$mainSlug.'/'.$row3['slug'].'">'.$row3['name'].'</a>
	                     		</li>';					
							}
							echo "</ul>";
						}
						 echo   '</li>';					
					}
				  echo "</ul>";
				}
				 echo   '</li>';
			}
		}
	}

	
	//footer link
	function getFooterLinks()
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		$records = $CI->SiteModel->getMenuRows('e_menu',array('level'=>'0'));
		foreach ($records as $row)
		{
			extract($row);
			if (in_array('footer',explode(',', $position)))
			{
				echo "<li><a href='".base_url()."$row[slug]'><span>$row[name]</span></a></li>
						";
			}
		}
		// echo "<li><a href='".base_url().'home'."'><span>Home</span></a></li>";
	}
	function getFooterLinks2()
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		$records = $CI->SiteModel->getMenuRows('e_menu',array('level'=>'0'));
		foreach ($records as $row)
		{
			extract($row);
			if (in_array('footer2',explode(',', $position)))
			{
				echo "<li><a href='".base_url()."$row[slug]'><span>$row[name]</span></a></li>
						";
			}
		}
		// echo "<li><a href='".base_url().'home'."'><span>Home</span></a></li>";
	}


	##################################
	 function renderChartYears($cond = array())
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		$years = $CI->SiteModel->multiCondition('data',$cond);
		$data = "";
		foreach ($years as $key => $value) {
			$data = $data."'".$value['year']."',";
		}
		echo  $data;
	}
	function renderChartValues($cond = array())
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		$years = $CI->SiteModel->multiCondition('data',$cond);
		$data = "";
		foreach ($years as $key => $value) {
			$data = $data."".$value['value'].",";
		}
		echo  $data;
		// echo "'1995', '1996', '2000', '2006', '2010', '".$indicator."'";
	}

	function getYears($indicator_no,$level = "National")
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		$years = $CI->SiteModel->getYears($indicator_no,$level);
		$data = "";
		foreach ($years as $key => $value) {
			$data = $data."'".$value['year']."',";
		}
		echo  $data;
		// echo "'1995', '1996', '2000', '2006', '2010', '".$indicator."'";
	}
	

	function getPieChartData($cond)
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		$years = $CI->SiteModel->multiCondition('data',$cond);
		$data = "";
		foreach ($years as $key => $value) {
			$name = " Year ".$value['year'];
			$year = $value['value'];
			$data =  $data."{name:'$name',y:".$year."},";
			
		}
		echo  $data;
	}
	

	###################### PHASE II ################
	 function getFilterValues($subFilters,$cond = array())
		{
			$CI =& get_instance();
			$CI->load->model('SiteModel');

			foreach ($subFilters as $sf)
			{	$NCond = $cond + array('e_fdata.filter_id'=>$sf['filter_id']);
				$data = $CI->SiteModel->getFilterValues($NCond);
				$value = array();
				foreach ($data as $d) {
					$value[] = $d['value'];
				}
				echo   "{
                        name: '$sf[filter_title]',
                        data: [".implode(',', $value)."]
                    },";
			}
			
		}	
	function retFilterValues($subFilters,$cond= array())
	{
		$CI =& get_instance();
			$CI->load->model('SiteModel');
			$values = "";
			foreach ($subFilters as $sf)
			{	$NCond = $cond + array('e_fdata.filter_id'=>$sf['filter_id']);
				$data = $CI->SiteModel->getFilterValues($NCond);
				$value = array();
				foreach ($data as $d) {
					$value[] = $d['value'];
				}
				$values = $values."{
                        name: '$sf[filter_title]',
                        data: [".implode(',', $value)."]
                    },";
			}
			return $values;
	}
	function retFilterValuesV2($subFilters,$cond= array(),$yearArr)
	{
		$CI =& get_instance();
			$CI->load->model('SiteModel');
			$values = "";
			foreach ($subFilters as $sf)
			{	$NCond = $cond + array('e_fdata.filter_id'=>$sf['filter_id']);
				$data = $CI->SiteModel->getFilterValues($NCond);
				$value = array();
				foreach ($data as $d) {
					$value[$d['year']] = $d['value'];
				}
				foreach ($yearArr as $y) {
					if (!array_key_exists($y, $value)) {
						$value[$y] = "0";
					}
				}
				ksort($value);
				$zeroFlag = true;
				foreach ($value as $k) {
					if($k > 0) $zeroFlag = false;
				}
				if($zeroFlag) $value = array();
				
				if($value)
				$values = $values."{
                        name: '$sf[filter_title]',
                        data: [".implode(',', $value)."]
                    },";
			}
			return $values;
	}
	function retFilterValuesYearBased($subFilters,$cond1 = "", $cond2 = "")
	{
		$CI =& get_instance();
			$CI->load->model('SiteModel');
			$values = "";
			foreach ($subFilters as $sf)
			{	
				
				$cond3 = $cond1." and e_fdata.filter_id ='".$sf['filter_id']."'";
				  $q = "
					SELECT * FROM data 
					JOIN e_fdata ON data.data_id = e_fdata.data_id
					WHERE
					( $cond3 ) and ( $cond2 )
					order by year asc
				";
				$data = $CI->SiteModel->myQuery($q);
				$value = array();
				foreach ($data as $d) {
					$value[] = $d['value'];
				}
				if($value)
				 $values = $values."{
                        name: '$sf[filter_title]',
                        data: [".implode(',', $value)."]
                    },";
			}
			if($values)
			return $values;
			else
				return array();
	}
	function retFilterValuesYearBasedV2($subFilters,$cond1 = "", $cond2 = "",$yearArr)
	{
		$CI =& get_instance();
			$CI->load->model('SiteModel');
			$values = "";
			foreach ($subFilters as $sf)
			{	
				
				$cond3 = $cond1." and e_fdata.filter_id ='".$sf['filter_id']."'";
				  $q = "
					SELECT * FROM data 
					JOIN e_fdata ON data.data_id = e_fdata.data_id
					WHERE
					( $cond3 ) and ( $cond2 )
					order by year asc
				";
				$data = $CI->SiteModel->myQuery($q);
				$value = array();
				foreach ($data as $d) {
					$value[$d['year']] = $d['value'];
				}
				foreach ($yearArr as $y) {
					if (!array_key_exists($y, $value)) {
						$value[$y] = "0";
					}
				}
				ksort($value);
				$zeroFlag = true;
				foreach ($value as $k) {
					if($k > 0) $zeroFlag = false;
				}
				if($zeroFlag) $value = array();

				if($value)
				 $values = $values."{
                        name: '$sf[filter_title]',
                        data: [".implode(',', $value)."]
                    },";
			}
			if($values)
			return $values;
			else
				return array();
	}

	//

	 function getChartYearsFilterBased($cond = array())
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		$years = $CI->SiteModel->getDistinct('data','year',$cond,TRUE);
		foreach ($years as $y) {
			$preY[] = $y['year'];
		}
		sort($preY);
		return implode(',', $preY);
	}
	function getChartYearsRegionBased($indicator_no,$subLevel)
	{
		$subLevelArr = explode(',', $subLevel);
		$preArr = array();
		foreach ($subLevelArr as $prov) {
			$preArr[] = "sub_level = '".$prov."'"; 	
		}
		$subLevelPreparedString =  implode(' || ', $preArr);

		$CI =& get_instance();
		$CI->load->model('SiteModel');
	 $q = "select distinct year from data where ($subLevelPreparedString) and (indicator_no = '$indicator_no') ";
		 $res = $CI->SiteModel->myQuery($q);
		 foreach ($res as $row) {
		 	$years[] = $row['year'];
		 }
		 sort($years);
		 echo implode(',', $years);
	}
	 function getValuesRegionBased($indicator_no,$subLevel)
	 {

	 	$CI =& get_instance();
		$CI->load->model('SiteModel');

	 	$subLevelArr = explode(',', $subLevel);
	 	foreach ($subLevelArr as $prov) {
	 		$values = array();
	 		$res = $CI->SiteModel->getOrderby('data','year','asc',array('level'=>'Region','sub_level'=>$prov,'indicator_no'=>$indicator_no));
	 		if($res){
	 			foreach ($res as $row) {
	 				$values[] = $row['headline_value'];
	 			}
	 			echo   "{
                        name: '$prov',
                        data: [".implode(',', $values)."]
                    },";
	 		}
	 		
	 	}
	 }
	 //Calling : Explore and Trend
	function getValues($indicator_no,$level = "National")
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		$years = $CI->SiteModel->getChartValues($indicator_no,$level);
		$data = "";
		foreach ($years as $key => $value) {
			$data = $data."".$value['headline_value'].",";
		}
		echo  $data;
		// echo "'1995', '1996', '2000', '2006', '2010', '".$indicator."'";
	}

	#################### renderSummary
	//return array of id's
	function get_filter_cat_ids($indicatorsArr)
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');

		$filters_cat_ids = array();
        foreach ($indicatorsArr as $ind_no)
        {
        	$indRow = $CI->SiteModel->getRow('e_indicator',array('indicator_no'=>$ind_no));
            if($indRow['filter_national'] != "")
            {
                foreach(explode(',', $indRow['filter_national']) as $temp)
                {
                    $filters_cat_ids[] = $temp;
                }
            }
        }
        return array_unique($filters_cat_ids);
	}
	#############end render summary
	function getSubFilters($filters_cat_ids)
	{
		$subF = array();
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		if($filters_cat_ids){
			  $q = "
			SELECT * FROM e_filters
			WHERE 
			".prepareOr($filters_cat_ids,'filter_cat_id');
			$arr = $CI->SiteModel->myQuery($q);

			foreach ($arr as $row) {
				$subF[] =$row;
			}
		}
		return $subF;
	}
	 function prepareOr($arr,$index)
	{
		$newArr = array();
		foreach ($arr as $item) {
			$newArr[] = " $index = '$item' ";
		}
		return implode(' || ', $newArr);
	}

	########### overall
	function getOrderedIndicator($target_no)
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		return $CI->SiteModel->getOrderby('e_indicator','indicator_no','asc',array('target_no'=>$target_no));
	}
	function getTargets($goal)
	{
		$CI =& get_instance();
		$CI->load->model('SiteModel');
		$q = "
			SELECT * FROM e_targets
			where goal_no = '$goal'
			order by orderNo asc			 
		";

		 $targets =  $CI->SiteModel->myQuery($q);
		 foreach ($targets as $key => $value) {
		 	$newArr[$value['orderNo']] = $value;
		 }

		 ksort($newArr);
		 return $newArr;
		
	}

}

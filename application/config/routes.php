<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Home';
$route['Home'] = "Home";
$route['home'] = "Home";

$route['Login'] = "Login";
$route['login'] = "Login";
$route['signup'] = "Login/signup";
$route['forgot'] = "Login/forgot";



$route['Assemblies/(:any)'] = "Home/assemblies/$1";
$route['assemblies/(:any)'] = "Home/assemblies/$1";


$route['Analysis/(:any)/(:any)'] = "Analysis/ana/$1/$2";
$route['analysis/(:any)/(:any)'] = "Analysis/ana/$1/$2";
$route['Pdfgenerator'] = "Pdfgenerator";
//$route['Pdfgenerator/(.*)']='Pdfgenerator/index/$1';
$route['Pdfgenerator/downloadBom']='Pdfgenerator/downloadBom/$1';
$route['Pdfgenerator/downloadMat']='Pdfgenerator/downloadMat/$1';
$route['Pdfgenerator/downloadGrn']='Pdfgenerator/downloadGrn/$1';
$route['Pdfgenerator/downloadTempIssue']='Pdfgenerator/downloadTempIssue/$1';
$route['Pdfgenerator/deliveryChallan']='Pdfgenerator/deliveryChallan/$1';
$route['Pdfgenerator/deliveryChallan']='Pdfgenerator/downloadMaterialMovement/$1';
$route['Pdfgenerator/deliveryChallan']='Pdfgenerator/downloadAdvBooking/$1';
$route['Pdfgenerator/deliveryChallan']='Pdfgenerator/downloadReleases/$1';
$route['Pdfgenerator/deliveryChallan']='Pdfgenerator/downloadRestock/$1';
$route['Pdfgenerator/deliveryChallan']='Pdfgenerator/downloadFixedAsset/$1';


$route['(:any)'] = "Pages/$1";
$route['404_override'] = 'Not_found_404';
$route['translate_uri_dashes'] = FALSE;


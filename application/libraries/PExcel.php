<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/PHPExcel.php';
class PExcel extends PHPExcel
{
	function __construct()
    {
        parent::__construct();
    }
}
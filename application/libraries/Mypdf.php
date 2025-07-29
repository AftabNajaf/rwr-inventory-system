<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mypdf {
     public $param;
    public $pdf;
 
    public function __construct($param = '"en-GB-x","A4","","",10,10,10,10,6,3')
    { include_once APPPATH.'/third_party/mpdf/mpdf.php';
        $this->param =$param;
        $this->pdf = new mPDF($this->param);
    }
}
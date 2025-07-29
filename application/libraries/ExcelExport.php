<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/PHPExcel.php';

class ExcelExport {

    private $objPHPExcel;

    public function __construct() {
        $this->objPHPExcel = new PHPExcel();
    }

    public function exportData($queryResult, $columns, $filename) {

		set_time_limit(600); // 
        $this->objPHPExcel->setActiveSheetIndex(0);
        $sheet = $this->objPHPExcel->getActiveSheet();

        // Write column names at the top
       // $columns = $queryResult->list_fields();
try{
        $columnIndex = 'A';
        foreach ($columns as $columnName) {
            $sheet->setCellValue($columnIndex . '1', $columnName);
            $columnIndex++;
        }
 // Write data rows in chunks to manage memory usage
    $chunkSize = 1000;
    $totalRows = count($queryResult);
    $rowIndex = 2;

    for ($i = 0; $i < $totalRows; $i += $chunkSize) {
        $chunk = array_slice($queryResult, $i, $chunkSize);

        foreach ($chunk as $row) {
            $columnIndex = 'A';
            foreach ($row as $value) {
                // Handle data type issues and convert if necessary
                if (is_numeric($value)) {
                    $sheet->setCellValue($columnIndex . $rowIndex, (float)$value);
                } else {
                    $sheet->setCellValue($columnIndex . $rowIndex, (string)$value);
                }
                $columnIndex++;
            }
            $rowIndex++;
        }

        
    }
/****************
         $rowIndex = 2;
    foreach ($queryResult as $row) {
        $columnIndex = 'A';
        foreach ($row as $value) {
            // Handle data type issues and convert if necessary
            if (is_numeric($value)) {
                $sheet->setCellValue($columnIndex . $rowIndex, (float)$value);
            } else {
                $sheet->setCellValue($columnIndex . $rowIndex, (string)$value);
            }
            $columnIndex++;

		## $sheet->setCellValue($columnIndex . $rowIndex, $value);
                ## $columnIndex++;

        }
        $rowIndex++;
    } ********/
} catch (Exception $e) {
    // Display the error message and stop execution
    echo 'Error: ' . $e->getMessage();
    exit;
}
        // Set headers for download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');

        // Save file
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

    }

	
	public function exportData___($queryResult, $columns, $filename, $chunkSize = 50) {
    $this->objPHPExcel->setActiveSheetIndex(0);
    $sheet = $this->objPHPExcel->getActiveSheet();

    // Write column names at the top
    $columnIndex = 'A';
    foreach ($columns as $columnName) {
        $sheet->setCellValue($columnIndex . '1', $columnName);
        $columnIndex++;
    }

    // Initialize variables
    $rowIndex = 2;
    $rowCount = 0;
    $chunkCount = 1;

    foreach ($queryResult as $row) {
        $columnIndex = 'A';

        foreach ($row as $value) {
            $sheet->setCellValue($columnIndex . $rowIndex, $value);
            $columnIndex++;
        }

        $rowIndex++;
        $rowCount++;

        // Check if chunk size is reached
        if ($rowCount >= $chunkSize) {
            // Save the current chunk
            $this->saveExcelChunk($filename, $chunkCount);

            // Clear the worksheet for the next chunk
            $this->objPHPExcel->disconnectWorksheets();
            $this->objPHPExcel->createSheet();

            // Reset variables for the next chunk
            $sheet = $this->objPHPExcel->getActiveSheet();
            $rowIndex = 2;
            $rowCount = 0;
            $chunkCount++;
        }
    }

    // Save the remaining data as the last chunk
    $this->saveExcelChunk($filename, $chunkCount);

    // Set headers for download
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
    header('Cache-Control: max-age=0');

    // Save file
    $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
}

private function saveExcelChunk($filename, $chunkCount) {
    // Save the current chunk to a file
    $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
    $filePath = $filename . '_chunk' . $chunkCount . '.xls';
    $objWriter->save($filePath);
}

	
	
	
	
	
}

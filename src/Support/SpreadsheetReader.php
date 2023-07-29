<?php 

namespace Source\Support;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class SpreadsheetReader {

    /**
    * @var Xlsx 
    */
    private $xlsx;
    
    public function __construct() {
        $this->xlsx = new Xlsx();
    }

    /**
    * @param string
    * @return array 
    */
    public function getXlsxData($path)
    {
        $this->xlsx->setReadDataOnly(true);
        $spreadsheet = $this->xlsx->load($path);
        $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
        return $sheet->toArray();    
    }    
   
}
?>



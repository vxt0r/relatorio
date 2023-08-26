<?php 

namespace app\classes;

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
    public function getXlsxData($filename)
    {
        $this->xlsx->setReadDataOnly(true);
        $spreadsheet = $this->xlsx->load(PATH['temp'].'/'.$filename);
        $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
        return $sheet->toArray();    
    }    
   
}



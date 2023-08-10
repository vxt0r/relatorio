<?php

namespace app\controllers;

use MF\controller\Action;
use MF\class\Container;

class IndexController extends Action{

    public function index()
    {
        $this->render('index','layout');
    }

    public function report()
    {
        if(!$this->validFile()){
            header('location:/?upload=false');
            die;
        }

        else{            
            $spreadsheet_reader = Container::getClass('SpreadsheetReader');
            $pdf_generator = Container::getClass('PDFGenerator');
            $format_report = Container::getClass('CashFlowReport');
            $this->createReport($spreadsheet_reader,$pdf_generator,$format_report);
        }
    }

    private function validFile()
    {
        $allowed = array('xlsx');
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        
        if (!in_array($ext, $allowed) || $_FILES['file']['size'] > 3145728) {
            return false;
        }

        return true;
    }

    private function createReport($spreadsheet_reader,$pdf_generator,$format_report)
    {
        $file = $_FILES['file'];
        $file_name = explode('.',$file['name'])[0];
        
        move_uploaded_file($file['tmp_name'],'temp/'.$file['name']);
        $report_name = $_POST['name'] ? $_POST['name'] : $file_name;
        $data = $spreadsheet_reader->getXlsxData('temp/'.$file['name']);
        $report = $format_report->cashFlowReport($data);
        $pdf_generator->generatePDF($report,$report_name);
        unlink('temp/'.$file['name']);   
    }
}

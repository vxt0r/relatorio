<?php

namespace app\controllers;

use MF\controller\Action;
use MF\class\Container;

require __DIR__.'/../Config.php'; 

class IndexController extends Action{

    public function index()
    {
        $this->render('index','layout');
    }

    public function report()
    {
        $uploader = Container::getClass('Uploader');
        $filename = $uploader->upload($_FILES['file']);

        if(!$this->validFile($filename)){
            header('location:/?upload=false');
            exit;
        }

        else{             

            $data = (Container::getClass('SpreadsheetReader'))
                ->getXlsxData($filename);

            $report = (Container::getClass('CashFlowReport'))
                ->cashFlowReport($data);

            (Container::getClass('PDFGenerator'))
                ->generatePDF($report,$_POST['name'],$filename);

            $uploader->delete($filename);
        }
    }

    private function validFile($filename)
    {
        $allowed = array('xlsx');
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (!in_array($ext, $allowed) || $_FILES['file']['size'] > 3145728) {
            return false;
        }

        return true;
    }
}

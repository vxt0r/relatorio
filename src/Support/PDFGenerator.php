<?php 

namespace Source\Support;

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFGenerator {

    /**
     * @var Dompdf
     */
    private $dompdf;

    /**
     * @var Options
     */
    private $options;

    /**
     * @var string
     */
    private const TEMPLATES_PATH = __DIR__.'/../../public/templates';

     /**
     * @var string
     */
    private const TEMP_PATH = __DIR__.'/../../public/temp';
    
    public function __construct() {
        $this->options = new Options();
        $this->options->setIsRemoteEnabled(true);
        $this->options->setChroot('/');
        $this->dompdf = new Dompdf($this->options);
    }

    /**
     * @param array
     * @return void
     */
    private function cashFlowHTMLReport($data)
    {
        copy(self::TEMPLATES_PATH.'/cash_flow.html',self::TEMP_PATH.'/cash_flow_template.html');
        $html = file_get_contents(self::TEMP_PATH.'/cash_flow_template.html');

        foreach($data as $field=>$value){
            $html = str_replace($field,$value,$html);
        }

        $report_html = fopen(self::TEMP_PATH.'/cash_flow_report.html','w');
        fwrite($report_html,$html);
        fclose($report_html);
        unlink(self::TEMP_PATH.'/cash_flow_template.html');
    }

    /**
     * @param array
     * @param string
     * @return void
     */
    public function generatePDF($data,$name)
    {
        $this->cashFlowHTMLReport($data);
        $this->dompdf->loadHtmlFile(self::TEMP_PATH.'/cash_flow_report.html');
        $this->dompdf->setPaper('A4', 'landscape');
        $this->dompdf->render();
        $this->dompdf->stream($filename=$name);
        unlink(self::TEMP_PATH.'/cash_flow_report.html');
    }
}
?>
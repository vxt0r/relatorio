<?php 

namespace app\classes;

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
        copy(PATH['templates'].'/cash_flow.html',PATH['temp'].'/cash_flow_template.html');
        $html = file_get_contents(PATH['temp'].'/cash_flow_template.html');

        foreach($data as $field=>$value){
            $html = str_replace($field,$value,$html);
        }

        $report_html = fopen(PATH['temp'].'/cash_flow_report.html','w');
        fwrite($report_html,$html);
        fclose($report_html);
        unlink(PATH['temp'].'/cash_flow_template.html');
    }

    /**
     * @param array
     * @param string
     * @return void
     */
    public function generatePDF($data,$new_name,$default_name)
    {
        $default_name = explode('.',$default_name)[0];
        $this->cashFlowHTMLReport($data);
        $this->dompdf->loadHtmlFile(PATH['temp'].'/cash_flow_report.html');
        $this->dompdf->setPaper('A4', 'landscape');
        $this->dompdf->render();
        $this->dompdf->stream($filename=$this->reportName($new_name,$default_name));
        unlink(PATH['temp'].'/cash_flow_report.html');
    }

    /**
     * @param string
     * @param string
     * @return string
     */
    private function reportName($new_name,$default_name)
    {
        return $new_name ? $new_name : $default_name;
    }
}

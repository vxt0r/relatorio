<?php 

namespace Source\ReportsFormat;

class CashFlowReport{

    /** 
     * Itera sobre a planilha, calculando os campos necessários para o relatório 
     * @param array
     * @return array 
    */
    public function cashFlowReport($data)
    {
        $report = [];
        $profit_month = $this->profitMonth($data);
        $report['profit_year'] = $this->profitYear($profit_month);
        $report['profit_mean'] = $this->profitMean($report['profit_year']);
        $max = $this->max($profit_month);
        $report['max_month'] = $max[0];
        $report['max_value'] = $max[1];
        $report['loss'] = $this->loss($profit_month);
        $report['profit_percent'] = $this->profitPercent($data,$report['profit_year']);
        return $report;
    }

    private function profitMonth($data){
        unset($data[0]);

        $profit_month = [];

        foreach($data as $cell){
            $profit_month[$cell[0]] = $cell['2'] - $cell['1'];
        }

        return $profit_month;
    }

    private function profitYear($profit_month){
        $profit_year = array_reduce($profit_month,function($carry,$value){
            $carry += $value;
            return $carry;
        },0);

        return round($profit_year,2);
    }

    private function profitMean($profit_year){
        return round(($profit_year/12),2);
    }

    private function max($profit_month){
        $max_value = round(max($profit_month),2);

        $max_month = array_keys(array_filter($profit_month, function ($element) use ($max_value) { 
            return $element == $max_value; 
        }));

        if(count($max_month) > 1){
            $max_month = implode(', ',$max_month);
        }
        else{
            $max_month = $max_month[0];
        }
        return [$max_month,round($max_value,2)];
    }

    private function loss($profit_month){
        $loss = array_filter($profit_month,function($element){
            return $element < 0;
        });

        $loss = array_keys($loss);

        if(count($loss) > 1){
            $loss = implode(', ',$loss);
        }
        else{
            $loss = $loss[0];
        }

        return $loss;
    }

    private function profitPercent($data,$profit_year){
        $annual_expense = array_reduce($data,function($carry,$element){
            $carry += (float)$element[1];
            return $carry;
        },0);

        return round(($profit_year/$annual_expense)*100,2);
    }
}
?>
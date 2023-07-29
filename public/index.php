<?php

require __DIR__.'/../vendor/autoload.php';

use Source\Support\SpreadsheetReader;
use Source\Support\PDFGenerator;
use Source\ReportsFormat\CashFlowReport;

$spreadsheet_reader = new SpreadsheetReader();
$pdf_generator = new PDFGenerator();
$format_report = new CashFlowReport();

if(isset($_FILES['file'])){

    $file = $_FILES['file'];
    $file_name = explode('.',$file['name'])[0];
    
    $allowed = array('xlsx');
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    
    if (!in_array($ext, $allowed) || $_FILES['file']['size'] > 3145728) {
        header('location:?upload=false');
    }

    else{
        move_uploaded_file($file['tmp_name'],'temp/'.$file['name']);
        
        $report_name = $_POST['name'] ? $_POST['name'] : $file_name;
    
        $data = $spreadsheet_reader->getXlsxData('temp/'.$file['name']);
        $report = $format_report->cashFlowReport($data);
        $pdf_generator->generatePDF($report,$report_name);
      
        unlink('temp/'.$file['name']);
    }

}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Relatório</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Gerador de Relatório</h1>
        <p>Envie o arquivo da sua planilha do excel e receba um relatório em pdf ! <a href="?details=true">Detalhes</a></p>
        <?php if(isset($_GET['details'])){ ?>
            <p>
                A planilha deve conter cabeçalhos e os dados na seguinte ordem: mês, gasto e ganho,
                e começar da primeira linha e coluna.
            </p>
        <?php } ?>
    </header>
    <main>

        <?php if(isset($_GET['upload']) && $_GET['upload'] == 'false'){ ?>
            <h2>Ocorreu algum erro ao enviar o arquivo</h2>
            <p>Verifique o formato ou o tamanho do arquivo(máx: 3MB)</p>
        <?php } ?>

        <section>
            <form action="?upload=true" enctype="multipart/form-data" method="POST">
                <label>Insira um arquivo (xlsx)</label>
                <input type="file" name="file" id="file">
                <label>Defina um nome para seu relatório</label>
                <input type="text" name="name">
                <button>Gerar Relatório</button>
            </form>
        </section>
    </main>
</body>
</html>
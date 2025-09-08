<?php
// Define o fuso horário para garantir a hora de impressão correta
date_default_timezone_set('America/Sao_Paulo');

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Configuração do Dompdf
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$options->setIsHtml5ParserEnabled(true);
$dompdf = new Dompdf($options);

// Conexão com banco de dados
$pdo = new PDO("mysql:host=db_financaspi.mysql.dbaas.com.br;dbname=db_financaspi;charset=utf8", "db_financaspi", "Gzfinanceiro1@");

// Filtros
$mes = isset($_GET['mes']) ? (int) $_GET['mes'] : date('n');
$ano = isset($_GET['ano']) ? (int) $_GET['ano'] : date('Y');

// Array com nomes dos meses
$nomesMeses = [
    1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril",
    5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto",
    9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro"
];
$mesNome = $nomesMeses[$mes];

// Consulta ao banco de dados
$sql = "SELECT desc_plano, desc_lanc, data_venc, valor_lanc
        FROM view_pagamento_mes_ano_atual
        WHERE MONTH(data_venc) = :mes
        AND YEAR(data_venc) = :ano
        ORDER BY data_venc ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':mes' => $mes, ':ano' => $ano]);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Caminho do logo no servidor
$logoPath = 'images/logo.png';

// Verifica se o arquivo da imagem existe e o converte para Base64
if (file_exists($logoPath)) {
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoSrc = 'data:image/jpeg;base64,' . $logoData;
} else {
    // Se a imagem não for encontrada, exibe uma mensagem de erro e não tenta carregar a imagem.
    die("Erro: O arquivo de logo não foi encontrado em: " . $logoPath);
}

// Estilo CSS para o documento
$html = "
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 20px; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header-table td { vertical-align: middle; padding: 0; border: none; } /* Linha alterada */
        .header-table .logo-cell { width: 100px; text-align: left; }
        .header-table .title-cell { text-align: center; }
        .header-table .title-cell h2 { color: #007BFF; font-size: 20px; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #007BFF; color: #fff; text-align: center; padding: 8px; font-size: 13px; }
        td { border: 1px solid #ddd; padding: 6px; font-size: 12px; }
        tr:nth-child(even) { background: #f9f9f9; }
        tr:hover { background: #f1f1f1; }
        .total { background: #0056b3; color: #fff; font-weight: bold; }
        .total td { border: none; background: #007BFF;}
    </style>
";

// Cria a nova estrutura do cabeçalho com logo e título usando uma tabela
$html .= "<table class='header-table'>
            <tr>
            <td class='logo-cell'>
                <img src='{$logoSrc}' width='80' height='40' />
            </td>
            <td class='title-cell'>
                <h2>Pagamentos do Mês de {$mesNome} de {$ano}</h2>
            </td>
            </tr>
        </table>";

$html .= "<table>
                <tr>
                    <th>Plano de Contas</th>
                    <th>Descrição</th>
                    <th>Vencimento</th>
                    <th>Valor (R$)</th>
                </tr>";

$total = 0;
foreach ($dados as $row) {
    $html .= "<tr>
                    <td>{$row['desc_plano']}</td>
                    <td>{$row['desc_lanc']}</td>
                    <td style='text-align:center'>" . date('d/m/Y', strtotime($row['data_venc'])) . "</td>
                    <td style='text-align:right'>" . number_format($row['valor_lanc'], 2, ',', '.') . "</td>
                </tr>";
    $total += $row['valor_lanc'];
}

$html .= "<tr class='total'>
                <td colspan='3' align='right'>Total Geral R$</td>
                <td align='right' style='font-size: 14px;'>" . number_format($total, 2, ',', '.') . "</td>
            </tr>";
$html .= "</table>";

// Gera o PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$canvas = $dompdf->getCanvas();
$font = $dompdf->getFontMetrics()->get_font("DejaVu Sans", "normal");

// Acrescenta a data de impressão no rodapé
$dateString = "Data de Impressão: " . date('d/m/Y H:i:s');
$canvas->page_text(20, 820, $dateString, $font, 9, [0,0,0]);

// Acrescenta a numeração de páginas no rodapé
$canvas->page_text(520, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 9, [0,0,0]);

// Exibe o PDF no navegador
$dompdf->stream("Pagamentos_{$mesNome}_{$ano}.pdf", ["Attachment" => false]);
?>
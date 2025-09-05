<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SFP-GZ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <style>
        /* ========================== BASE ========================== */
body {
    background: #f7f7fa;
    min-height: 100vh;
    font-family: 'Segoe UI', Arial, sans-serif;
}

/* Sidebar */
.sidebar {
    background: #4B2673;
    color: #fff;
    min-height: 100vh;
    padding-top: 2rem;
}
.sidebar .nav-link, .sidebar .nav-link:visited {
    color: #fff;
    font-weight: 500;
    margin-bottom: 1rem;
    border-radius: 8px;
    transition: background 0.2s;
}
.sidebar .nav-link.active, .sidebar .nav-link:hover {
    background: #7c3aed;
    color: #fbbf24;
}

/* Cards */
.dashboard-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(75,38,115,0.10);
    padding: 2rem 1.5rem;
    margin-bottom: 2rem;
    position: relative;
    z-index: 2;
    border: 1px solid #ececec;
}
.dashboard-card h5 {
    color: #4B2673;
    font-weight: bold;
}

/* Glass Effect (Desktop only) */
.glass-effect {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #ececec;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(75,38,115,0.10);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
}

/* ========================== MOBILE ========================== */
@media (max-width: 768px) {
    .glass-effect {
        background: #fff !important;          /* fundo sólido */
        backdrop-filter: none !important;     /* remove blur */
        -webkit-backdrop-filter: none !important;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08) !important;
    }

    /* Sidebar responsivo */
    #sidebar {
        position: fixed;
        top: 0;
        left: -100vw;
        width: 80vw;
        max-width: 320px;
        min-height: 100vh;
        height: 100vh;
        transition: left 0.3s;
        z-index: 1040;
    }
    #sidebar.active {
        left: 0;
    }
    #sidebar-overlay.active {
        display: block;
    }
    main, .main-content {
        filter: none !important;       /* remove blur de fundo */
        pointer-events: auto !important;
        margin-left: 0 !important;
    }

    /* Cards full width */
    .dashboard-card {
        width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
}

/* ========================== DESKTOP ========================== */
@media (min-width: 769px) {
    main, .main-content {
        margin-left: 260px;
    }
}

    </style>
</head>
<body class="vh-100">
<?php
require_once dirname(__DIR__) . '/model/Lancamento.class.php';
$lancamento = new Lancamento();
$receitasPorMes = $lancamento->getReceitasPorMesAnoAtual();
$despesasPorMes = $lancamento->getDespesasPorMesAnoAtual();

$receitas = array_fill(0, 12, 0);
$despesas = array_fill(0, 12, 0);

if ($receitasPorMes) {
    foreach ($receitasPorMes as $item) {
        $receitas[$item->mes - 1] = (float)$item->total;
    }
}
if ($despesasPorMes) {
    foreach ($despesasPorMes as $item) {
        $despesas[$item->mes - 1] = (float)$item->total;
    }
}

// Buscar composição das despesas por categoria/plano
$composicaoDespesas = [];
try {
    $bd = $lancamento->conectar();
    $sql = "SELECT tp.desc_plano as categoria, SUM(valor_lanc) as total 
            FROM db_financaspi.tb_lancamento tl 
            LEFT JOIN db_financaspi.tb_cad_plano tp ON tp.id_cad_plano = tl.id_cad_plano 
            WHERE tl.id_cad_tipo = 2 
            AND YEAR(tl.data_venc) = YEAR(CURDATE()) 
            GROUP BY tp.desc_plano 
            ORDER BY total DESC";
    $query = $bd->prepare($sql);
    $query->execute();
    $composicaoDespesas = $query->fetchAll(PDO::FETCH_OBJ);
} catch (Exception $e) {
    $composicaoDespesas = [];
}

$categoriasDespesas = array_map(fn($item) => $item->categoria, $composicaoDespesas);
$valoresDespesas = array_map(fn($item) => (float)$item->total, $composicaoDespesas);
?>
<header>
    <?php 
    require_once dirname(__DIR__) . '/controller/Controller.class.php';
    $controller = new Controller();
    $controller->menu();
    ?>
</header>
<main>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="dashboard-card card-mes-atual">
                    <p class="fs-3 text-success"><?php echo $controller->viewReceita(); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card card-mes-atual">
                    <p class="fs-3 text-danger"><?php echo $controller->viewDespesa(); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card card-mes-atual glass-effect text-center">
                    <p class="fs-3 text-primary"><?php echo $controller->viewSaldo(); ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Comparativo Mensal -->
            <div class="col-12 col-lg-6 d-flex flex-column">
                <div class="dashboard-card" style="width: 100%;">
                    <h5 class="mb-3 text-center">Comparativo Mensal</h5>
                    <canvas id="graficoFinanceiro" height="120"></canvas>
                </div>
                <div class="dashboard-card" style="width: 100%;">
                    <h5 class="mb-3 text-center">Saldo Acumulado/Mês</h5>
                    <canvas id="graficoSaldoAcumulado" height="120"></canvas>
                </div>
            </div>

            <!-- Composição das Despesas -->
            <div class="col-12 col-lg-6 d-flex align-items-stretch justify-content-end">
                <div class="dashboard-card glass-effect text-center w-100" style="max-width: 700px; margin-left: auto;">
                    <h5 class="mb-3">Composição das Despesas</h5>
                    <canvas id="graficoComposicaoDespesas" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>
<footer>
    <!-- place footer here -->
</footer>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
    const meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    const receitas = <?php echo json_encode($receitas); ?>;
    const despesas = <?php echo json_encode($despesas); ?>;
    const categoriasDespesas = <?php echo json_encode($categoriasDespesas); ?>;
    const valoresDespesas = <?php echo json_encode($valoresDespesas); ?>;

    // Gráfico comparativo receitas x despesas
    new Chart(document.getElementById('graficoFinanceiro'), {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [
                { label: 'Receitas', data: receitas, backgroundColor: '#7c3aed' },
                { label: 'Despesas', data: despesas, backgroundColor: '#ffd700' }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Gráfico de composição das despesas
    new Chart(document.getElementById('graficoComposicaoDespesas'), {
        type: 'doughnut',
        data: {
            labels: categoriasDespesas,
            datasets: [{
                label: 'Despesas',
                data: valoresDespesas,
                backgroundColor: [
                    '#7c3aed', '#e53e3e', '#38bdf8', '#fbbf24',
                    '#22c55e', '#f472b6', '#818cf8', '#f59e42',
                    '#10b981', '#a3e635', '#f43f5e', '#6366f1'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 16 },
                    formatter: (value, ctx) => {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        return total ? ((value / total) * 100).toFixed(1) + '%' : value;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // Gráfico de saldo acumulado
    const saldoAcumulado = meses.map((_, i) => {
        let saldo = 0;
        for (let j = 0; j <= i; j++) saldo += (receitas[j] || 0) - (despesas[j] || 0);
        return saldo;
    });

    new Chart(document.getElementById('graficoSaldoAcumulado'), {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'Saldo Acumulado',
                data: saldoAcumulado,
                fill: true,
                backgroundColor: 'rgba(124,58,237,0.15)',
                borderColor: '#7c3aed',
                tension: 0.4,
                pointBackgroundColor: '#7c3aed',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
</body>
</html>

<body class="vh-100">
    <body class="vh-100">
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
            body {
                background: #f7f7fa;
                min-height: 100vh;
            }
            .sidebar {
                background: #4B2673;
                color: #fff;
                min-height: 100vh;
                padding-top: 2rem;
                font-family: 'Segoe UI', Arial, sans-serif;
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
            .dashboard-card .fs-3 {
                font-weight: bold;
                font-size: 2rem;
            }
            .dashboard-card .text-success {
                color: #22c55e !important;
            }
            .dashboard-card .text-danger {
                color: #e53e3e !important;
            }
            .dashboard-card .text-primary {
                color: #7c3aed !important;
            }
            .glass-effect {
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid #ececec;
                border-radius: 16px;
                position: relative;
                z-index: 2;
                box-shadow: 0 2px 16px rgba(75,38,115,0.10);
            }
            .dashboard-header {
                color: #4B2673;
                font-size: 2.2rem;
                font-weight: bold;
                letter-spacing: 1px;
            }
            .dashboard-highlight {
                background: linear-gradient(90deg, #7c3aed 60%, #4B2673 100%);
                color: #fbbf24;
                border-radius: 12px;
                padding: 1rem 2rem;
                font-size: 1.5rem;
                font-weight: bold;
                box-shadow: 0 2px 8px rgba(75,38,115,0.10);
                margin-bottom: 1rem;
            }
            .dashboard-label {
                color: #4B2673;
                font-weight: 500;
            }
            .dashboard-value {
                color: #fbbf24;
                font-weight: bold;
                font-size: 2rem;
            }
            .dashboard-user {
                color: #4B2673;
                font-weight: 500;
                font-size: 1.1rem;
            }
            .dashboard-invest {
                color: #7c3aed;
                font-weight: bold;
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
            $sql = "SELECT tp.desc_plano as categoria, SUM(valor_lanc) as total FROM db_financaspi.tb_lancamento tl LEFT JOIN db_financaspi.tb_cad_plano tp ON tp.id_cad_plano = tl.id_cad_plano WHERE tl.id_cad_tipo = 2 AND YEAR(tl.data_venc) = YEAR(CURDATE()) GROUP BY tp.desc_plano ORDER BY total DESC";
            $query = $bd->prepare($sql);
            $query->execute();
            $composicaoDespesas = $query->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            $composicaoDespesas = [];
        }
        $categoriasDespesas = array_map(function($item) { return $item->categoria; }, $composicaoDespesas);
        $valoresDespesas = array_map(function($item) { return (float)$item->total; }, $composicaoDespesas);
        ?>
        <header>
            <!-- place navbar here -->
            <?php echo $menu ?>
        </header>
        <main>
            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="dashboard-card card-mes-atual">
                            <!-- Título removido -->
                            <p class="fs-3 text-success"><?php echo $this->viewReceita(); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-card card-mes-atual">
                            <!-- Título removido -->
                            <p class="fs-3 text-danger"><?php echo $this->viewDespesa(); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-card card-mes-atual glass-effect text-center">
                            <!-- Título removido -->
                            <p class="fs-3 text-primary">
                                <?php 
                                require_once dirname(__DIR__) . '/controller/Controller.class.php';
                                $controller = new Controller();
                                echo $controller->viewSaldo(); 
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Cards de gráficos alinhados à esquerda e card de composição à direita -->
                <div class="row">
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
                    <div class="col-12 col-lg-6 d-flex align-items-stretch justify-content-end">
                        <div class="dashboard-card glass-effect text-center w-100" style="max-width: 700px; margin-left: auto;">
                            <h5 class="mb-3">Composição das Despesas</h5>
                            <canvas id="graficoComposicaoDespesas" height="120"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Os cards de gráficos agora estão dentro do grid principal, removendo duplicatas vazias -->
            </div>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script>
            const meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            const receitas = <?php echo json_encode($receitas); ?>;
            const despesas = <?php echo json_encode($despesas); ?>;
            // Dados para gráfico de rosca (composição das despesas)
            const categoriasDespesas = <?php echo json_encode($categoriasDespesas); ?>;
            const valoresDespesas = <?php echo json_encode($valoresDespesas); ?>;
            const ctx = document.getElementById('graficoFinanceiro').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: meses,
                    datasets: [
                        {
                            label: 'Receitas',
                            data: receitas,
                            backgroundColor: '#7c3aed',
                        },
                        {
                            label: 'Despesas',
                            data: despesas,
                            backgroundColor: '#ffd700',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Gráfico de rosca para composição das despesas
            const ctxComposicao = document.getElementById('graficoComposicaoDespesas').getContext('2d');
            new Chart(ctxComposicao, {
                type: 'doughnut',
                data: {
                    labels: categoriasDespesas,
                    datasets: [{
                        label: 'Despesas',
                        data: valoresDespesas,
                        backgroundColor: [
                            '#7c3aed', '#e53e3e', '#38bdf8', '#fbbf24', '#22c55e', '#f472b6', '#818cf8', '#f59e42', '#10b981', '#a3e635', '#f43f5e', '#6366f1'
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: false },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 16
                            },
                            formatter: function(value, context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percent = total ? ((value / total) * 100).toFixed(1) + '%' : value;
                                return percent;
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            // Saldo acumulado por mês
            const saldoAcumulado = meses.map((_, i) => {
                let saldo = 0;
                for (let j = 0; j <= i; j++) {
                    saldo += (receitas[j] || 0) - (despesas[j] || 0);
                }
                return saldo;
            });
            const ctxSaldo = document.getElementById('graficoSaldoAcumulado').getContext('2d');
            new Chart(ctxSaldo, {
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
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>
    </body>
</html>